@extends('layouts.material')

@section('title', 'Detalle de Venta')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalle de Venta #{{ $venta->id }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'N/A' }}</p>
                <p><strong>Empleado:</strong> {{ $venta->empleado->nombre ?? 'N/A' }}</p>
                <p><strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y H:i') }}</p>
                <p><strong>Método de Pago:</strong> {{ ucfirst($venta->metodo_pago) }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Nro. Factura:</strong> {{ $venta->nro_factura }}</p>
                <p><strong>Estado:</strong> <span class="badge bg-success">{{ ucfirst($venta->estado) }}</span></p>
                <p><strong>Impuesto (16%):</strong> ${{ number_format($venta->impuesto, 2) }}</p>
                @php
                    $totalCalculado = $venta->detalleVentas->sum(function($detalle) {
                        return $detalle->cantidad * $detalle->precio_venta_unitario;
                    });
                @endphp
                <p><strong>Total:</strong> ${{ number_format($totalCalculado, 2) }}</p>
            </div>
        </div>

        <h4 class="mt-4">Productos</h4>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalleVentas as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio_venta_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_venta_unitario, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver al Historial</a>
    </div>
</div>
@endsection
