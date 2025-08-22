<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Empleado; // Asegúrate de importar el modelo Empleado
use App\Mail\VentaExitosa;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Storage;

class VentaController extends Controller
{
    /**
     * Almacena una nueva venta en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'nro_factura' => 'required|string|max:255|unique:ventas,nro_factura',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
            'pago.moneda' => 'required|string|in:EUR,USD,VES',
            'pago.metodo' => 'required|string|max:255',
            'pago.referencia' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $venta = DB::transaction(function () use ($request) {
                $carrito = $request->input('items');
                $subTotal = 0;

                // 1. Validar stock antes de hacer cualquier cosa
                foreach ($carrito as $item) {
                    $producto = Producto::find($item['id']);
                    if ($producto->stock_actual < $item['cantidad']) {
                        // Usamos una excepción para detener la transacción
                        throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
                    }
                    // Calcular subtotal basado en el precio en la BD (más seguro)
                    $subTotal += $producto->precio_venta * $item['cantidad'];
                }

                // 2. Crear la venta
                $empleadoId = null;
                if (Auth::check()) {
                    $user = Auth::user();
                    // Asume que cada usuario autenticado tiene un registro de empleado asociado
                    $empleado = Empleado::where('user_id', $user->id)->first();
                    if ($empleado) {
                        $empleadoId = $empleado->id;
                    }
                }

                $venta = Venta::create([
                    'nro_factura' => $request->input('nro_factura'),
                    'cliente_id' => $request->input('cliente_id'),
                    'empleado_id' => $empleadoId, // Asigna el ID del empleado
                    'fecha_venta' => now(),
                    'sub_total' => $subTotal,
                    'impuesto' => 0, // El impuesto no se calcula ni se aplica
                    // 'total' se calcula automáticamente por la base de datos
                    'metodo_pago' => $request->input('pago.metodo'),
                    'moneda_pago' => $request->input('pago.moneda'),
                    'referencia_pago' => $request->input('pago.referencia'),
                    'estado' => 'Completada', // O el estado que corresponda
                ]);

                // 3. Crear los detalles de la venta y actualizar stock
                foreach ($carrito as $item) {
                    $producto = Producto::find($item['id']);

                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_venta_unitario' => $producto->precio_venta, // Precio en Euros desde la BD
                    ]);

                    // Actualizar stock del producto
                    $producto->stock_actual -= $item['cantidad'];
                    $producto->save();
                }

                return $venta;
            });

            // Después de la transacción, cargar las relaciones necesarias
            $venta->load('cliente', 'detalles.producto');

            // Generar y guardar el PDF de la factura
            $pdfPath = null;
            try {
                $html = view('pdf.facturaventa', compact('venta'))->render();
                
                // Generar el contenido del PDF en memoria
                $pdfContent = Browsershot::html($html)->pdf();

                // Definir la ruta relativa para el almacenamiento
                $relativePath = 'facturas/factura-' . $venta->id . '.pdf';

                // Guardar el PDF usando el Storage de Laravel, que maneja la creación de directorios
                Storage::disk('local')->put($relativePath, $pdfContent);

                // Obtener la ruta absoluta para el Mailable
                $pdfPath = Storage::disk('local')->path($relativePath);

                Log::info('PDF de la factura ' . $venta->id . ' generado exitosamente en: ' . $pdfPath);
            } catch (\Exception $e) {
                Log::error('Error al generar el PDF de la factura: ' . $e->getMessage());
                $pdfPath = null; // Asegurarse de que no se intente adjuntar un PDF que no existe
            }

            // Enviar correo de confirmación con el PDF adjunto
            Log::info('Iniciando proceso de envío de correo para la venta: ' . $venta->id);
            try {
                if ($venta->cliente && $venta->cliente->correo) {
                    Log::info('Intentando enviar correo a: ' . $venta->cliente->correo);
                    Mail::to($venta->cliente->correo)->send(new VentaExitosa($venta, $pdfPath));
                    Log::info('Correo para la venta ' . $venta->id . ' enviado exitosamente a ' . $venta->cliente->correo);
                } else {
                    Log::warning('No se envió correo para la venta ' . $venta->id . '. Cliente no tiene correo registrado.');
                }
            } catch (\Exception $e) {
                // Si el correo falla, no detener la operación, solo registrar el error
                Log::error("Fallo al enviar correo de confirmación de venta: " . $e->getMessage());
            } finally {
                // Opcional: Eliminar el PDF después de enviarlo para no ocupar espacio
                if ($pdfPath && Storage::disk('local')->exists('facturas/factura-' . $venta->id . '.pdf')) {
                    Storage::disk('local')->delete('facturas/factura-' . $venta->id . '.pdf');
                }
            }

            // Calcular los nuevos KPIs
            $kpis = $this->getKpis();

            return response()->json([
                'message' => 'Venta registrada exitosamente.',
                'venta_id' => $venta->id,
                'nro_factura' => $venta->nro_factura,
                'kpis' => $kpis
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calcula y devuelve los KPIs del inventario.
     *
     * @return array
     */
    private function getKpis()
    {
        // Usamos el Query Builder para eficiencia, no la colección.
        $valorTotal = DB::table('productos')->sum(DB::raw('precio_venta * stock_actual'));
        
        return [
            'valorTotalInventario' => $valorTotal,
            'productosActivos' => Producto::where('estado', true)->count(),
            'productosBajoStock' => Producto::where('estado', true)->whereColumn('stock_actual', '<=', 'stock_minimo')->count(),
            'productosInactivos' => Producto::where('estado', false)->count(),
        ];
    }
}
