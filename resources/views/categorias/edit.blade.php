@extends('layouts.material')

@section('title', 'Editar Categoría')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-edit text-warning mr-2"></i>Editar Categoría</h1>
            <p class="text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Modifica los datos de la categoría y gestiona sus subcategorías.</p>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" id="form-edit" class="form-animation">
                @csrf
                @method('PUT')

                {{-- Card 1: Datos de la Categoría --}}
                <div class="card card-warning card-outline animated-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-folder-open mr-2"></i>Datos de la Categoría</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">Nombre de la Categoría <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ old('nombre', $categoria->nombre) }}">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Subcategorías --}}
                <div class="card card-info card-outline animated-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tags mr-2"></i>Gestionar Subcategorías</h3>
                    </div>
                    <div class="card-body">
                        <div id="subcategorias-container">
                            @foreach ($categoria->subcategorias as $subcategoria)
                            <div class="input-group mb-2 subcategoria-item">
                                <input type="hidden" name="subcategorias[{{ $subcategoria->id }}][id]" value="{{ $subcategoria->id }}">
                                <input type="text" name="subcategorias[{{ $subcategoria->id }}][nombre]" class="form-control" value="{{ $subcategoria->nombre }}">
                                <div class="input-group-append">
                                    <button class="btn btn-danger remove-subcategoria" type="button" style="display: flex; align-items: center; justify-content: center;">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-subcategoria" class="btn btn-success mt-2">
                            <i class="fas fa-plus"></i> Añadir Nueva Subcategoría
                        </button>
                        <div id="subcategorias-eliminadas" class="d-none"></div>
                        <small class="form-text text-muted">Puedes editar, eliminar o añadir nuevas subcategorías.</small>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="text-center mb-4 animated-card">
                    <button type="submit" class="btn btn-warning btn-lg"><i class="fas fa-sync-alt mr-2"></i>Actualizar Categoría</button>
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
            to { opacity: 1; transform: translateY(0); }
        }

        .subcategoria-item { animation: fadeInRow 0.5s ease-in-out; }
        @keyframes fadeInRow {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .subcategoria-item.removing { animation: fadeOutRow 0.5s ease-in-out forwards; }
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
    const container = document.getElementById('subcategorias-container');
    const addButton = document.getElementById('add-subcategoria');
    const eliminadasContainer = document.getElementById('subcategorias-eliminadas');
    let newIndex = 0;

    addButton.addEventListener('click', function () {
        const newItem = document.createElement('div');
        newItem.classList.add('input-group', 'mb-2', 'subcategoria-item');
        const uniqueId = `new_${newIndex++}`;
        newItem.innerHTML = `
            <input type="text" name="subcategorias[${uniqueId}][nombre]" class="form-control" placeholder="Nombre de la nueva subcategoría" required>
            <div class="input-group-append">
                <button class="btn btn-danger remove-subcategoria" type="button" style="display: flex; align-items: center; justify-content: center;">
                    <i class="material-icons">delete</i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
    });

    container.addEventListener('click', function (e) {
        const removeButton = e.target.closest('.remove-subcategoria');
        if (removeButton) {
            const itemToRemove = removeButton.closest('.subcategoria-item');
            const subcategoriaIdInput = itemToRemove.querySelector('input[type="hidden"]');
            if (subcategoriaIdInput) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'subcategorias_eliminadas[]';
                hiddenInput.value = subcategoriaIdInput.value;
                eliminadasContainer.appendChild(hiddenInput);
            }
            itemToRemove.classList.add('removing');
            itemToRemove.addEventListener('animationend', () => itemToRemove.remove());
        }
    });

    const form = document.getElementById('form-edit');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Confirmar Cambios?',
            text: "¿Estás seguro de que deseas guardar los cambios?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡guardar!',
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
