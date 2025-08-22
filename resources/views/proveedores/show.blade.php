@extends('layouts.material')

@section('title', 'Detalle de Proveedor')

@section('content_header')
    <h1><i class="fas fa-info-circle"></i> Detalle de Proveedor</h1>
@stop

@section('content')
    <div class="card shadow">
        <div class="card-header bg-primary">
            <h3 class="card-title mb-0">{{ $proveedor->nombre }}</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <strong><i class="fas fa-building mr-1"></i> RUC:</strong>
                <p>{{ $proveedor->ruc }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-envelope mr-1"></i> Correo:</strong>
                <p>{{ $proveedor->correo }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-phone mr-1"></i> Teléfono:</strong>
                <p>{{ $proveedor->telefono }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Dirección:</strong>
                <p>{{ $proveedor->direccion }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-user mr-1"></i> Persona de Contacto:</strong>
                <p>{{ $proveedor->persona_contacto }}</p>
            </div>
            <hr>
            <div class="form-group">
                <strong><i class="fas fa-box-open mr-1"></i> Productos Suministrados:</strong>
                @if($proveedor->productos->isNotEmpty())
                    <ul class="list-group mt-2">
                        @foreach ($proveedor->productos as $producto)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $producto->nombre }}
                                <span class="badge badge-info badge-pill"><i class="fas fa-cubes"></i></span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2">No hay productos asociados a este proveedor.</p>
                @endif
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
            <a href="{{ route('proveedores.get_edit_form', ['proveedor' => $proveedor->id]) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
