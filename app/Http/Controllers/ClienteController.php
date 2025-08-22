<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('cedula', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
        }

        $clientes = $query->paginate(10); // Paginar los resultados

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cedula' => 'required|string|max:255|unique:clientes,cedula',
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255|unique:clientes,correo',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'cedula' => 'required|string|max:255|unique:clientes,cedula,' . $cliente->id,
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255|unique:clientes,correo,' . $cliente->id,
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
