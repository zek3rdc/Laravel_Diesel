<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'empleado'])->latest();

        // Búsqueda por número de factura
        if ($request->filled('nro_factura')) {
            $query->where('nro_factura', 'like', '%' . $request->nro_factura . '%');
        }

        // Búsqueda por rango de fechas
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_fin);
        }

        $ventas = $query->paginate(10)->withQueryString();

        return view('ventas.index', [
            'ventas' => $ventas,
            'nro_factura' => $request->nro_factura,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'empleado', 'detalleVentas.producto']);
        return view('ventas.show', compact('venta'));
    }
}
