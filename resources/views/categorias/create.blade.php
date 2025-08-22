@extends('layouts.material')

@section('title', 'Crear Nueva Categoría')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-plus-circle text-primary mr-2"></i>Crear Nueva Categoría</h1>
            <p class="text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Completa el formulario para añadir una nueva categoría y sus subcategorías.</p>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-lightbulb mr-2"></i>
                <strong>Guía Rápida:</strong> Rellena el nombre de la categoría y añade tantas subcategorías como necesites.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('categorias.store') }}" method="POST" id="form-create" class="form-animation">
                @csrf

                {{-- Card 1: Datos de la Categoría --}}
                <div class="card card-primary card-outline animated-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-folder-open mr-2"></i>Datos de la Categoría</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">Nombre de la Categoría <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Motores, Filtros, Frenos..." required value="{{ old('nombre') }}">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Breve descripción de la categoría (opcional)">{{ old('descripcion') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Subcategorías --}}
                <div class="card card-success card-outline animated-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tags mr-2"></i>Subcategorías</h3>
                    </div>
                    <div class="card-body">
                        <div id="subcategorias-container">
                            @if(old('subcategorias'))
                                @foreach(old('subcategorias') as $subcategoria)
                                    @if(!empty($subcategoria))
                                    <div class="input-group mb-2 subcategoria-item">
                                        <input type="text" name="subcategorias[]" class="form-control" placeholder="Nombre de la subcategoría" value="{{ $subcategoria }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger remove-subcategoria" type="button"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-subcategoria" class="btn btn-success mt-2">
                            <i class="fas fa-plus"></i> Añadir Subcategoría
                        </button>
                        <small class="form-text text-muted">Añade una o varias subcategorías. Puedes dejarlas en blanco si no deseas añadir ninguna.</small>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="text-center mb-4 animated-card">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-2"></i>Guardar Categoría</button>
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times mr-2"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .animated-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .form-animation .animated-card:nth-child(1) { animation-delay: 0.1s; }
        .form-animation .animated-card:nth-child(2) { animation-delay: 0.2s; }
        .form-animation .animated-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .subcategoria-item {
            animation: fadeInRow 0.5s ease-in-out;
        }
        @keyframes fadeInRow {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .subcategoria-item.removing {
            animation: fadeOutRow 0.5s ease-in-out forwards;
        }
        @keyframes fadeOutRow {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.9); height: 0; margin: 0; padding: 0; border: 0; }
        }
    </style>
@stop

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lógica para subcategorías dinámicas
    const container = document.getElementById('subcategorias-container');
    const addButton = document.getElementById('add-subcategoria');
    addButton.addEventListener('click', function () {
        const newItem = document.createElement('div');
        newItem.classList.add('input-group', 'mb-2', 'subcategoria-item');
        newItem.innerHTML = `
            <input type="text" name="subcategorias[]" class="form-control" placeholder="Nombre de la subcategoría" required>
            <div class="input-group-append">
                <button class="btn btn-danger remove-subcategoria" type="button"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.appendChild(newItem);
    });
    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-subcategoria')) {
            const itemToRemove = e.target.closest('.subcategoria-item');
            itemToRemove.classList.add('removing');
            itemToRemove.addEventListener('animationend', () => itemToRemove.remove());
        }
    });

    // Lógica para el modal de confirmación
    const form = document.getElementById('form-create');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Confirmar Creación?',
            text: "¿Estás seguro de que deseas crear esta nueva categoría?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡crear!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});
</script>
@stop
