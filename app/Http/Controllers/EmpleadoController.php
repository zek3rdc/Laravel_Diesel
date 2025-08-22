<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // Importar el modelo Role
use Illuminate\Support\Facades\Hash; // Importar Hash

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Empleado::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('cedula', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%");
        }

        $empleados = $query->paginate(10);

        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $roles = Role::all(); // Obtener todos los roles
        return view('empleados.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'cedula' => 'required|string|max:255|unique:empleados,cedula',
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255|unique:empleados,correo',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'cargo' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_egreso' => 'nullable|date|after_or_equal:fecha_contratacion', // Nuevo campo
            'user_id' => 'nullable|exists:users,id',
        ];

        // Validaciones condicionales para la creación de usuario
        if ($request->has('create_user') && $request->create_user) {
            $rules['user_name'] = 'required|string|max:255';
            $rules['user_email'] = 'required|string|email|max:255|unique:users,email';
            $rules['user_password'] = 'required|string|min:8|confirmed';
            $rules['user_roles'] = 'nullable|array';
            $rules['user_roles.*'] = 'exists:roles,name';
        }

        $validatedData = $request->validate($rules);

        $empleadoData = $request->only([
            'cedula', 'nombre', 'apellido', 'correo', 'telefono', 'direccion',
            'cargo', 'fecha_contratacion', 'fecha_egreso', 'user_id'
        ]);

        // Lógica para crear un nuevo usuario si se seleccionó la opción
        if ($request->has('create_user') && $request->create_user) {
            $newUser = User::create([
                'name' => $validatedData['user_name'],
                'email' => $validatedData['user_email'],
                'password' => Hash::make($validatedData['user_password']),
            ]);

            if (!empty($validatedData['user_roles'])) {
                $newUser->syncRoles($validatedData['user_roles']);
            }

            $empleadoData['user_id'] = $newUser->id;
        }

        Empleado::create($empleadoData);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        $users = User::all();
        $roles = Role::all(); // Obtener todos los roles
        return view('empleados.edit', compact('empleado', 'users', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'cedula' => 'required|string|max:255|unique:empleados,cedula,' . $empleado->id,
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255|unique:empleados,correo,' . $empleado->id,
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'cargo' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_egreso' => 'nullable|date|after_or_equal:fecha_contratacion', // Nuevo campo
            'user_id' => 'nullable|exists:users,id',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        // Si el empleado tiene un usuario asociado, se podría considerar eliminarlo o deshabilitarlo.
        // Por ahora, solo eliminamos el empleado.
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
