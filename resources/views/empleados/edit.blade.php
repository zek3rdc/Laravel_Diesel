@extends('layouts.material')

@section('title', 'Editar Empleado')

@section('css')
    <!-- Force-load Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
@endsection

@section('content')
    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">edit</i>Editar Empleado</h6>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="px-4 py-3">
                <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula', $empleado->cedula) }}" required>
                                @error('cedula')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                                @error('nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido', $empleado->apellido) }}">
                                @error('apellido')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo', $empleado->correo) }}">
                                @error('correo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono', $empleado->telefono) }}">
                                @error('telefono')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion', $empleado->direccion) }}">
                                @error('direccion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cargo</label>
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" name="cargo" value="{{ old('cargo', $empleado->cargo) }}" required>
                                @error('cargo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Fecha de Contratación</label>
                                <input type="date" class="form-control @error('fecha_contratacion') is-invalid @enderror" name="fecha_contratacion" value="{{ old('fecha_contratacion', $empleado->fecha_contratacion ? $empleado->fecha_contratacion->format('Y-m-d') : '') }}" required>
                                @error('fecha_contratacion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Fecha de Egreso (Opcional)</label>
                                <input type="date" class="form-control @error('fecha_egreso') is-invalid @enderror" name="fecha_egreso" value="{{ old('fecha_egreso', $empleado->fecha_egreso ? $empleado->fecha_egreso->format('Y-m-d') : '') }}">
                                @error('fecha_egreso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Usuario Asociado (Opcional)</label>
                                <select class="form-control @error('user_id') is-invalid @enderror" name="user_id">
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $empleado->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
                        <a href="{{ route('empleados.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize floating labels
            var inputs = document.querySelectorAll('.input-group.input-group-outline');
            inputs.forEach(function(input) {
                var inputField = input.querySelector('input, select');
                if (inputField) {
                    inputField.addEventListener('focus', function() {
                        input.classList.add('is-focused');
                    });
                    inputField.addEventListener('blur', function() {
                        if (inputField.value === '') {
                            input.classList.remove('is-focused');
                        }
                    });
                    // Ensure labels are lifted if there's an old value or existing value
                    if (inputField.value !== '') {
                       input.classList.add('is-focused');
                    }
                }
            });
        });
    </script>
@endsection
