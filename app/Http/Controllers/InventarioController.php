<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Asegúrate de que este facade esté importado
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Mail\NotificacionOrdenCompra;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\OrdenCompra;
use App\Models\DetalleOrdenCompra;
use App\Models\Compra;
use App\Models\DetalleCompra;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        // KPIs
        $valorTotalInventario = Producto::sum(DB::raw('precio_venta * stock_actual'));
        $productosActivos = Producto::where('estado', 'activo')->count();
        $productosInactivos = Producto::where('estado', 'inactivo')->count();
        $productosBajoStock = Producto::where('stock_minimo', '>', 0)
                                     ->whereColumn('stock_actual', '<=', 'stock_minimo')
                                     ->count();

        $query = Producto::with(['subcategoria.categoria', 'proveedor', 'modelos']);

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('codigo_sku', 'like', '%' . $search . '%');
        }

        if ($request->has('subcategoria_id') && $request->input('subcategoria_id') != '') {
            $query->where('subcategoria_id', $request->input('subcategoria_id'));
        }

        if ($request->has('proveedor_id') && $request->input('proveedor_id') != '') {
            $query->where('proveedor_id', $request->input('proveedor_id'));
        }

        if ($request->has('filtro_kpi')) {
            switch ($request->get('filtro_kpi')) {
                case 'activos':
                    $query->where('estado', 'activo');
                    break;
                case 'inactivos':
                    $query->where('estado', 'inactivo');
                    break;
                case 'bajo_stock':
                    $query->where('stock_minimo', '>', 0)
                          ->whereColumn('stock_actual', '<=', 'stock_minimo');
                    break;
            }
        }

        $productos = $query->paginate(10);
        $subcategorias = Subcategoria::with('categoria')->get();
        $proveedores = Proveedor::all();

        return view('inventario.index', compact(
            'productos', 
            'subcategorias', 
            'proveedores',
            'valorTotalInventario',
            'productosActivos',
            'productosInactivos',
            'productosBajoStock'
        ));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $proveedores = Proveedor::all();
        return view('inventario.create', compact('categorias', 'subcategorias', 'marcas', 'modelos', 'proveedores'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo_sku' => 'nullable|string|max:255|unique:productos,codigo_sku',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'foto_camera' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'stock_maximo' => 'required|integer|min:0',
            'estado' => 'required|boolean',
            'fecha_vencimiento' => 'nullable|date',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'proveedor_id' => 'required|exists:proveedores,id',
            'modelos' => 'nullable|array',
            'modelos.*' => 'exists:modelos,id',
        ]);

        $data = $validatedData;

        $imageFile = $request->hasFile('foto') ? $request->file('foto') : ($request->hasFile('foto_camera') ? $request->file('foto_camera') : null);

        if ($imageFile) {
            $file = $imageFile;
            $fileName = ($request->input('codigo_sku') ?? uniqid('product_')) . '.' . $file->extension();
            
            // Usar el disco 'product_images' que apunta a public/product_images
            Storage::disk('product_images')->putFileAs('/', $file, $fileName);
            $data['foto_url'] = $fileName; // Guardar solo el nombre del archivo
        }

        // Eliminar 'foto' y 'foto_camera' del array antes de crear
        unset($data['foto'], $data['foto_camera']);

        $producto = Producto::create($data);

        if ($request->has('modelos')) {
            $producto->modelos()->attach($request->input('modelos'));
        }

        return redirect()->route('inventario.index')->with('success', 'Producto creado exitosamente.');
    }


    public function show(Producto $producto)
    {
        return view('inventario.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $proveedores = Proveedor::all();
        return view('inventario.edit', compact('producto', 'categorias', 'subcategorias', 'marcas', 'modelos', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        Log::info('--- INICIO DE ACTUALIZACIÓN DE PRODUCTO ---', ['id' => $producto->id, 'request' => $request->except('foto')]);

        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'codigo_sku' => ['nullable', 'string', 'max:255', Rule::unique('productos', 'codigo_sku')->ignore($producto->id)],
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
                'foto_camera' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
                'precio_venta' => 'required|numeric|min:0',
                'stock_actual' => 'required|integer|min:0',
                'stock_minimo' => 'nullable|integer|min:0',
                'stock_maximo' => 'nullable|integer|min:0',
                'estado' => 'required|string|in:activo,inactivo',
                'categoria_id' => 'required|exists:categorias,id',
                'subcategoria_id' => 'nullable|exists:subcategorias,id',
                'proveedor_id' => 'required|exists:proveedores,id',
                'modelos' => 'nullable|array',
                'modelos.*' => 'exists:modelos,id',
            ]);
            Log::info('[PASO 1] Validación completada exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('[ERROR DE VALIDACIÓN]', ['errors' => $e->errors()]);
            throw $e;
        }

        $data = $validatedData;

        $imageFile = $request->hasFile('foto') ? $request->file('foto') : ($request->hasFile('foto_camera') ? $request->file('foto_camera') : null);

        if ($imageFile) {
            Log::info('[PASO 2] Se detectó un archivo de imagen para subir.', ['source' => $request->hasFile('foto') ? 'foto' : 'foto_camera']);
            $file = $imageFile;
            $fileName = ($request->input('codigo_sku') ?? 'prod_' . $producto->id) . '_' . time() . '.' . $file->extension();

            if ($producto->foto_url) {
                Log::info('[PASO 2.1] El producto tiene una imagen antigua. Intentando borrarla.');
                if (Storage::disk('product_images')->exists($producto->foto_url)) {
                    Storage::disk('product_images')->delete($producto->foto_url);
                    Log::info('[PASO 2.2] Imagen antigua borrada: ' . $producto->foto_url);
                } else {
                    Log::warning('[PASO 2.2] La imagen antigua no se encontró en el disco: ' . $producto->foto_url);
                }
            }
            
            Log::info('[PASO 2.3] Almacenando nueva imagen con el nombre: ' . $fileName);
            // Usar el disco 'product_images' que apunta a public/product_images
            Storage::disk('product_images')->putFileAs('/', $file, $fileName);
            $data['foto_url'] = $fileName; // Guardar solo el nombre del archivo
            Log::info('[PASO 2.4] Nuevo nombre de archivo de imagen generado: ' . $data['foto_url']);
        } else {
            Log::info('[PASO 2] No se subió un nuevo archivo de imagen.');
        }
        
        unset($data['foto'], $data['foto_camera']);
        Log::info('[PASO 3] Datos listos para la actualización en la BD.', $data);

        Log::info('[PASO 4] Intentando ejecutar $producto->update()...');
        $producto->update($data);
        Log::info('[PASO 5] El método $producto->update() se ejecutó. El observer debería haberse activado.');

        if (isset($data['modelos'])) {
            Log::info('[PASO 6] Sincronizando modelos...', ['modelos' => $data['modelos']]);
            $producto->modelos()->sync($data['modelos']);
        } else {
            Log::info('[PASO 6] Desvinculando todos los modelos.');
            $producto->modelos()->detach();
        }

        Log::info('--- FIN DE ACTUALIZACIÓN. REDIRIGIENDO... ---');
        return redirect()->route('inventario.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        Log::info("--- INICIO PROCESO DE ELIMINACIÓN DE PRODUCTO ---", ['producto_id' => $producto->id]);

        try {
            DB::transaction(function () use ($producto) {
                Log::info("Paso 1: Desvinculando modelos...");
                $producto->modelos()->detach();
                Log::info("Modelos desvinculados.");

                Log::info("Paso 2: Eliminando detalles de ventas...");
                $producto->detalleVentas()->delete();
                Log::info("Detalles de ventas eliminados.");

                Log::info("Paso 3: Eliminando detalles de compras...");
                $producto->detalleCompras()->delete();
                Log::info("Detalles de compras eliminados.");

                if ($producto->foto_url) {
                    Log::info("Paso 4: Eliminando imagen del producto...", ['url' => $producto->foto_url]);
                    if (Storage::disk('product_images')->exists($producto->foto_url)) {
                        Storage::disk('product_images')->delete($producto->foto_url);
                        Log::info("Imagen eliminada del disco.");
                    } else {
                        Log::warning("La imagen no se encontró en el disco.", ['url' => $producto->foto_url]);
                    }
                }

                Log::info("Paso 5: Eliminando el producto de la base de datos...");
                $producto->delete();
                Log::info("Producto eliminado de la base de datos.");
            });

            Log::info("--- FIN PROCESO DE ELIMINACIÓN. ÉXITO. ---", ['producto_id' => $producto->id]);
            return redirect()->route('inventario.index')->with('success', 'Producto eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error("--- ERROR DURANTE LA ELIMINACIÓN DEL PRODUCTO ---", [
                'producto_id' => $producto->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('inventario.index')->with('error', 'No se pudo eliminar el producto. Revise los logs para más detalles.');
        }
    }

    public function showReponerStock()
    {
        $productosParaReponer = Producto::with('proveedor')
            ->where('stock_minimo', '>', 0)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('proveedor_id')
            ->get();

        $nombreEmpresa = env('NOMBRE_DE_LA_EMPRESA', 'Su Empresa');
        $rifEmpresa = env('RIF', 'J-00000000-0');

        return view('inventario.reponer-stock', compact('productosParaReponer', 'nombreEmpresa', 'rifEmpresa'));
    }

    public function enviarNotificaciones(Request $request)
    {
        Log::info('--- INICIO DE ENVÍO DE NOTIFICACIONES ---', ['request_data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'proveedores' => 'required|array|min:1',
            'proveedores.*.id' => 'required|exists:proveedores,id',
            'proveedores.*.mensaje' => 'required|string',
            'proveedores.*.productos' => 'required|array',
            'proveedores.*.productos.*.id' => 'required|exists:productos,id',
            'proveedores.*.productos.*.cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            Log::error('[ERROR DE VALIDACIÓN]', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $nombreEmpresa = env('NOMBRE_DE_LA_EMPRESA', 'Su Empresa');
        $rifEmpresa = env('RIF', 'J-00000000-0');
        $datosParaConfirmacion = [];

        foreach ($request->input('proveedores') as $proveedorData) {
            $proveedor = Proveedor::find($proveedorData['id']);
            if (!$proveedor) {
                Log::warning('Proveedor no encontrado con ID: ' . $proveedorData['id']);
                continue;
            }

            Log::info("Procesando proveedor: {$proveedor->nombre} (ID: {$proveedor->id})");

            if ($proveedor && $proveedor->correo) {
                try {
                    Log::info("Intentando enviar correo a: {$proveedor->correo}");
                    Mail::to($proveedor->correo)->send(new NotificacionOrdenCompra(
                        $proveedor,
                        $proveedorData['productos'],
                        $proveedorData['mensaje'],
                        $nombreEmpresa,
                        $rifEmpresa
                    ));
                    Log::info("Correo de solicitud enviado exitosamente a {$proveedor->nombre} ({$proveedor->correo})");
                } catch (\Exception $e) {
                    Log::error("Fallo al enviar correo a {$proveedor->correo}: " . $e->getMessage());
                }
            } else {
                Log::warning("Proveedor {$proveedor->nombre} (ID: {$proveedor->id}) no tiene un correo electrónico registrado. No se envió notificación.");
            }
            
            // Preparamos los datos para la siguiente pestaña independientemente del envío del correo
            $datosParaConfirmacion[] = [
                'proveedor' => $proveedor,
                'productos' => $proveedorData['productos']
            ];

            // Guardar la orden de compra en la base de datos
            $ordenCompra = OrdenCompra::create([
                'proveedor_id' => $proveedor->id,
                'estado' => 'pendiente',
                'mensaje_correo' => $proveedorData['mensaje'],
            ]);

            foreach ($proveedorData['productos'] as $productoItem) {
                DetalleOrdenCompra::create([
                    'orden_compra_id' => $ordenCompra->id,
                    'producto_id' => $productoItem['id'],
                    'cantidad' => $productoItem['cantidad'],
                ]);
            }
            Log::info("Orden de compra #{$ordenCompra->id} creada para el proveedor {$proveedor->nombre}.");
        }

        // Obtener las órdenes de compra pendientes para la vista de confirmación
        $ordenesPendientes = OrdenCompra::with('proveedor', 'detalles.producto')->where('estado', 'pendiente')->get();
        Log::info('Datos para confirmación obtenidos de la base de datos.', ['ordenes_pendientes_count' => $ordenesPendientes->count()]);

        Log::info('--- FIN DE ENVÍO DE NOTIFICACIONES ---');
        return response()->json([
            'message' => 'Proceso de notificación completado. Revise los logs para más detalles. Ahora puede proceder a confirmar las compras.',
            'confirmacion_html' => view('partials.confirmacion_compra', ['datosParaConfirmacion' => $ordenesPendientes])->render()
        ]);
    }

    public function showOrdenesPendientes()
    {
        $ordenesPendientes = OrdenCompra::with('proveedor', 'detalles.producto')
                                        ->where('estado', 'pendiente')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
        return view('inventario.ordenes-pendientes', compact('ordenesPendientes'));
    }

    public function getOrdenDetalles(OrdenCompra $ordenCompra)
    {
        // Cargar los detalles de la orden con los productos asociados
        $ordenCompra->load('detalles.producto');

        // Formatear los datos para que sean fáciles de consumir por JavaScript
        $productosDetalles = $ordenCompra->detalles->map(function ($detalle) {
            return [
                'producto_id' => $detalle->producto_id,
                'nombre_producto' => $detalle->producto->nombre ?? 'Producto Eliminado',
                'cantidad_solicitada' => $detalle->cantidad,
                'precio_sugerido' => $detalle->producto->precio_venta ?? 0, // O un precio de compra si existe en Producto
            ];
        });

        return response()->json([
            'orden_id' => $ordenCompra->id,
            'productos' => $productosDetalles,
        ]);
    }

    public function reenviarNotificacion(Request $request, OrdenCompra $ordenCompra)
    {
        Log::info('--- INICIO DE REENVÍO DE NOTIFICACIÓN ---', ['orden_compra_id' => $ordenCompra->id]);

        $proveedor = $ordenCompra->proveedor;
        $productos = $ordenCompra->detalles->map(function($detalle) {
            return [
                'id' => $detalle->producto_id,
                'nombre' => $detalle->producto->nombre,
                'cantidad' => $detalle->cantidad,
            ];
        })->toArray();
        $mensaje = $ordenCompra->mensaje_correo;
        $nombreEmpresa = env('NOMBRE_DE_LA_EMPRESA', 'Su Empresa');
        $rifEmpresa = env('RIF', 'J-00000000-0');

        if ($proveedor && $proveedor->correo) {
            try {
                Log::info("Intentando reenviar correo a: {$proveedor->correo} para OC #{$ordenCompra->id}");
                Mail::to($proveedor->correo)->send(new NotificacionOrdenCompra(
                    $proveedor,
                    $productos,
                    $mensaje,
                    $nombreEmpresa,
                    $rifEmpresa
                ));
                Log::info("Correo de solicitud reenviado exitosamente a {$proveedor->nombre} ({$proveedor->correo}) para OC #{$ordenCompra->id}");
                return response()->json(['message' => 'Correo reenviado exitosamente.'], 200);
            } catch (\Exception $e) {
                Log::error("Fallo al reenviar correo a {$proveedor->correo} para OC #{$ordenCompra->id}: " . $e->getMessage());
                return response()->json(['error' => 'Fallo al reenviar el correo.'], 500);
            }
        } else {
            Log::warning("Proveedor {$proveedor->nombre} (ID: {$proveedor->id}) no tiene un correo electrónico registrado. No se pudo reenviar notificación para OC #{$ordenCompra->id}.");
            return response()->json(['error' => 'El proveedor no tiene un correo electrónico registrado.'], 400);
        }
    }

    public function confirmarRecepcion(Request $request, OrdenCompra $ordenCompra)
    {
        Log::info('--- INICIO DE CONFIRMACIÓN DE RECEPCIÓN DE ORDEN DE COMPRA ---', ['orden_compra_id' => $ordenCompra->id, 'request_data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad_recibida' => 'required|integer|min:0',
            'productos.*.precio_compra_unitario' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('[ERROR DE VALIDACIÓN EN CONFIRMAR RECEPCIÓN]', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $empleadoId = auth()->id(); // Obtener el ID del empleado autenticado antes de la transacción

            DB::transaction(function () use ($ordenCompra, $request, $empleadoId) {
                $productosRecibidos = $request->input('productos');
                $totalCompra = 0;

                // 1. Actualizar el stock de los productos y calcular el total de la compra
                foreach ($productosRecibidos as $productoData) {
                    $producto = Producto::find($productoData['producto_id']);
                    if ($producto) {
                        $cantidadRecibida = $productoData['cantidad_recibida'];
                        $precioUnitario = $productoData['precio_compra_unitario'];

                        $producto->stock_actual += $cantidadRecibida;
                        $producto->save();
                        Log::info("Stock actualizado para producto {$producto->nombre} (ID: {$producto->id}). Cantidad añadida: {$cantidadRecibida}. Nuevo stock: {$producto->stock_actual}");

                        $totalCompra += $precioUnitario * $cantidadRecibida;
                    } else {
                        Log::warning("Producto con ID {$productoData['producto_id']} no encontrado al confirmar orden de compra #{$ordenCompra->id}.");
                    }
                }

                // 2. Cambiar el estado de la orden de compra a 'completada'
                $ordenCompra->estado = 'completada';
                $ordenCompra->save();
                Log::info("Orden de compra #{$ordenCompra->id} marcada como 'completada'.");

                // 3. Crear un registro en la tabla de compras
                $compra = Compra::create([
                    'proveedor_id' => $ordenCompra->proveedor_id,
                    'fecha_compra' => now(),
                    'monto_total' => $totalCompra,
                    'nro_factura_proveedor' => null, // Puedes añadir un campo en el modal para esto si es necesario
                    'empleado_id' => $empleadoId, // Asignar el empleado que confirma la recepción
                    'estado' => 'recibida',
                    'observaciones' => 'Compra generada a partir de Orden de Compra #' . $ordenCompra->id,
                ]);
                Log::info("Nueva compra creada con ID: {$compra->id} para el proveedor {$ordenCompra->proveedor->nombre}. Monto Total: {$totalCompra}");

                // 4. Crear los detalles de la compra
                foreach ($productosRecibidos as $productoData) {
                    DetalleCompra::create([
                        'compra_id' => $compra->id,
                        'producto_id' => $productoData['producto_id'],
                        'cantidad' => $productoData['cantidad_recibida'],
                        'precio_compra_unitario' => $productoData['precio_compra_unitario'],
                    ]);
                    Log::info("Detalle de compra creado para producto ID: {$productoData['producto_id']}, Cantidad: {$productoData['cantidad_recibida']}, Precio Unitario: {$productoData['precio_compra_unitario']}.");
                }
            });

            Log::info('--- FIN DE CONFIRMACIÓN DE RECEPCIÓN DE ORDEN DE COMPRA. ÉXITO. ---', ['orden_compra_id' => $ordenCompra->id]);
            return response()->json(['message' => 'Recepción confirmada, stock actualizado y compra registrada exitosamente.'], 200);

        } catch (\Exception $e) {
            Log::error("--- ERROR DURANTE LA CONFIRMACIÓN DE RECEPCIÓN DE ORDEN DE COMPRA ---", [
                'orden_compra_id' => $ordenCompra->id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Fallo al confirmar la recepción y actualizar el stock.'], 500);
        }
    }
}
