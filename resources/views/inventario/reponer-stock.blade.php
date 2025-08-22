@extends('layouts.material')

@section('title', 'Reponer Stock de Productos')

@section('content')
<div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">production_quantity_limits</i>Proceso de Reposición de Stock</h6>
        </div>
    </div>
    <div class="card-body px-4 pb-3">

        <!-- Navegación de Pestañas -->
        <ul class="nav nav-tabs" id="reponerStockTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="seleccionar-tab" data-bs-toggle="tab" data-bs-target="#seleccionar" type="button" role="tab" aria-controls="seleccionar" aria-selected="true">Paso 1: Seleccionar Productos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notificar-tab" data-bs-toggle="tab" data-bs-target="#notificar" type="button" role="tab" aria-controls="notificar" aria-selected="false" disabled>Paso 2: Notificar a Proveedores</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="confirmar-tab" data-bs-toggle="tab" data-bs-target="#confirmar" type="button" role="tab" aria-controls="confirmar" aria-selected="false" disabled>Paso 3: Confirmar Compra</button>
            </li>
        </ul>

        <!-- Contenido de las Pestañas -->
        <div class="tab-content" id="reponerStockTabContent">
            <!-- Paso 1: Seleccionar Productos -->
            <div class="tab-pane fade show active" id="seleccionar" role="tabpanel" aria-labelledby="seleccionar-tab">
                <div class="py-3">
                    @if ($productosParaReponer->isEmpty())
                        <div class="alert alert-success text-white text-center">
                            <i class="material-icons opacity-10 me-2">check_circle</i>¡Excelente! No hay productos con bajo stock en este momento.
                        </div>
                    @else
                        <form id="form-seleccionar-productos">
                            @php
                                $productosAgrupados = $productosParaReponer->groupBy('proveedor.nombre');
                            @endphp

                            @foreach ($productosAgrupados as $nombreProveedor => $productos)
                                <div class="mb-4">
                                    <h5 class="text-capitalize border-bottom pb-2">{{ $nombreProveedor ?: 'Proveedor no especificado' }}</h5>
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="50px">Incluir</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stock Actual</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stock Máximo</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad a Reponer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($productos as $producto)
                                                    @php
                                                        $cantidadAReponer = $producto->stock_maximo - $producto->stock_actual;
                                                    @endphp
                                                    <tr data-proveedor-id="{{ $producto->proveedor_id }}" data-proveedor-nombre="{{ $producto->proveedor->nombre }}" data-proveedor-email="{{ $producto->proveedor->correo }}">
                                                        <td class="align-middle text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input producto-checkbox" type="checkbox" value="{{ $producto->id }}" checked>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div>
                                                                    <img src="{{ $producto->foto_url ? Storage::disk('product_images')->url($producto->foto_url) : asset('assets/img/default-150x150.png') }}" class="avatar avatar-sm me-3" alt="{{ $producto->nombre }}">
                                                                </div>
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm producto-nombre">{{ $producto->nombre }}</h6>
                                                                    <p class="text-xs text-secondary mb-0">{{ $producto->codigo_sku }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <span class="badge badge-sm bg-gradient-warning">{{ $producto->stock_actual }}</span>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <p class="text-xs font-weight-bold mb-0">{{ $producto->stock_maximo }}</p>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <div class="input-group input-group-outline" style="width: 100px; margin: auto;">
                                                                <input type="number" name="productos[{{ $producto->id }}][cantidad]" class="form-control cantidad-a-reponer" value="{{ $cantidadAReponer > 0 ? $cantidadAReponer : 1 }}" min="0" data-producto-id="{{ $producto->id }}">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" id="btn-preparar-notificacion" class="btn btn-primary">Siguiente: Preparar Notificación <i class="material-icons opacity-10 ms-2">arrow_forward</i></button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Paso 2: Notificar a Proveedores -->
            <div class="tab-pane fade" id="notificar" role="tabpanel" aria-labelledby="notificar-tab">
                <div class="py-3" id="notificacion-container">
                    <!-- El contenido se generará con JavaScript -->
                </div>
                 <div class="d-flex justify-content-between mt-4">
                    <button type="button" id="btn-volver-seleccion" class="btn btn-secondary"><i class="material-icons opacity-10 me-2">arrow_back</i> Volver a Selección</button>
                    <button type="button" id="btn-enviar-notificaciones" class="btn btn-success">Enviar Notificaciones y Pasar a Confirmación <i class="material-icons opacity-10 ms-2">send</i></button>
                </div>
            </div>

            <!-- Paso 3: Confirmar Compra -->
            <div class="tab-pane fade" id="confirmar" role="tabpanel" aria-labelledby="confirmar-tab">
                <div class="py-3" id="confirmacion-container">
                    <!-- El contenido se generará después de notificar -->
                     <div class="alert alert-info text-white">
                        <p>Aquí aparecerán las órdenes de compra una vez que se hayan enviado las notificaciones a los proveedores.</p>
                        <p>Desde aquí podrá confirmar la recepción de los productos para actualizar el stock automáticamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Variable global para almacenar los productos seleccionados, accesible por ambas funciones de click
    let productosSeleccionados = {};

    const btnPrepararNotificacion = document.getElementById('btn-preparar-notificacion');
    const notificacionContainer = document.getElementById('notificacion-container');
    const tabElNotificar = document.getElementById('notificar-tab');
    const notificarTab = new bootstrap.Tab(tabElNotificar);

    btnPrepararNotificacion.addEventListener('click', function() {
        // Limpiar y re-poblar el objeto global
        productosSeleccionados = {}; 
        
        document.querySelectorAll('input.producto-checkbox:checked').forEach(checkbox => {
            const fila = checkbox.closest('tr');
            const cantidadInput = fila.querySelector('input.cantidad-a-reponer');
            const cantidad = parseInt(cantidadInput.value, 10);

            if (cantidad > 0) {
                const proveedorId = fila.dataset.proveedorId;
                
                if (!productosSeleccionados[proveedorId]) {
                    productosSeleccionados[proveedorId] = {
                        nombre: fila.dataset.proveedorNombre,
                        email: fila.dataset.proveedorEmail,
                        productos: []
                    };
                }
                
                productosSeleccionados[proveedorId].productos.push({
                    id: checkbox.value,
                    nombre: fila.querySelector('.producto-nombre').textContent,
                    cantidad: cantidad
                });
            }
        });

        if (Object.keys(productosSeleccionados).length === 0) {
            Swal.fire('No hay productos seleccionados', 'Por favor, seleccione al menos un producto y especifique una cantidad mayor a cero.', 'warning');
            return;
        }

        // Generar HTML para la pestaña de notificación
        let html = '';
        const nombreEmpresa = "{{ $nombreEmpresa }}";
        const rifEmpresa = "{{ $rifEmpresa }}";

        for (const proveedorId in productosSeleccionados) {
            const data = productosSeleccionados[proveedorId];
            let listaProductos = '<ul>';
            data.productos.forEach(p => {
                listaProductos += `<li>${p.nombre} - Cantidad: ${p.cantidad}</li>`;
            });
            listaProductos += '</ul>';

            html += `
                <div class="card mb-4" data-proveedor-id="${proveedorId}">
                    <div class="card-header">
                        <h5>Solicitud para: ${data.nombre}</h5>
                        <p class="text-sm">Se enviará una notificación a: <strong>${data.email || 'No especificado'}</strong></p>
                    </div>
                    <div class="card-body">
                        <h6>Mensaje de Notificación:</h6>
                        <textarea class="form-control" rows="10">
Estimado proveedor ${data.nombre},

Nos ponemos en contacto con ustedes para solicitar una cotización o iniciar un pedido de los siguientes productos:

${data.productos.map(p => `- ${p.nombre} (Cantidad: ${p.cantidad})`).join('\n')}

Agradeceríamos que nos confirmaran la disponibilidad y los precios a la brevedad posible.

Atentamente,
${nombreEmpresa}
RIF: ${rifEmpresa}
                        </textarea>
                        <hr class="dark horizontal">
                        <h6>Productos en esta solicitud:</h6>
                        ${listaProductos}
                    </div>
                </div>
            `;
        }
        notificacionContainer.innerHTML = html;

        // Habilitar y cambiar a la pestaña de notificación
        tabElNotificar.removeAttribute('disabled');
        notificarTab.show();
    });

    // Lógica para el botón de volver
    document.getElementById('btn-volver-seleccion').addEventListener('click', function() {
        const seleccionarTab = new bootstrap.Tab(document.getElementById('seleccionar-tab'));
        seleccionarTab.show();
    });

    // Lógica para enviar notificaciones y pasar a la siguiente pestaña
    document.getElementById('btn-enviar-notificaciones').addEventListener('click', function() {
        const payload = {
            proveedores: [],
            _token: '{{ csrf_token() }}'
        };

        const notificaciones = document.querySelectorAll('#notificacion-container .card');
        notificaciones.forEach(card => {
            const proveedorId = card.dataset.proveedorId;
            if (!proveedorId || !productosSeleccionados[proveedorId]) return;

            const data = productosSeleccionados[proveedorId];
            const mensaje = card.querySelector('textarea').value;

            payload.proveedores.push({
                id: proveedorId,
                mensaje: mensaje,
                productos: data.productos
            });
        });

        if (payload.proveedores.length === 0) {
            Swal.fire('Error', 'No se encontraron datos de notificación para enviar.', 'error');
            return;
        }

        Swal.fire({
            title: 'Enviando notificaciones...',
            text: 'Por favor, espere.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('{{ route("inventario.enviarNotificaciones") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
            });

            document.getElementById('confirmacion-container').innerHTML = data.confirmacion_html;
            const tabElConfirmar = document.getElementById('confirmar-tab');
            tabElConfirmar.removeAttribute('disabled');
            new bootstrap.Tab(tabElConfirmar).show();
        })
        .catch(error => {
            let errorMsg = 'No se pudieron enviar las notificaciones.';
            if (error.errors) {
                errorMsg += ' ' + Object.values(error.errors).flat().join(' ');
            } else if (error.message) {
                errorMsg += ' ' + error.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg,
            });
        });
    });
});
</script>
@endsection
