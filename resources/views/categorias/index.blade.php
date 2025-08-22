@extends('layouts.material')

@section('title', 'Categorías')

@section('content_header')
    <h1>Lista de Categorías</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('anadir categorias')
            <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Nueva Categoría
            </a>
            @endcan
            <form action="{{ route('categorias.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar categoría..." value="{{ $busqueda ?? '' }}">
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

            @if ($categorias->isEmpty())
                <div class="alert alert-info text-center">
                    No hay categorías registradas.
                </div>
            @else
                <div class="row">
                    @foreach ($categorias as $categoria)
                        <div class="col-md-4 mb-4">
                            <div class="card new-card-v2 page-index-card action-card"
                                 data-show-url="{{ route('categorias.show', $categoria->id) }}"
                                 data-edit-url="{{ route('categorias.edit', $categoria->id) }}"
                                 data-delete-url="{{ route('categorias.destroy', $categoria->id) }}">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $categoria->nombre }}</h5>
                                    <p class="card-category"><i class="material-icons">category</i> Categoría</p>
                                </div>
                                <div class="card-body">
                                    <p class="card-description">
                                        {{ $categoria->descripcion ?? 'Sin descripción.' }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="stats">
                                        <i class="material-icons">tag</i>
                                        {{ $categoria->subcategorias->count() }} Subcategorías
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $categorias->links() }}
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
                        @can('modificar categorias')
                        <a href="${editUrl}" class="btn btn-warning w-100 mb-2">Editar</a>
                        @endcan
                        @can('eliminar categorias')
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
