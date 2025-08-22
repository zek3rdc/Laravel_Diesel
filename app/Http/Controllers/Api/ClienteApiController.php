<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;

class ClienteApiController extends Controller
{
    /**
     * Busca un cliente por su cédula o RUC.
     *
     * @param  string  $cedula
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar($cedula)
    {
        // Asumiendo que la cédula/RUC se guarda en el campo 'cedula' del modelo Cliente
        $cliente = Cliente::where('cedula', $cedula)->first();

        if ($cliente) {
            return response()->json(['cliente' => $cliente]);
        }

        return response()->json(['cliente' => null]);
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:clientes,cedula',
            'correo' => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cliente = Cliente::create($request->all());

        return response()->json(['cliente' => $cliente], 201);
    }
}
