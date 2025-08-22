@if(isset($datosParaConfirmacion) && count($datosParaConfirmacion) > 0)
    @foreach($datosParaConfirmacion as $index => $orden)
        <div class="card mb-4" id="orden-compra-{{ $orden->id }}">
            <div class="card-header">
                <h5 class="text-capitalize">Orden de Compra #{{ $orden->id }} para: {{ $orden->proveedor->nombre ?? 'N/A' }}</h5>
                <p class="text-sm">Los siguientes productos han sido solicitados. Confirme la compra para actualizar el stock.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Producto</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cantidad Solicitada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orden->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</td>
                                    <td class="text-center">{{ $detalle->cantidad }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-success btn-sm confirmar-recepcion-btn" data-id="{{ $orden->id }}" data-bs-toggle="tooltip" title="Confirmar recepción de productos">
                        <i class="material-icons text-sm">check</i> Confirmar Recepción
                    </button>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-warning text-white">
        No hay datos de confirmación disponibles. Por favor, complete los pasos anteriores.
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Re-initialize tooltips for newly loaded content
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    document.querySelectorAll('.confirmar-recepcion-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ordenId = this.dataset.id;
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
                // Opcional: Eliminar la tarjeta de la orden de compra de la vista
                document.getElementById(`orden-compra-${ordenId}`).remove();
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
});
</script>
