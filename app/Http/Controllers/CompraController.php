<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::with('proveedor')->latest()->paginate(10);
        return view('compras.index', compact('compras'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'detalleCompras.producto']);
        return view('compras.show', compact('compra'));
    }
}
