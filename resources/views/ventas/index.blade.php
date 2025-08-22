@extends('layouts.material')

@section('title', 'Historial de Ventas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Historial de Ventas</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('ventas.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nro_factura" class="form-control" placeholder="Buscar por Nro. Factura" value="{{ $nro_factura ?? '' }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ $fecha_inicio ?? '' }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="fecha_fin" class="form-control" value="{{ $fecha_fin ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        @if($ventas->isEmpty())
            <p>No se encontraron ventas con los criterios de búsqueda.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                        <th>Nro. Factura</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id }}</td>
                            <td>{{ $venta->nro_factura }}</td>
                            <td>{{ $venta->cliente->nombre }}</td>
                            <td>{{ $venta->empleado->nombre ?? 'N/A' }}</td>
                            <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                            <td>${{ number_format($venta->total, 2) }}</td>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    <div class="card-footer">
        {{ $ventas->appends(request()->except('page'))->links('vendor.pagination.bootstrap-5') }}
    </div>
</div>
@endsection
