@extends('layouts.material')

@section('title', 'Crear Nuevo Empleado')

@section('css')
    <!-- Force-load Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- TomSelect CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
@endsection

@section('content')
    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">person_add</i>Crear Nuevo Empleado</h6>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="px-4 py-3">
                <form action="{{ route('empleados.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" class="form-control @error('cedula') is-invalid @enderror" name="cedula" value="{{ old('cedula') }}" required>
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
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>
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
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}">
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
                                <input type="email" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}">
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
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}">
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
                                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion') }}">
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
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" name="cargo" value="{{ old('cargo') }}" required>
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
                                <input type="date" class="form-control @error('fecha_contratacion') is-invalid @enderror" name="fecha_contratacion" value="{{ old('fecha_contratacion') }}" required>
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
                                <input type="date" class="form-control @error('fecha_egreso') is-invalid @enderror" name="fecha_egreso" value="{{ old('fecha_egreso') }}">
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
                                <select class="form-control @error('user_id') is-invalid @enderror" name="user_id" id="user_id_select">
                                    <option value="">Seleccione un usuario existente</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
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

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="form-check form-switch d-flex align-items-center mb-3">
                                <input class="form-check-input" type="checkbox" id="createUserSwitch" name="create_user" value="1" {{ old('create_user') ? 'checked' : '' }}>
                                <label class="form-check-label mb-0 ms-3" for="createUserSwitch">Crear un nuevo usuario para este empleado</label>
                            </div>
                        </div>
                    </div>

                    <div id="newUserFields" style="display: {{ old('create_user') ? 'block' : 'none' }};">
                        <h6 class="text-primary text-capitalize ps-3 mt-4">Datos del Nuevo Usuario</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name') }}">
                                    @error('user_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Email de Usuario</label>
                                    <input type="email" class="form-control @error('user_email') is-invalid @enderror" name="user_email" value="{{ old('user_email') }}">
                                    @error('user_email')
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
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" class="form-control @error('user_password') is-invalid @enderror" name="user_password">
                                    @error('user_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" name="user_password_confirmation">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-outline my-3">
                                    <label>Roles del Usuario</label>
                                    <select class="form-control @error('user_roles') is-invalid @enderror" name="user_roles[]" id="user_roles_select" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" {{ in_array($role->name, old('user_roles', [])) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_roles')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Guardar Empleado</button>
                        <a href="{{ route('empleados.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
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
                    if (inputField.value !== '') {
                       input.classList.add('is-focused');
                    }
                }
            });

            // Lógica para mostrar/ocultar campos de nuevo usuario
            const createUserSwitch = document.getElementById('createUserSwitch');
            const newUserFields = document.getElementById('newUserFields');
            const userIdSelect = document.getElementById('user_id_select');
            let tomSelectInstance = null; // Variable para almacenar la instancia de TomSelect

            function initializeTomSelect() {
                if (!tomSelectInstance) {
                    tomSelectInstance = new TomSelect("#user_roles_select", {
                        plugins: ['remove_button'],
                        create: false, // No permitir crear nuevos roles
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });
                }
            }

            function destroyTomSelect() {
                if (tomSelectInstance) {
                    tomSelectInstance.destroy();
                    tomSelectInstance = null;
                }
            }

            function toggleNewUserFields() {
                if (createUserSwitch.checked) {
                    newUserFields.style.display = 'block';
                    userIdSelect.value = ''; // Deseleccionar usuario existente si se va a crear uno nuevo
                    userIdSelect.disabled = true; // Deshabilitar el select de usuario existente
                    // Asegurar que los campos de nuevo usuario tengan la clase 'is-focused' si tienen valor
                    newUserFields.querySelectorAll('input, select').forEach(function(inputField) {
                        if (inputField.value !== '') {
                            inputField.closest('.input-group-outline').classList.add('is-focused');
                        }
                    });
                    initializeTomSelect(); // Inicializar TomSelect cuando los campos son visibles
                } else {
                    newUserFields.style.display = 'none';
                    userIdSelect.disabled = false; // Habilitar el select de usuario existente
                    // Limpiar los campos del nuevo usuario al ocultarlos
                    newUserFields.querySelectorAll('input').forEach(input => input.value = '');
                    newUserFields.querySelectorAll('select').forEach(select => select.selectedIndex = -1);
                    newUserFields.querySelectorAll('.input-group-outline').forEach(el => el.classList.remove('is-focused'));
                    destroyTomSelect(); // Destruir TomSelect cuando los campos se ocultan
                }
            }

            createUserSwitch.addEventListener('change', toggleNewUserFields);

            // Inicializar el estado de los campos al cargar la página
            // Si hay errores de validación para los campos de nuevo usuario, asegurar que se muestren
            <?php $hasUserErrors = $errors->has('user_name') || $errors->has('user_email') || $errors->has('user_password') || $errors->has('user_roles'); ?>
            const hasUserErrors = {{ json_encode($hasUserErrors) }};
            if (hasUserErrors) {
                createUserSwitch.checked = true;
            }
            toggleNewUserFields(); // Llamar después de verificar hasUserErrors para asegurar la inicialización correcta
        });
    </script>
@endsection
