@extends('layouts.material')

@section('title', 'Listado de Clientes')

@section('css')
    <!-- Force-load Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
@endsection

@section('content')
    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">group</i>Listado de Clientes</h6>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @can('anadir clientes')
                        <a href="{{ route('clientes.create') }}" class="btn btn-primary" data-toggle="tooltip" title="Agregar un nuevo cliente">
                            <i class="material-icons opacity-10 me-2">person_add</i> Nuevo Cliente
                        </a>
                        @endcan
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Filtros --}}
                <form action="{{ route('clientes.index') }}" method="GET" class="row g-3 align-items-center mt-3">
                    <div class="col-md-6">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Buscar por cédula, nombre, correo o teléfono</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary"><i class="material-icons opacity-10">search</i></button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="material-icons opacity-10">refresh</i></a>
                    </div>
                </form>
            </div>

            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cédula</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre Completo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Correo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Teléfono</th>
                            <th class="text-secondary opacity-7">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $cliente->cedula }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $cliente->correo ?? 'N/A' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $cliente->telefono ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="text-info font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Ver cliente">
                                        <i class="material-icons opacity-10">visibility</i>
                                    </a>
                                    @can('modificar clientes')
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-warning font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Editar cliente">
                                        <i class="material-icons opacity-10">edit</i>
                                    </a>
                                    @endcan
                                    @can('eliminar clientes')
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger text-gradient px-3 mb-0" onclick="return confirm('¿Estás seguro de eliminar este cliente?');" data-toggle="tooltip" data-original-title="Eliminar cliente">
                                            <i class="material-icons opacity-10">delete</i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No se encontraron clientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $clientes->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

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
        });
    </script>
@endsection
