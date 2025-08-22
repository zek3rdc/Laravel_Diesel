<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('can:ver dashboard');

    // Rutas para Clientes
    Route::resource('clientes', ClienteController::class)->except(['edit'])->middleware([
        'can:ver clientes'
    ]);
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('can:modificar clientes');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store')->middleware('can:anadir clientes');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('can:modificar clientes');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('can:eliminar clientes');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create')->middleware('can:anadir clientes');


    // Rutas para Empleados
    Route::resource('empleados', EmpleadoController::class)->except(['edit'])->middleware([
        'can:ver empleados'
    ]);
    Route::get('/empleados/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit')->middleware('can:modificar empleados');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store')->middleware('can:anadir empleados');
    Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update')->middleware('can:modificar empleados');
    Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy')->middleware('can:eliminar empleados');
    Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create')->middleware('can:anadir empleados');


    // Rutas para Inventario (Productos)
    Route::get('/inventario/reponer-stock', [InventarioController::class, 'showReponerStock'])->name('inventario.reponerStock')->middleware('can:ver productos');
    Route::post('/inventario/enviar-notificaciones', [InventarioController::class, 'enviarNotificaciones'])->name('inventario.enviarNotificaciones')->middleware('can:anadir productos');
    Route::get('/inventario/ordenes-pendientes', [InventarioController::class, 'showOrdenesPendientes'])->name('inventario.ordenesPendientes')->middleware('can:ver productos');
    Route::post('/inventario/reenviar-notificacion/{ordenCompra}', [InventarioController::class, 'reenviarNotificacion'])->name('inventario.reenviarNotificacion')->middleware('can:anadir productos');
    Route::get('/inventario/ordenes-pendientes/{ordenCompra}/detalles', [InventarioController::class, 'getOrdenDetalles'])->name('inventario.getOrdenDetalles')->middleware('can:ver productos');
    Route::post('/inventario/confirmar-recepcion/{ordenCompra}', [InventarioController::class, 'confirmarRecepcion'])->name('inventario.confirmarRecepcion')->middleware('can:anadir productos');
    Route::resource('inventario', InventarioController::class)->parameters([
        'inventario' => 'producto'
    ])->except(['edit'])->middleware([
        'can:ver productos'
    ]);
    Route::get('/inventario/{producto}/editar', [InventarioController::class, 'edit'])->name('inventario.custom_edit')->middleware('can:modificar productos');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store')->middleware('can:anadir productos');
    Route::put('/inventario/{producto}', [InventarioController::class, 'update'])->name('inventario.update')->middleware('can:modificar productos');
    Route::delete('/inventario/{producto}', [InventarioController::class, 'destroy'])->name('inventario.destroy')->middleware('can:eliminar productos');
    Route::get('/inventario/create', [InventarioController::class, 'create'])->name('inventario.create')->middleware('can:anadir productos');


    // Rutas para Categorías
    Route::resource('categorias', CategoriaController::class)->except(['edit'])->middleware([
        'can:ver categorias'
    ]);
    Route::get('/categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('can:modificar categorias');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('can:anadir categorias');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('can:modificar categorias');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('can:eliminar categorias');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('can:anadir categorias');


    // Rutas para Proveedores
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor'
    ])->except(['edit'])->middleware([
        'can:ver proveedores'
    ]);
    // Se crea una ruta personalizada para evitar conflictos
    Route::get('/proveedores/editar/{proveedor}', [ProveedorController::class, 'edit'])->name('proveedores.get_edit_form')->middleware('can:modificar proveedores');
    Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store')->middleware('can:anadir proveedores');
    Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update')->middleware('can:modificar proveedores');
    Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy')->middleware('can:eliminar proveedores');
    Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create')->middleware('can:anadir proveedores');


    // Rutas para historial de ventas y compras
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index')->middleware('can:ver historial ventas');
    Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show')->middleware('can:ver historial ventas');
    Route::get('/compras', [CompraController::class, 'index'])->name('compras.index')->middleware('can:ver historial compras');
    Route::get('/compras/{compra}', [CompraController::class, 'show'])->name('compras.show')->middleware('can:ver historial compras');


// Esta ruta servirá tu aplicación de React.
// Debe ser la última ruta de este archivo.
// Route::get('/{any}', function () {
//     // Laravel buscará un archivo 'index.html' en la carpeta /public
//     // y lo servirá.
//     return file_get_contents(public_path('index.html'));
// })->where('any', '.*');
 
});
