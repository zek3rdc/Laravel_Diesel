<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return response()->json($proveedores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:20|unique:proveedores',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'persona_contacto' => 'nullable|string|max:255',
        ]);

        $proveedor = Proveedor::create($request->all());

        return response()->json($proveedor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return response()->json($proveedor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:20|unique:proveedores,ruc,' . $proveedor->id,
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'persona_contacto' => 'nullable|string|max:255',
        ]);

        $proveedor->update($request->all());

        return response()->json($proveedor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return response()->json(null, 204);
    }
}
