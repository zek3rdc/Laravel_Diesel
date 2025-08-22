@extends('layouts.material')

@section('title', 'Proveedores')

@section('content_header')
    <h1>Lista de Proveedores</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('anadir proveedores')
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Nuevo Proveedor
            </a>
            @endcan
            <form action="{{ route('proveedores.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar proveedor..." value="{{ $busqueda ?? '' }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($proveedores->isEmpty())
                <div class="alert alert-info text-center">
                    No hay proveedores registrados.
                </div>
            @else
                <div class="row">
                    @foreach ($proveedores as $proveedor)
                        <div class="col-md-4 mb-4">
                            <div class="card new-card-v2 page-index-card action-card" 
                                 data-show-url="{{ route('proveedores.show', $proveedor->id) }}"
                                 data-edit-url="{{ route('proveedores.get_edit_form', $proveedor->id) }}"
                                 data-delete-url="{{ route('proveedores.destroy', $proveedor->id) }}">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $proveedor->nombre }}</h5>
                                    <p class="card-category"><i class="material-icons">local_shipping</i> Proveedor</p>
                                </div>
                                <div class="card-body">
                                    <p class="card-description">
                                        <strong>RUC:</strong> {{ $proveedor->ruc }}<br>
                                        <strong>Email:</strong> {{ $proveedor->correo }}<br>
                                        <strong>Teléfono:</strong> {{ $proveedor->telefono }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons">contact_phone</i> {{ $proveedor->persona_contacto }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $proveedores->links() }}
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const actionCards = document.querySelectorAll('.action-card');
            const actionModal = new bootstrap.Modal(document.getElementById('actionModal'));
            const actionModalBody = document.getElementById('actionModalBody');

            actionCards.forEach(card => {
                card.addEventListener('dblclick', function () {
                    const showUrl = this.dataset.showUrl;
                    const editUrl = this.dataset.editUrl;
                    const deleteUrl = this.dataset.deleteUrl;

                    actionModalBody.innerHTML = `
                        <a href="${showUrl}" class="btn btn-info w-100 mb-2">Ver Detalles</a>
                        @can('modificar proveedores')
                        <a href="${editUrl}" class="btn btn-warning w-100 mb-2">Editar</a>
                        @endcan
                        @can('eliminar proveedores')
                        <form action="${deleteUrl}" method="POST" class="form-delete d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Eliminar</button>
                        </form>
                        @endcan
                    `;
                    
                    const deleteForm = actionModalBody.querySelector('.form-delete');
                    deleteForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "¡No podrás revertir esto!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, ¡elimínalo!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }
                        });
                    });

                    actionModal.show();
                });
            });
        });
    </script>
@stop
