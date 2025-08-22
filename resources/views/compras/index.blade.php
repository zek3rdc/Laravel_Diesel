@extends('layouts.material')

@section('title', 'Historial de Compras')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Historial de Compras</h3>
    </div>
    <div class="card-body">
        @if($compras->isEmpty())
            <p>No hay compras registradas.</p>
        @else
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compras as $compra)
                        <tr>
                            <td>{{ $compra->id }}</td>
                            <td>{{ $compra->proveedor->nombre }}</td>
                            <td>{{ $compra->fecha_compra }}</td>
                            <td>${{ number_format($compra->total, 2) }}</td>
                            <td>
                                <a href="{{ route('compras.show', $compra) }}" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="card-footer">
        {{ $compras->links() }}
    </div>
</div>
@endsection
