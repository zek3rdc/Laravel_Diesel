@extends('layouts.material')

@section('title', 'Detalle de Categoría')

@section('content_header')
    <h1><i class="fas fa-info-circle"></i> Detalle de Categoría</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-header bg-primary">
            <h3 class="card-title mb-0">{{ $categoria->nombre }}</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <strong><i class="fas fa-file-alt mr-1"></i> Descripción:</strong>
                <p>{{ $categoria->descripcion ?? 'No se proporcionó una descripción.' }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-tags mr-1"></i> Subcategorías:</strong>
                @if($categoria->subcategorias->isNotEmpty())
                    <ul class="list-group mt-2">
                        @foreach ($categoria->subcategorias as $subcategoria)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $subcategoria->nombre }}
                                <span class="badge badge-primary badge-pill"><i class="fas fa-tag"></i></span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2">No hay subcategorías asociadas a esta categoría.</p>
                @endif
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
@stop
