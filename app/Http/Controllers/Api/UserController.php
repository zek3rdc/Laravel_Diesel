<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Muestra una lista de los recursos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtenemos todos los usuarios y los devolvemos como JSON
        $users = User::all();
        return response()->json($users);
    }
}