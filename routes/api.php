<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CurrencyRateController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\UserController; // Importa el controlador
use App\Http\Controllers\Api\ProveedorApiController;
use App\Http\Controllers\Api\DashboardApiController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clientes/buscar/{cedula}', [ClienteApiController::class, 'buscar']);
Route::post('/clientes', [ClienteApiController::class, 'store']);

Route::get('/productos/buscar', [ProductoApiController::class, 'buscar']);

Route::get('/currency-rates', [CurrencyRateController::class, 'getRates']);

Route::post('/ventas', [VentaController::class, 'store']);

