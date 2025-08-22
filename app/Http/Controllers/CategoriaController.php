<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Mostrar lista de categorías
     */
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');

        $categorias = Categoria::with('subcategorias')
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('nombre', 'like', "%{$busqueda}%")
                             ->orWhere('descripcion', 'like', "%{$busqueda}%");
            })
            ->orderBy('nombre', 'asc')
            ->paginate(10);

        return view('categorias.index', compact('categorias', 'busqueda'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'subcategorias' => 'nullable|array',
            'subcategorias.*' => 'nullable|string|max:255'
        ]);

        $categoria = Categoria::create($request->only('nombre', 'descripcion'));

        if ($request->has('subcategorias')) {
            foreach ($request->subcategorias as $subcategoriaNombre) {
                if (!empty($subcategoriaNombre)) {
                    Subcategoria::create([
                        'nombre' => $subcategoriaNombre,
                        'categoria_id' => $categoria->id
                    ]);
                }
            }
        }

        return redirect()->route('categorias.index')
                         ->with('success', 'Categoría y subcategorías creadas exitosamente.');
    }

    /**
     * Mostrar detalle de una categoría
     */
    public function show(Categoria $categoria)
    {
        $categoria->load('subcategorias');
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Categoria $categoria)
    {
        $categoria->load('subcategorias');
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualizar categoría
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
            'subcategorias' => 'nullable|array',
            'subcategorias.*.id' => 'nullable|integer|exists:subcategorias,id',
            'subcategorias.*.nombre' => 'required|string|max:255',
            'subcategorias_eliminadas' => 'nullable|array',
            'subcategorias_eliminadas.*' => 'integer|exists:subcategorias,id',
        ]);

        $categoria->update($request->only('nombre', 'descripcion'));

        // Procesar subcategorías
        if ($request->has('subcategorias')) {
            foreach ($request->subcategorias as $key => $subData) {
                if (isset($subData['id'])) {
                    // Actualizar subcategoría existente
                    $subcategoria = Subcategoria::find($subData['id']);
                    if ($subcategoria) {
                        $subcategoria->update(['nombre' => $subData['nombre']]);
                    }
                } else {
                    // Crear nueva subcategoría
                    $categoria->subcategorias()->create(['nombre' => $subData['nombre']]);
                }
            }
        }

        // Eliminar subcategorías marcadas
        if ($request->has('subcategorias_eliminadas')) {
            Subcategoria::destroy($request->subcategorias_eliminadas);
        }

        return redirect()->route('categorias.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Eliminar categoría
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')
                         ->with('success', 'Categoría eliminada exitosamente.');
    }
}
