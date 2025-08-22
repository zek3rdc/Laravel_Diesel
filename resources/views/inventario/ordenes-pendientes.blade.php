@extends('layouts.material')

@section('title', 'Órdenes de Compra Pendientes')

@section('content')
<div class="card my-4">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">receipt_long</i>Órdenes de Compra Pendientes</h6>
        </div>
    </div>
    <div class="card-body px-0 pb-2">
        <div class="px-4 py-3">
            @if ($ordenesPendientes->isEmpty())
                <div class="alert alert-success text-white text-center">
                    <i class="material-icons opacity-10 me-2">check_circle</i>No hay órdenes de compra pendientes en este momento.
                </div>
            @else
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Orden</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Proveedor</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Productos Solicitados</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de Creación</th>
                                <th class="text-secondary opacity-7">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenesPendientes as $orden)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">#{{ $orden->id }}</p>
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm">{{ $orden->proveedor->nombre ?? 'N/A' }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $orden->proveedor->correo ?? 'No especificado' }}</p>
                                    </td>
                                    <td>
                                        <ul class="list-group list-group-flush">
                                            @foreach ($orden->detalles as $detalle)
                                                <li class="list-group-item px-0 border-0">
                                                    {{ $detalle->producto->nombre ?? 'Producto Eliminado' }} (Cant: {{ $detalle->cantidad }})
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $orden->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-info btn-sm reenviar-email-btn" data-id="{{ $orden->id }}" data-bs-toggle="tooltip" title="Reenviar correo al proveedor">
                                            <i class="material-icons text-sm">send</i> Reenviar Email
                                        </button>
                                        {{-- Botón para confirmar recepción (futura implementación) --}}
                                        <button class="btn btn-success btn-sm confirmar-recepcion-btn" data-id="{{ $orden->id }}" data-bs-toggle="tooltip" title="Confirmar recepción de productos">
                                            <i class="material-icons text-sm">check</i> Confirmar Recepción
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Confirmar Recepción -->
<div class="modal fade" id="confirmarRecepcionModal" tabindex="-1" aria-labelledby="confirmarRecepcionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarRecepcionModalLabel">Confirmar Recepción de Orden #<span id="modalOrdenId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formConfirmarRecepcion">
                <div class="modal-body">
                    <input type="hidden" name="orden_compra_id" id="inputOrdenCompraId">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad Solicitada</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad Recibida</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Precio Unitario de Compra</th>
                                </tr>
                            </thead>
                            <tbody id="productosRecepcionBody">
                                <!-- Los productos se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar Recepción</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    document.querySelectorAll('.reenviar-email-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ordenId = this.dataset.id;
            Swal.fire({
                title: 'Reenviando correo...',
                text: 'Por favor, espere.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/inventario/reenviar-notificacion/${ordenId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
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
            })
            .catch(error => {
                let errorMsg = 'No se pudo reenviar el correo.';
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

    document.querySelectorAll('.confirmar-recepcion-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ordenId = this.dataset.id;
            document.getElementById('modalOrdenId').textContent = ordenId;
            document.getElementById('inputOrdenCompraId').value = ordenId;
            const productosRecepcionBody = document.getElementById('productosRecepcionBody');
            productosRecepcionBody.innerHTML = ''; // Limpiar contenido anterior

            // Obtener detalles de la orden de compra
            fetch(`/inventario/ordenes-pendientes/${ordenId}/detalles`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al obtener detalles de la orden.');
                    }
                    return response.json();
                })
                .then(data => {
                    data.productos.forEach((producto, index) => {
                        const row = `
                            <tr>
                                <td>${producto.nombre_producto}</td>
                                <td>${producto.cantidad_solicitada}</td>
                                <td>
                                    <input type="number" name="productos[${index}][cantidad_recibida]" class="form-control" value="${producto.cantidad_solicitada}" min="0" required>
                                    <input type="hidden" name="productos[${index}][producto_id]" value="${producto.producto_id}">
                                </td>
                                <td>
                                    <input type="number" name="productos[${index}][precio_compra_unitario]" class="form-control" value="${producto.precio_sugerido}" step="0.01" min="0" required>
                                </td>
                            </tr>
                        `;
                        productosRecepcionBody.insertAdjacentHTML('beforeend', row);
                    });
                    var myModal = new bootstrap.Modal(document.getElementById('confirmarRecepcionModal'));
                    myModal.show();
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message,
                    });
                });
        });
    });

    document.getElementById('formConfirmarRecepcion').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        const ordenId = document.getElementById('inputOrdenCompraId').value;
        const formData = new FormData(this);
        const productosData = [];

        // Recopilar los datos de los productos del formulario
        formData.forEach((value, key) => {
            const match = key.match(/productos\[(\d+)\]\[(cantidad_recibida|producto_id|precio_compra_unitario)\]/);
            if (match) {
                const index = parseInt(match[1]);
                const field = match[2];
                if (!productosData[index]) {
                    productosData[index] = {};
                }
                productosData[index][field] = value;
            }
        });

        Swal.fire({
            title: 'Confirmando recepción...',
            text: 'Por favor, espere.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/inventario/confirmar-recepcion/${ordenId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json', // Importante para enviar JSON
                'Accept': 'application/json'
            },
            body: JSON.stringify({ productos: productosData }) // Enviar los productos como JSON
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
            }).then(() => {
                location.reload(); // Recargar la página para reflejar los cambios
            });
        })
        .catch(error => {
            let errorMsg = 'No se pudo confirmar la recepción.';
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
