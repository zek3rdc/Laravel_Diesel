@extends('layouts.material')

@section('title', 'Crear Proveedor')

@section('content_header')
    <h1>Crear Nuevo Proveedor</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('proveedores.store') }}" method="POST" id="create-proveedor-form">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ruc">RUC:</label>
                    <input type="text" name="ruc" id="ruc" class="form-control @error('ruc') is-invalid @enderror" value="{{ old('ruc') }}" required>
                    @error('ruc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}">
                    @error('correo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                    @error('telefono')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <textarea name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="persona_contacto">Persona de Contacto:</label>
                    <input type="text" name="persona_contacto" id="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto') }}">
                    @error('persona_contacto')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('create-proveedor-form').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Vas a crear un nuevo proveedor.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡créalo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@stop
