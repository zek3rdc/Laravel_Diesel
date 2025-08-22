<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoApiController extends Controller
{
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        if (empty($term)) {
            return response()->json([]);
        }

        $productos = Producto::where('nombre', 'ILIKE', "%{$term}%")
                               ->orWhere('codigo_sku', 'ILIKE', "%{$term}%")
                               ->where('estado', true) // Solo productos activos
                               ->where('stock_actual', '>', 0) // Solo productos con stock
                               ->limit(10)
                               ->get(['id', 'nombre', 'codigo_sku', 'precio_venta', 'stock_actual', 'foto_url']);

        // Adjuntar la URL completa de la imagen a cada producto
        $productos->each(function ($producto) {
            $producto->foto_url_completa = $producto->foto_url ? Storage::disk('product_images')->url($producto->foto_url) : asset('vendor/adminlte/dist/img/default-150x150.png');
        });

        return response()->json($productos);
    }
}
