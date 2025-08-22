@extends('layouts.material')

@section('title', 'Crear Nuevo Producto')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-plus-circle text-primary mr-2"></i>Registrar Nuevo Producto</h1>
            <p class="text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Completa el formulario para añadir un nuevo producto al inventario.</p>
        </div>
    </div>
@stop

@section('content')
@can('anadir productos')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-lightbulb mr-2"></i>
                <strong>Guía Rápida:</strong> Rellena los campos requeridos (<span class="text-danger">*</span>) y asegúrate de que la información sea correcta.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-camera mr-2"></i>Imagen del Producto</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="image-preview-container mx-auto mb-3">
                            {{-- La clase 'img-fluid' se ha quitado para un mejor control con CSS --}}
                            <img id="image-preview" src="" alt="Vista previa de la imagen" class="rounded" style="display: none;">
                            <div id="image-placeholder" class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        </div>
                        <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                        <input type="file" name="foto_camera" id="foto_camera" class="d-none" accept="image/*" capture="environment">
                        <button type="button" id="btn-upload" class="btn btn-info"><i class="fas fa-upload mr-2"></i>Cargar Archivo</button>
                        <button type="button" id="btn-take-photo" class="btn btn-success"><i class="fas fa-camera-retro mr-2"></i>Tomar Foto</button>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-box-open mr-2"></i>Datos Principales</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Aceite para Motor 15W-40" required>
                        </div>
                        <div class="form-group">
                            <label for="codigo_sku">Código SKU</label>
                            <input type="text" name="codigo_sku" id="codigo_sku" class="form-control" placeholder="Ej: ACE-MOT-15W40">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Añade detalles adicionales sobre el producto..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-tags mr-2"></i>Categorización</i></h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="categoria_id">Categoría <span class="text-danger">*</span></label>
                            <select name="categoria_id" id="categoria_id" class="form-control" required>
                                <option value="">Selecciona una categoría...</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subcategoria_id">Subcategoría (Opcional)</label>
                            <select name="subcategoria_id" id="subcategoria_id" class="form-control" disabled>
                                <option value="">Selecciona una subcategoría...</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Precios y Stock</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precio_venta">Precio de Venta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                        <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.01" placeholder="0.00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_actual">Stock Inicial <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_actual" id="stock_actual" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_minimo">Stock Mínimo</label>
                                    <input type="number" name="stock_minimo" id="stock_minimo" class="form-control" placeholder="0">
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_maximo">Stock Máximo</label>
                                    <input type="number" name="stock_maximo" id="stock_maximo" class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i>Proveedor y Estado</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="proveedor_id">Proveedor <span class="text-danger">*</span></label>
                            <select name="proveedor_id" id="proveedor_id" class="form-control" required>
                                <option value="">Selecciona un proveedor...</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-2"></i>Guardar Producto</button>
                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times mr-2"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@stop

@section('css')
    <style>
        .image-preview-container {
            width: 100%;
            max-width: 250px;
            height: 250px;
            border: 3px dashed #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            position: relative; /* Añadido para posicionar correctamente la imagen */
        }
        .image-preview-container:hover {
            border-color: #007bff;
            background-color: #e9ecef;
        }
        #image-preview {
            width: 100%;
            height: 100%;
            /* CAMBIO CLAVE: 'cover' en lugar de 'contain' */
            object-fit: cover; 
            object-position: center; /* Centra la imagen antes de recortar */
        }
        #image-placeholder {
            /* Asegura que el placeholder se oculte correctamente */
            position: absolute;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            const categoriaSelect = $('#categoria_id');
            const subcategoriaSelect = $('#subcategoria_id');
            const subcategorias = @json($subcategorias);

            categoriaSelect.on('change', function() {
                const categoriaId = $(this).val();
                subcategoriaSelect.empty().append('<option value="">Selecciona una subcategoría...</option>');

                if (categoriaId) {
                    const filteredSubcategorias = subcategorias.filter(sub => sub.categoria_id == categoriaId);
                    
                    if (filteredSubcategorias.length > 0) {
                        filteredSubcategorias.forEach(sub => {
                            subcategoriaSelect.append(`<option value="${sub.id}">${sub.nombre}</option>`);
                        });
                        subcategoriaSelect.prop('disabled', false);
                    } else {
                        subcategoriaSelect.prop('disabled', true);
                    }
                } else {
                    subcategoriaSelect.prop('disabled', true);
                }
            });

            // Image preview logic
            const fotoInput = document.getElementById('foto');
            const fotoCameraInput = document.getElementById('foto_camera');
            const imagePreview = document.getElementById('image-preview');
            const imagePlaceholder = document.getElementById('image-placeholder');
            const btnUpload = document.getElementById('btn-upload');
            const btnTakePhoto = document.getElementById('btn-take-photo');

            btnUpload.addEventListener('click', () => fotoInput.click());
            btnTakePhoto.addEventListener('click', () => fotoCameraInput.click());

            const handleFileChange = (inputElement) => {
                const file = inputElement.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        // Ocultamos el placeholder de forma más segura
                        imagePlaceholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            };

            fotoInput.addEventListener('change', () => handleFileChange(fotoInput));
            fotoCameraInput.addEventListener('change', () => handleFileChange(fotoCameraInput));
        });
    </script>
@stop
