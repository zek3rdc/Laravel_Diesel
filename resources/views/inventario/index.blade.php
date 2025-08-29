@extends('layouts.material')

@section('title', 'Inventario de Productos')

@section('css')
    <!-- Force-load Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        .swal2-container {
            z-index: 10054 !important;
        }
        /* Asegura que el input de SweetAlert2 sea interactivo */
        .swal2-input {
            pointer-events: auto !important;
            opacity: 1 !important;
            background-color: #fff !important; /* Asegura un fondo visible */
            color: #333 !important; /* Asegura un color de texto visible */
            border: 1px solid #ccc !important; /* Asegura un borde visible */
            padding: 0.625em 1.25em !important; /* Ajusta el padding */
            position: relative !important; /* Asegura que z-index tenga efecto */
            z-index: 10055 !important; /* Un z-index aún más alto para el input */
        }

        /* Asegura que el input-group-outline no interfiera con los inputs de SweetAlert2 */
        .swal2-html-container .input-group-outline {
            border: none !important;
            box-shadow: none !important;
        }

        /* --- FIX PARA INPUTS EN MODALES --- */
        /* La regla anterior 'border: none' en '.modal-body .input-group-outline' 
           hacía que los campos de texto fueran invisibles.
           Esta nueva regla restaura el borde para que se vean correctamente.
        */
        .modal-body .input-group.input-group-outline {
            border: 1px solid #d2d6da !important;
            border-radius: .5rem !important;
        }

        /* --- FIX PARA SELECT2 DENTRO DE INPUT-GROUP --- */
        .input-group .select2-container--bootstrap-5 {
            width: 100% !important;
        }
        .input-group .select2-selection {
            border: 0 !important;
            box-shadow: none !important;
        }
        .input-group .select2-selection__rendered {
            padding-left: 0 !important;
        }

    </style>
@endsection

@section('content')
    {{-- Indicadores Clave (KPIs) --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">attach_money</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Valor Total del Inventario</p>
                        <h4 class="mb-0" id="kpi-valor-total">${{ number_format($valorTotalInventario ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <a href="{{ route('inventario.index', ['filtro_kpi' => 'activos']) }}">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">check_circle</i>
                    </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Productos Activos</p>
                            <h4 class="mb-0" id="kpi-productos-activos">{{ $productosActivos ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <a href="{{ route('inventario.index', ['filtro_kpi' => 'bajo_stock']) }}">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">warning</i>
                    </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Bajo Stock</p>
                            <h4 class="mb-0" id="kpi-bajo-stock">{{ $productosBajoStock ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <a href="{{ route('inventario.index', ['filtro_kpi' => 'inactivos']) }}">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-icons opacity-10">cancel</i>
                    </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Productos Inactivos</p>
                            <h4 class="mb-0" id="kpi-productos-inactivos">{{ $productosInactivos ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">inventory_2</i>Lista de Productos</h6>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="px-4 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @can('anadir productos')
                        <a href="{{ route('inventario.create') }}" class="btn btn-primary" data-toggle="tooltip" title="Agregar un nuevo producto al inventario">
                            <i class="material-icons opacity-10 me-2">add</i> Nuevo Producto
                        </a>
                        @endcan
                        @can('realizar ventas')
                        <button id="btn-nueva-venta" class="btn btn-success" data-toggle="tooltip" title="Registrar una nueva venta">
                            <i class="material-icons opacity-10 me-2">shopping_cart</i> Ventas
                        </button>
                        @endcan
                        @can('ver productos') {{-- Asumiendo que el permiso para ver productos es suficiente para ver órdenes pendientes --}}
                        <a href="{{ route('inventario.ordenesPendientes') }}" class="btn btn-info" data-toggle="tooltip" title="Ver órdenes de compra pendientes de confirmación">
                            <i class="material-icons opacity-10 me-2">receipt_long</i> Órdenes Pendientes
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
                <form action="{{ route('inventario.index') }}" method="GET" class="row g-3 align-items-center mt-3">
                    <div class="col-md-4">
                        <div class="input-group input-group-outline">
                            <label class="form-label">Buscar por nombre o SKU</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="input-group input-group-outline">
                            <select name="subcategoria_id" class="form-control">
                                <option value="">Todas las Subcategorías</option>
                                @foreach ($subcategorias as $subcategoria)
                                    <option value="{{ $subcategoria->id }}" {{ request('subcategoria_id') == $subcategoria->id ? 'selected' : '' }}>
                                        {{ $subcategoria->nombre }} ({{ $subcategoria->categoria->nombre }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-outline">
                            <select name="proveedor_id" class="form-control">
                                <option value="">Todos los Proveedores</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                        {{ $proveedor->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary"><i class="material-icons opacity-10">search</i></button>
                        <a href="{{ route('inventario.index') }}" class="btn btn-secondary"><i class="material-icons opacity-10">refresh</i></a>
                    </div>
                </form>
            </div>

            <div class="row px-4 mt-4" id="product-cards-container">
                @forelse ($productos as $producto)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card product-card" data-id="{{ $producto->id }}" style="cursor: pointer;">
                             <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                <div class="image-container" style="height: 200px; position: relative; background-color: #f0f0f0; border-radius: .5rem; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                    <div class="loading-spinner">
                                        <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                                    </div>
                                    <img src="{{ $producto->foto_url ? asset('storage/product_images/' . $producto->foto_url) : asset('assets/img/default-150x150.png') }}" class="product-image" alt="{{ $producto->nombre }}" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <h5 class="card-title font-weight-bold text-truncate" data-toggle="tooltip" title="{{ $producto->nombre }}">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted small">{{ $producto->codigo_sku }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-lg text-primary font-weight-bold">€{{ number_format($producto->precio_venta, 2) }}</span>
                                    <span class="badge badge-sm {{ $producto->stock_actual <= $producto->stock_minimo ? 'bg-gradient-danger' : 'bg-gradient-success' }}">
                                        Stock: {{ $producto->stock_actual }}
                                    </span>
                                </div>
                                 <hr class="dark horizontal">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted d-block text-truncate">
                                        <i class="material-icons opacity-10 fs-6 me-1">category</i> {{ $producto->subcategoria->nombre ?? 'N/A' }}
                                    </small>
                                    <small class="text-muted d-block text-truncate">
                                        <i class="material-icons opacity-10 fs-6 me-1">local_shipping</i> {{ $producto->proveedor->nombre ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>
                             @if ($producto->estado == 'activo')
                                <span class="badge bg-gradient-success position-absolute top-0 end-0 m-2" style="z-index: 2;">Activo</span>
                            @else
                                <span class="badge bg-gradient-danger position-absolute top-0 end-0 m-2" style="z-index: 2;">Inactivo</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-white text-center">
                            <i class="material-icons opacity-10 me-2">info</i>No se encontraron productos con los filtros aplicados.
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $productos->appends(request()->except('page'))->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Modal para Nueva Venta --}}
    <div class="modal fade" id="modal-nueva-venta" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"><i class="material-icons opacity-10 me-2">shopping_cart</i>Nueva Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Paso 1: Buscar o Crear Cliente --}}
                    <div id="paso-cliente">
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Cédula del Cliente</label>
                            <input type="text" class="form-control" id="cliente-cedula" placeholder="Ingrese la cédula o RUC">
                            <button class="btn btn-primary mb-0" type="button" id="btn-buscar-cliente">Buscar</button>
                        </div>
                        <div id="cliente-info" class="mt-3" style="display: none;">
                            {{-- Aquí se mostrarán los datos del cliente o el formulario de creación --}}
                        </div>
                    </div>

                    {{-- Paso 2: Carrito de Compras --}}
                    <div id="paso-carrito" style="display: none;">
                        <hr class="dark horizontal">
                        <h5><i class="material-icons opacity-10 me-2">shopping_bag</i>Carrito de Compras</h5>
                        
                        <div class="input-group input-group-outline my-3">
                            <label class="form-label">Buscar Producto</label>
                            <input type="text" id="producto-search" class="form-control" placeholder="Escriba el nombre o SKU del producto...">
                        </div>
                        <div id="producto-search-results"></div>

                        <table class="table table-hover align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Precio</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="120px">Cantidad</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subtotal</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody id="carrito-tabla-body">
                                {{-- Filas del carrito se añadirán aquí --}}
                            </tbody>
                        </table>

                        <div class="row justify-content-end mt-3">
                            <div class="col-md-4">
                                <h4 class="text-end">Total: <span id="carrito-total" class="font-weight-bold">€0.00</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-volver-paso" style="display: none;">Volver</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-siguiente-paso" disabled>Siguiente</button>
                    <button type="button" class="btn btn-success" id="btn-finalizar-venta" style="display: none;">Finalizar Venta</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Selección de Moneda de Pago --}}
    <div class="modal fade" id="modal-seleccion-pago" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10051;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Método de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>¿Cómo desea pagar el cliente?</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg w-100 mb-2" data-moneda="EUR">
                            Pagar en Euros (€)
                            <small class="d-block font-weight-bold" id="total-eur">--</small>
                        </button>
                        <button type="button" class="btn btn-success btn-lg w-100 mb-2" data-moneda="USD">
                            Pagar en Dólares ($)
                            <small class="d-block font-weight-bold" id="total-usd">--</small>
                        </button>
                        <button type="button" class="btn btn-info btn-lg w-100" data-moneda="VES">
                            Pagar en Bolívares (Bs.)
                            <small class="d-block font-weight-bold" id="total-ves">--</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Finalizar Pago y Vuelto --}}
    <div class="modal fade" id="modal-finalizar-pago" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10054;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finalizar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-center mb-3">Total a Pagar: <span id="pago-total-convertido" class="font-weight-bold"></span></h4>
                    
                    <div class="input-group input-group-static my-3">
                        <label>Monto Recibido</label>
                        <input type="number" class="form-control form-control-lg" id="pago-monto-recibido" placeholder="0.00" step="any">
                    </div>

                    <div class="input-group input-group-outline my-3">
                        <select class="form-control" id="pago-metodo">
                            <option value="">Seleccione Método de Pago</option>
                            {{-- Opciones se cargarán dinámicamente con JS --}}
                        </select>
                    </div>

                    <div class="input-group input-group-outline my-3" id="referencia-pago-group" style="display: none;">
                        <label class="form-label">Referencia (Opcional)</label>
                        <input type="text" class="form-control form-control-lg" id="pago-referencia" placeholder="Número de referencia">
                    </div>

                    <div class="alert alert-info text-white mt-3">
                        <h5>Vuelto: <span id="pago-vuelto" class="font-weight-bold">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-venta-final" disabled>Confirmar y Registrar Venta</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Ingresar Número de Factura --}}
    <div class="modal fade" id="modal-ingresar-factura" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 10053;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Asignar Número de Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Puedes generar un número automáticamente o ingresarlo manualmente.</p>
                    <div class="d-grid gap-2 mb-3">
                        <button type="button" class="btn btn-primary btn-lg w-100" id="btn-generar-factura-auto">
                            Generar Automáticamente
                        </button>
                    </div>
                    <hr class="dark horizontal my-3">
                    <p class="text-muted">O ingresa el número manualmente:</p>
                    <div class="input-group input-group-static my-3">
                        <label>Número de Factura</label>
                        <input type="text" class="form-control form-control-lg" id="input-nro-factura" placeholder="Ej: FAC-00123">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btn-confirmar-nro-factura" disabled>Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Function to initialize floating labels
            function initializeFloatingLabels() {
                var inputs = document.querySelectorAll('.input-group.input-group-outline');
                inputs.forEach(function(input) {
                    var inputField = input.querySelector('input, select');
                    if (inputField) {
                        inputField.removeEventListener('focus', handleFocus);
                        inputField.removeEventListener('blur', handleBlur);
                        inputField.addEventListener('focus', handleFocus);
                        inputField.addEventListener('blur', handleBlur);
                        if (inputField.value !== '') {
                           input.classList.add('is-focused');
                        } else {
                           input.classList.remove('is-focused');
                        }
                    }
                });
            }

            function handleFocus() {
                this.closest('.input-group-outline').classList.add('is-focused');
            }

            function handleBlur() {
                if (this.value === '') {
                    this.closest('.input-group-outline').classList.remove('is-focused');
                }
            }

            // Initial call for existing elements
            initializeFloatingLabels();

            // Double click on product card
            let clickTimer = null;
            document.getElementById('product-cards-container').addEventListener('click', function(e) {
                const card = e.target.closest('.product-card');
                if (!card) return;

                if (clickTimer === null) {
                    clickTimer = setTimeout(() => {
                        clickTimer = null;
                    }, 300);
                } else {
                    clearTimeout(clickTimer);
                    clickTimer = null;
                    
                    e.preventDefault();
                    e.stopPropagation();

                    const productoId = card.dataset.id;
                    const titleElement = card.querySelector('.card-title');
                    const productoNombre = titleElement.textContent.trim() || titleElement.getAttribute('title');
                    
                    Swal.fire({
                        title: 'Acciones para ' + productoNombre,
                        html: `
                            <div class="d-grid gap-2">
                                <a href="/inventario/${productoId}" class="btn btn-info btn-lg w-100 mb-2"><i class="material-icons opacity-10 me-2">visibility</i> Ver Detalles</a>
                                @can('modificar productos')
                                <a href="/inventario/${productoId}/editar" class="btn btn-warning btn-lg w-100 mb-2"><i class="material-icons opacity-10 me-2">edit</i> Editar Producto</a>
                                @endcan
                                @can('eliminar productos')
                                <button type="button" class="btn btn-danger btn-lg w-100" onclick="confirmDelete(${productoId}, '${productoNombre}')"><i class="material-icons opacity-10 me-2">delete</i> Eliminar Producto</button>
                                @endcan
                            </div>
                        `,
                        showCancelButton: false,
                        showConfirmButton: false,
                        width: '400px'
                    });
                }
            });

            // Confirm Delete
            window.confirmDelete = function(productoId, productoNombre) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    html: `Se eliminará el producto <strong>${productoNombre}</strong>. <br>¡Esta acción no se puede revertir!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Crear un formulario dinámicamente
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/inventario/${productoId}`;

                        // Campo para el token CSRF
                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}'; // Blade procesará esto correctamente
                        form.appendChild(csrfToken);

                        // Campo para el método DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        // Añadir el formulario al body y enviarlo
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // Image loading
            document.querySelectorAll('.product-image').forEach(function(image) {
                const container = image.closest('.image-container');
                const spinner = container.querySelector('.loading-spinner');
                
                image.addEventListener('load', () => {
                    if(spinner) spinner.style.display = 'none';
                    image.style.display = 'block';
                });
                image.addEventListener('error', () => {
                    if(spinner) spinner.style.display = 'none';
                });

                if (image.complete) {
                    if(spinner) spinner.style.display = 'none';
                    image.style.display = 'block';
                }
            });

            // --- VENTA MODAL LOGIC ---
            const ventaModalEl = document.getElementById('modal-nueva-venta');
            const ventaModal = new bootstrap.Modal(ventaModalEl);
            const seleccionPagoModalEl = document.getElementById('modal-seleccion-pago');
            const seleccionPagoModal = new bootstrap.Modal(seleccionPagoModalEl);
            const finalizarPagoModalEl = document.getElementById('modal-finalizar-pago');
            const finalizarPagoModal = new bootstrap.Modal(finalizarPagoModalEl);
            const ingresarFacturaModalEl = document.getElementById('modal-ingresar-factura');
            const ingresarFacturaModal = new bootstrap.Modal(ingresarFacturaModalEl);

            document.getElementById('btn-nueva-venta').addEventListener('click', () => ventaModal.show());

            let carrito = [];
            let exchangeRates = {};

            // Get references to the new invoice modal elements
            const btnGenerarFacturaAuto = document.getElementById('btn-generar-factura-auto');
            const inputNroFactura = document.getElementById('input-nro-factura');
            const btnConfirmarNroFactura = document.getElementById('btn-confirmar-nro-factura');

            // Client Search
            document.getElementById('btn-buscar-cliente').addEventListener('click', function() {
                const cedula = document.getElementById('cliente-cedula').value;
                if (!cedula) {
                    Swal.fire('Error', 'Por favor, ingrese una cédula o RUC.', 'error');
                    return;
                }

                fetch(`/api/clientes/buscar/${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        const clienteInfo = document.getElementById('cliente-info');
                        if (data.cliente) {
                            clienteInfo.innerHTML = `
                                <div class="alert alert-success text-white">Cliente encontrado: <strong>${data.cliente.nombre}</strong></div>
                                <input type="hidden" id="cliente-id" value="${data.cliente.id}">
                            `;
                            document.getElementById('btn-siguiente-paso').disabled = false;
                        } else {
                            clienteInfo.innerHTML = `
                                <div class="alert alert-warning text-white">Cliente no encontrado. Por favor, registre los datos.</div>
                                <form id="form-nuevo-cliente">
                                    <input type="hidden" name="cedula" value="${cedula}">
                                    <div class="row">
                                        <div class="col-md-6"><div class="input-group input-group-outline my-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" required></div></div>
                                        <div class="col-md-6"><div class="input-group input-group-outline my-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="correo"></div></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><div class="input-group input-group-outline my-3"><label class="form-label">Teléfono</label><input type="text" class="form-control" name="telefono"></div></div>
                                        <div class="col-md-6"><div class="input-group input-group-outline my-3"><label class="form-label">Dirección</label><input type="text" class="form-control" name="direccion"></div></div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Crear Cliente</button>
                                </form>
                            `;
                            document.getElementById('btn-siguiente-paso').disabled = true;
                        }
                        clienteInfo.style.display = 'block';
                        initializeFloatingLabels(); // Call after content is updated
                    })
                    .catch(() => Swal.fire('Error', 'Ocurrió un error al buscar el cliente.', 'error'));
            });
            
            // --- DYNAMIC CLIENT CREATION ---
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.id === 'form-nuevo-cliente') {
                    e.preventDefault();
                    const formData = new FormData(e.target);
                    
                    fetch('/api/clientes', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            let errorHtml = '<ul>';
                            for (const error of Object.values(data.errors)) {
                                errorHtml += `<li>${error}</li>`;
                            }
                            errorHtml += '</ul>';
                            Swal.fire('Error de Validación', errorHtml, 'error');
                            return;
                        }
                        
                        Swal.fire('Éxito', 'Cliente creado correctamente.', 'success');
                        const clienteInfo = document.getElementById('cliente-info');
                        clienteInfo.innerHTML = `
                            <div class="alert alert-success text-white">Cliente creado: <strong>${data.cliente.nombre}</strong></div>
                            <input type="hidden" id="cliente-id" value="${data.cliente.id}">
                        `;
                        document.getElementById('btn-siguiente-paso').disabled = false;
                        initializeFloatingLabels(); // Call after content is updated
                    })
                    .catch(() => Swal.fire('Error', 'Ocurrió un error al crear el cliente.', 'error'));
                }
            });

            // --- MODAL STEP NAVIGATION ---
            const pasoCliente = document.getElementById('paso-cliente');
            const pasoCarrito = document.getElementById('paso-carrito');
            const btnSiguiente = document.getElementById('btn-siguiente-paso');
            const btnVolver = document.getElementById('btn-volver-paso');
            const btnFinalizar = document.getElementById('btn-finalizar-venta');

            btnSiguiente.addEventListener('click', function() {
                pasoCliente.style.display = 'none';
                pasoCarrito.style.display = 'block';
                this.style.display = 'none';
                btnVolver.style.display = 'inline-block';
                btnFinalizar.style.display = 'inline-block';
            });

            btnVolver.addEventListener('click', function() {
                pasoCarrito.style.display = 'none';
                pasoCliente.style.display = 'block';
                this.style.display = 'none';
                btnFinalizar.style.display = 'none';
                btnSiguiente.style.display = 'inline-block';
            });

            // --- SHOPPING CART LOGIC ---
            const productoSearchInput = document.getElementById('producto-search');
            const searchResultsContainer = document.getElementById('producto-search-results');
            const carritoBody = document.getElementById('carrito-tabla-body');
            const carritoTotalEl = document.getElementById('carrito-total');

            productoSearchInput.addEventListener('keyup', function() {
                const term = this.value;
                if (term.length < 2) {
                    searchResultsContainer.innerHTML = '';
                    return;
                }

                fetch(`/api/productos/buscar?term=${term}`)
                    .then(response => response.json())
                    .then(productos => {
                        let resultsHtml = '<ul class="list-group">';
                        productos.forEach(producto => {
                            const productoData = encodeURIComponent(JSON.stringify(producto));
                            resultsHtml += `
                                <li class="list-group-item list-group-item-action d-flex align-items-center" data-producto='${productoData}' style="cursor:pointer;">
                                    <img src="${producto.foto_url_completa}" alt="${producto.nombre}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <strong>${producto.nombre}</strong> (${producto.codigo_sku})
                                        <br>
                                        <small>Stock: ${producto.stock_actual}</small>
                                    </div>
                                </li>`;
                        });
                        resultsHtml += '</ul>';
                        searchResultsContainer.innerHTML = resultsHtml;
                    });
            });

            searchResultsContainer.addEventListener('click', function(e) {
                const li = e.target.closest('li');
                if (li && li.dataset.producto) {
                    const producto = JSON.parse(decodeURIComponent(li.dataset.producto));
                    agregarAlCarrito({
                        id: producto.id,
                        nombre: producto.nombre,
                        precio: parseFloat(producto.precio_venta),
                        stock: producto.stock_actual,
                        foto_url_completa: producto.foto_url_completa
                    });
                    productoSearchInput.value = '';
                    searchResultsContainer.innerHTML = '';
                }
            });

            function agregarAlCarrito(producto) {
                const existente = carrito.find(item => item.id === producto.id);
                if (existente) {
                    if (existente.cantidad < existente.stock) {
                        existente.cantidad++;
                    } else {
                        Swal.fire('Stock insuficiente', `No puedes agregar más de ${existente.stock} unidades de ${existente.nombre}.`, 'warning');
                    }
                } else {
                    carrito.push({ ...producto, cantidad: 1 });
                }
                renderizarCarrito();
            }

            function renderizarCarrito() {
                carritoBody.innerHTML = '';
                carrito.forEach(item => {
                    const row = document.createElement('tr');
                    row.dataset.id = item.id;
                    row.innerHTML = `
                        <td class="align-middle">
                            <div class="d-flex px-2 py-1">
                                <div>
                                    <img src="${item.foto_url_completa}" class="avatar avatar-sm me-3" alt="product">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">${item.nombre}</h6>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle"><p class="text-xs font-weight-bold mb-0">€${item.precio.toFixed(2)}</p></td>
                        <td class="align-middle text-center">
                            <div class="input-group input-group-outline" style="width: 100px; margin: auto;">
                                <input type="number" class="form-control cantidad-item" value="${item.cantidad}" min="1" max="${item.stock}">
                            </div>
                        </td>
                        <td class="align-middle text-center text-sm"><p class="text-xs font-weight-bold mb-0 subtotal-item">€${(item.precio * item.cantidad).toFixed(2)}</p></td>
                        <td class="align-middle"><button class="btn btn-link text-danger text-gradient px-3 mb-0 btn-remover-item"><i class="material-icons text-sm">delete</i></button></td>
                    `;
                    carritoBody.appendChild(row);
                });
                calcularTotales();
            }

            carritoBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('cantidad-item')) {
                    const productoId = parseInt(e.target.closest('tr').dataset.id);
                    const nuevaCantidad = parseInt(e.target.value);
                    const item = carrito.find(i => i.id === productoId);
                    if (item) {
                        if (nuevaCantidad > item.stock) {
                            e.target.value = item.stock;
                            Swal.fire('Stock insuficiente', `El stock máximo para este producto es ${item.stock}.`, 'warning');
                        }
                        item.cantidad = parseInt(e.target.value);
                    }
                    renderizarCarrito();
                }
            });

            carritoBody.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-remover-item');
                if (btn) {
                    const productoId = parseInt(btn.closest('tr').dataset.id);
                    carrito = carrito.filter(i => i.id !== productoId);
                    renderizarCarrito();
                }
            });

            function calcularTotales() {
                const total = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
                carritoTotalEl.textContent = '€' + total.toFixed(2);
            }

            // --- PAYMENT LOGIC ---
            btnFinalizar.addEventListener('click', async function() {
                if (carrito.length === 0) {
                    Swal.fire('Carrito Vacío', 'Debe agregar al menos un producto al carrito.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Obteniendo tasas de cambio...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const response = await fetch('/api/currency-rates');
                    if (!response.ok) throw new Error('Error de red');
                    const data = await response.json();
                    if (data.error) throw new Error(data.error);

                    exchangeRates = data.rates;
                    Swal.close();
                    
                    const totalEnEUR = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
                    const totalUSD = (totalEnEUR * exchangeRates.EUR) / exchangeRates.USD;
                    const totalVES = totalEnEUR * exchangeRates.EUR;

                    document.getElementById('total-eur').textContent = `€ ${totalEnEUR.toFixed(2)}`;
                    document.getElementById('total-usd').textContent = `$ ${totalUSD.toFixed(2)}`;
                    document.getElementById('total-ves').textContent = `Bs. ${totalVES.toFixed(2)}`;
                    
                    seleccionPagoModal.show();
                } catch (error) {
                    Swal.fire('Error', 'No se pudieron obtener las tasas de cambio. Por favor, intente de nuevo.', 'error');
                }
            });

            document.getElementById('modal-seleccion-pago').addEventListener('click', function(e) {
                const btn = e.target.closest('.btn[data-moneda]');
                if (!btn) return;

                const moneda = btn.dataset.moneda;
                const totalEnEUR = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
                let totalConvertido = 0;
                let simbolo = '€';

                if (moneda === 'USD') {
                    totalConvertido = (totalEnEUR * exchangeRates.EUR) / exchangeRates.USD;
                    simbolo = '$';
                } else if (moneda === 'VES') {
                    totalConvertido = totalEnEUR * exchangeRates.EUR;
                    simbolo = 'Bs.';
                } else {
                    totalConvertido = totalEnEUR;
                }
                
                const pagoTotalEl = document.getElementById('pago-total-convertido');
                pagoTotalEl.textContent = `${simbolo}${totalConvertido.toFixed(2)}`;
                pagoTotalEl.dataset.total = totalConvertido;
                pagoTotalEl.dataset.moneda = moneda;
                pagoTotalEl.dataset.simbolo = simbolo;

                document.getElementById('pago-monto-recibido').value = '';
                document.getElementById('pago-vuelto').textContent = `${simbolo}0.00`;
                document.getElementById('btn-confirmar-venta-final').disabled = true;

                seleccionPagoModal.hide();
                finalizarPagoModal.show();
            });

            const pagoMetodoSelect = document.getElementById('pago-metodo');
            const referenciaPagoGroup = document.getElementById('referencia-pago-group');
            const pagoReferenciaInput = document.getElementById('pago-referencia');

            document.getElementById('pago-monto-recibido').addEventListener('input', function() {
                const montoRecibido = parseFloat(this.value) || 0;
                const pagoTotalEl = document.getElementById('pago-total-convertido');
                const totalConvertido = parseFloat(pagoTotalEl.dataset.total) || 0;
                const metodoSeleccionado = pagoMetodoSelect.value;
                const vueltoEl = document.getElementById('pago-vuelto');
                const btnConfirmar = document.getElementById('btn-confirmar-venta-final');

                if (metodoSeleccionado === 'Efectivo' && montoRecibido >= totalConvertido) {
                    const vuelto = montoRecibido - totalConvertido;
                    vueltoEl.textContent = `${pagoTotalEl.dataset.simbolo}${vuelto.toFixed(2)}`;
                    btnConfirmar.disabled = false;
                } else if (metodoSeleccionado !== 'Efectivo' && montoRecibido >= totalConvertido) {
                    vueltoEl.textContent = `${pagoTotalEl.dataset.simbolo}0.00`; // Vuelto siempre 0 para otros métodos
                    btnConfirmar.disabled = false;
                } else {
                    vueltoEl.textContent = `${pagoTotalEl.dataset.simbolo}0.00`;
                    btnConfirmar.disabled = true;
                }
            });

            pagoMetodoSelect.addEventListener('change', function() {
                const moneda = document.getElementById('pago-total-convertido').dataset.moneda;
                const metodo = this.value;
                const montoRecibido = parseFloat(document.getElementById('pago-monto-recibido').value) || 0;
                const totalConvertido = parseFloat(document.getElementById('pago-total-convertido').dataset.total) || 0;

                // Show/hide referencia field based on method
                if (moneda === 'EUR' && (metodo === 'Tarjeta' || metodo === 'Transferencia')) {
                    referenciaPagoGroup.style.display = 'block';
                } else if (moneda === 'USD' && (metodo === 'Tarjeta' || metodo === 'Transferencia')) {
                    referenciaPagoGroup.style.display = 'block';
                } else if (moneda === 'VES' && (metodo === 'Pago Móvil' || metodo === 'Transferencia')) {
                    referenciaPagoGroup.style.display = 'block';
                } else {
                    referenciaPagoGroup.style.display = 'none';
                    pagoReferenciaInput.value = ''; // Clear reference if not needed
                }
                // Re-initialize for the reference input after display change
                setTimeout(() => {
                    initializeFloatingLabels();
                }, 100);

                // Re-evaluate confirm button state
                const vueltoEl = document.getElementById('pago-vuelto');
                const btnConfirmar = document.getElementById('btn-confirmar-venta-final');

                if (metodo === 'Efectivo' && montoRecibido >= totalConvertido) {
                    const vuelto = montoRecibido - totalConvertido;
                    vueltoEl.textContent = `${document.getElementById('pago-total-convertido').dataset.simbolo}${vuelto.toFixed(2)}`;
                    btnConfirmar.disabled = false;
                } else if (metodo !== 'Efectivo' && montoRecibido >= totalConvertido) {
                    vueltoEl.textContent = `${document.getElementById('pago-total-convertido').dataset.simbolo}0.00`;
                    btnConfirmar.disabled = false;
                } else {
                    vueltoEl.textContent = `${document.getElementById('pago-total-convertido').dataset.simbolo}0.00`;
                    btnConfirmar.disabled = true;
                }
            });

            document.getElementById('modal-seleccion-pago').addEventListener('click', function(e) {
                const btn = e.target.closest('.btn[data-moneda]');
                if (!btn) return;

                const moneda = btn.dataset.moneda;
                const totalEnEUR = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
                let totalConvertido = 0;
                let simbolo = '€';
                let metodosPago = [];

                if (moneda === 'USD') {
                    totalConvertido = (totalEnEUR * exchangeRates.EUR) / exchangeRates.USD;
                    simbolo = '$';
                    metodosPago = ['Efectivo', 'Tarjeta', 'Transferencia'];
                } else if (moneda === 'VES') {
                    totalConvertido = totalEnEUR * exchangeRates.EUR;
                    simbolo = 'Bs.';
                    metodosPago = ['Efectivo', 'Pago Móvil', 'Transferencia'];
                } else { // EUR
                    totalConvertido = totalEnEUR;
                    simbolo = '€';
                    metodosPago = ['Efectivo', 'Tarjeta', 'Transferencia'];
                }
                
                const pagoTotalEl = document.getElementById('pago-total-convertido');
                pagoTotalEl.textContent = `${simbolo}${totalConvertido.toFixed(2)}`;
                pagoTotalEl.dataset.total = totalConvertido;
                pagoTotalEl.dataset.moneda = moneda;
                pagoTotalEl.dataset.simbolo = simbolo;

                // Populate payment methods
                pagoMetodoSelect.innerHTML = '<option value="">Seleccione Método de Pago</option>';
                metodosPago.forEach(metodo => {
                    const option = document.createElement('option');
                    option.value = metodo;
                    option.textContent = metodo;
                    pagoMetodoSelect.appendChild(option);
                });

                document.getElementById('pago-monto-recibido').value = '';
                document.getElementById('pago-vuelto').textContent = `${simbolo}0.00`;
                document.getElementById('btn-confirmar-venta-final').disabled = true;
                referenciaPagoGroup.style.display = 'none'; // Hide reference by default
                pagoReferenciaInput.value = ''; // Clear reference input
                
                // Re-initialize floating labels for all inputs in the modal after it's shown
                seleccionPagoModal.hide();
                finalizarPagoModal.show();
                setTimeout(() => {
                    initializeFloatingLabels();
                }, 100); // Small delay to ensure DOM is ready

            });

            document.getElementById('btn-confirmar-venta-final').addEventListener('click', function() {
                const montoRecibido = parseFloat(document.getElementById('pago-monto-recibido').value) || 0;
                const pagoTotalEl = document.getElementById('pago-total-convertido');
                const totalConvertido = parseFloat(pagoTotalEl.dataset.total) || 0;
                const metodoPago = pagoMetodoSelect.value;

                if (montoRecibido < totalConvertido) {
                    Swal.fire('Monto Insuficiente', 'El monto recibido es menor que el total a pagar.', 'error');
                    return;
                }
                if (metodoPago === '') {
                    Swal.fire('Método de Pago', 'Por favor, seleccione un método de pago.', 'error');
                    return;
                }

                finalizarPagoModal.hide();
                ingresarFacturaModal.show();
                initializeFloatingLabels();
                inputNroFactura.value = '';
                btnConfirmarNroFactura.disabled = true;
            });

            btnGenerarFacturaAuto.addEventListener('click', function() {
                ingresarFacturaModal.hide();
                enviarVenta(`VENTA-${Date.now()}`);
            });

            inputNroFactura.addEventListener('input', function() {
                btnConfirmarNroFactura.disabled = this.value.trim() === '';
            });

            btnConfirmarNroFactura.addEventListener('click', function() {
                const nroFactura = inputNroFactura.value.trim();
                if (nroFactura) {
                    ingresarFacturaModal.hide();
                    enviarVenta(nroFactura);
                } else {
                    Swal.fire('Error', 'Por favor, ingrese un número de factura.', 'error');
                }
            });

            function enviarVenta(nroFactura) {
                const pagoTotalEl = document.getElementById('pago-total-convertido');
                const metodoPago = document.getElementById('pago-metodo').value;
                const referenciaPago = document.getElementById('pago-referencia').value;

                const ventaData = {
                    cliente_id: document.getElementById('cliente-id').value,
                    items: carrito,
                    nro_factura: nroFactura,
                    pago: {
                        moneda: pagoTotalEl.dataset.moneda,
                        metodo: metodoPago,
                        referencia: referenciaPago,
                    },
                    _token: '{{ csrf_token() }}'
                };

                Swal.fire({
                    title: 'Registrando Venta...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('/api/ventas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(ventaData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error || data.errors) {
                        let errorMsg = data.error || Object.values(data.errors).flat().join('<br>');
                        Swal.fire('Error', errorMsg, 'error');
                        return;
                    }

                    // UI Updates
                    carrito.forEach(itemVendido => {
                        const card = document.querySelector(`.product-card[data-id="${itemVendido.id}"]`);
                        if (card) {
                            const stockElement = card.querySelector('.badge');
                            const stockActual = parseInt(stockElement.textContent.replace('Stock: ', ''));
                            stockElement.textContent = `Stock: ${stockActual - itemVendido.cantidad}`;
                        }
                    });

                    if (data.kpis) {
                        document.getElementById('kpi-valor-total').textContent = `$${parseFloat(data.kpis.valorTotalInventario).toFixed(2)}`;
                        document.getElementById('kpi-productos-activos').textContent = data.kpis.productosActivos;
                        document.getElementById('kpi-bajo-stock').textContent = data.kpis.productosBajoStock;
                        document.getElementById('kpi-productos-inactivos').textContent = data.kpis.productosInactivos;
                    }

                    Swal.fire('¡Venta Registrada!', `La venta #${data.nro_factura} ha sido registrada.`, 'success');
                    
                    // Reset state
                    finalizarPagoModal.hide();
                    ventaModal.hide();
                    resetVentaModal();
                })
                .catch(err => Swal.fire('Error', 'Ocurrió un error inesperado al registrar la venta.', 'error'));
            }

            function resetVentaModal() {
                carrito = [];
                renderizarCarrito();
                document.getElementById('cliente-info').style.display = 'none';
                document.getElementById('cliente-info').innerHTML = '';
                document.getElementById('cliente-cedula').value = '';
                document.getElementById('btn-siguiente-paso').disabled = true;
                
                pasoCarrito.style.display = 'none';
                pasoCliente.style.display = 'block';
                btnVolver.style.display = 'none';
                btnFinalizar.style.display = 'none';
                btnSiguiente.style.display = 'inline-block';
            }
        });
    </script>
@stop
