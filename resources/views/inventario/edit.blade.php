@extends('layouts.material')

@section('title', 'Editar Producto')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-edit text-primary mr-2"></i>Editar Producto: {{ $producto->nombre }}</h1>
            <p class="text-muted mb-1"><i class="fas fa-info-circle mr-1"></i>Modifica la información del producto existente.</p>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">

            <form action="{{ route('inventario.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-camera mr-2"></i>Imagen del Producto</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="image-preview-container mx-auto mb-3">
                            <img id="image-preview" src="{{ $producto->foto_url ? asset('storage/product_images/' . $producto->foto_url) : '' }}" alt="Vista previa de la imagen" class="rounded" style="{{ $producto->foto_url ? 'display: block;' : 'display: none;' }}">
                            <div id="image-placeholder" class="d-flex align-items-center justify-content-center h-100" style="{{ $producto->foto_url ? 'display: none;' : '' }}">
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
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $producto->nombre) }}" placeholder="Ej: Aceite para Motor 15W-40" required>
                            @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="codigo_sku">Código SKU</label>
                            <input type="text" name="codigo_sku" id="codigo_sku" class="form-control @error('codigo_sku') is-invalid @enderror" value="{{ old('codigo_sku', $producto->codigo_sku) }}" placeholder="Ej: ACE-MOT-15W40">
                            @error('codigo_sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" placeholder="Añade detalles adicionales sobre el producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                            <select name="categoria_id" id="categoria_id" class="form-control @error('categoria_id') is-invalid @enderror" required>
                                <option value="">Selecciona una categoría...</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subcategoria_id">Subcategoría (Opcional)</label>
                            <select name="subcategoria_id" id="subcategoria_id" class="form-control @error('subcategoria_id') is-invalid @enderror" disabled>
                                <option value="">Selecciona una subcategoría...</option>
                                {{-- Las opciones se cargarán dinámicamente con JS --}}
                            </select>
                            @error('subcategoria_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                                        <input type="number" name="precio_venta" id="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" value="{{ old('precio_venta', $producto->precio_venta) }}" step="0.01" placeholder="0.00" required>
                                    </div>
                                    @error('precio_venta')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_actual">Stock Inicial <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_actual" id="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', $producto->stock_actual) }}" placeholder="0" required>
                                    @error('stock_actual')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_minimo">Stock Mínimo</label>
                                    <input type="number" name="stock_minimo" id="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" value="{{ old('stock_minimo', $producto->stock_minimo) }}" placeholder="0">
                                    @error('stock_minimo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock_maximo">Stock Máximo</label>
                                    <input type="number" name="stock_maximo" id="stock_maximo" class="form-control @error('stock_maximo') is-invalid @enderror" value="{{ old('stock_maximo', $producto->stock_maximo) }}" placeholder="0">
                                    @error('stock_maximo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                            <select name="proveedor_id" id="proveedor_id" class="form-control @error('proveedor_id') is-invalid @enderror" required>
                                <option value="">Selecciona un proveedor...</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $producto->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="activo" {{ old('estado', $producto->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $producto->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save mr-2"></i>Actualizar Producto</button>
                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary btn-lg"><i class="fas fa-times mr-2"></i>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .card-title { font-weight: 600; }
        .form-group label { font-weight: 500; }
        .btn-lg { padding: .75rem 1.5rem; font-size: 1.1rem; }
        @media (max-width: 768px) {
            .offset-lg-2 { margin-left: 0; }
        }
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
            position: relative;
        }
        .image-preview-container:hover {
            border-color: #007bff;
            background-color: #e9ecef;
        }
        #image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        #image-placeholder {
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
            const productoSubcategoriaId = @json($producto->subcategoria_id);
            const productoCategoriaId = @json($producto->categoria_id);

            function loadSubcategories(selectedCategoriaId, selectedSubcategoriaId = null) {
                subcategoriaSelect.empty().append('<option value="">Selecciona una subcategoría...</option>');
                
                if (selectedCategoriaId) {
                    const filteredSubcategorias = subcategorias.filter(sub => sub.categoria_id == selectedCategoriaId);
                    
                    if (filteredSubcategorias.length > 0) {
                        filteredSubcategorias.forEach(sub => {
                            const isSelected = selectedSubcategoriaId == sub.id ? 'selected' : '';
                            subcategoriaSelect.append(`<option value="${sub.id}" ${isSelected}>${sub.nombre}</option>`);
                        });
                        subcategoriaSelect.prop('disabled', false);
                    } else {
                        subcategoriaSelect.prop('disabled', true);
                    }
                } else {
                    subcategoriaSelect.prop('disabled', true);
                }
            }

            // Cargar subcategorías al inicio si ya hay una categoría seleccionada (para el caso de edición)
            if (productoCategoriaId) {
                categoriaSelect.val(productoCategoriaId);
                loadSubcategories(productoCategoriaId, productoSubcategoriaId);
            }

            categoriaSelect.on('change', function() {
                loadSubcategories($(this).val());
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
