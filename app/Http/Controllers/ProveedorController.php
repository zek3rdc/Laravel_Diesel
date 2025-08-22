<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $proveedores = Proveedor::query()
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('nombre', 'like', "%{$busqueda}%")
                             ->orWhere('ruc', 'like', "%{$busqueda}%")
                             ->orWhere('correo', 'like', "%{$busqueda}%")
                             ->orWhere('persona_contacto', 'like', "%{$busqueda}%");
            })
            ->paginate(10); 

        return view('proveedores.index', compact('proveedores', 'busqueda'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'required|string|max:20|unique:proveedores',
            'correo' => 'nullable|email|max:255|unique:proveedores',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'persona_contacto' => 'nullable|string|max:255',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
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

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
                         ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
