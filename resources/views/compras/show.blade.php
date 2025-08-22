@extends('layouts.material')

@section('title', 'Detalle de Compra')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detalle de Compra #{{ $compra->id }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre }}</p>
                <p><strong>Fecha:</strong> {{ $compra->fecha_compra->format('d/m/Y H:i') }}</p>
                <p><strong>Nro. Factura Proveedor:</strong> {{ $compra->nro_factura_proveedor }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Estado:</strong> <span class="badge bg-success">{{ ucfirst($compra->estado) }}</span></p>
                @php
                    $totalCalculado = $compra->detalleCompras->sum(function($detalle) {
                        return $detalle->cantidad * $detalle->precio_compra_unitario;
                    });
                @endphp
                <p><strong>Total:</strong> ${{ number_format($totalCalculado, 2) }}</p>
            </div>
        </div>
        @if($compra->observaciones)
            <div class="row mt-3">
                <div class="col-12">
                    <p><strong>Observaciones:</strong></p>
                    <p>{{ $compra->observaciones }}</p>
                </div>
            </div>
        @endif

        <h4 class="mt-4">Productos</h4>
        <table class="table-responsive">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compra->detalleCompras as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->precio_compra_unitario, 2) }}</td>
                        <td>${{ number_format($detalle->cantidad * $detalle->precio_compra_unitario, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver al Historial</a>
    </div>
</div>
@endsection
