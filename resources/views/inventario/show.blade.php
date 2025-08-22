@extends('layouts.material')

@section('title', 'Detalle del Producto')

@section('content_header')
    <h1>Detalle del Producto: {{ $producto->nombre }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información del Producto</h3>
            <div class="card-tools">
                <a href="{{ route('inventario.custom_edit', $producto) }}" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar este producto">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('inventario.destroy', $producto) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este producto?')" data-toggle="tooltip" title="Eliminar este producto">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if ($producto->foto_url)
                        <img src="{{ asset('storage/product_images/' . $producto->foto_url) }}" class="img-fluid rounded" alt="{{ $producto->nombre }}" style="max-height: 300px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 300px;">
                            <span class="text-muted">No hay foto disponible.</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $producto->id }}</dd>

                        <dt class="col-sm-4">Nombre:</dt>
                        <dd class="col-sm-8">{{ $producto->nombre }}</dd>

                        <dt class="col-sm-4">Descripción:</dt>
                        <dd class="col-sm-8">{{ $producto->descripcion ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Código SKU:</dt>
                        <dd class="col-sm-8">{{ $producto->codigo_sku ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Precio de Venta:</dt>
                        <dd class="col-sm-8">{{ number_format($producto->precio_venta, 2) }}</dd>

                        <dt class="col-sm-4">Stock Actual:</dt>
                        <dd class="col-sm-8">{{ $producto->stock_actual }}</dd>

                        <dt class="col-sm-4">Stock Mínimo:</dt>
                        <dd class="col-sm-8">{{ $producto->stock_minimo }}</dd>

                        <dt class="col-sm-4">Stock Máximo:</dt>
                        <dd class="col-sm-8">{{ $producto->stock_maximo }}</dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            @if ($producto->estado)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Fecha de Vencimiento:</dt>
                        <dd class="col-sm-8">{{ $producto->fecha_vencimiento ? $producto->fecha_vencimiento->format('d/m/Y') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Subcategoría:</dt>
                        <dd class="col-sm-8">{{ $producto->subcategoria->nombre ?? 'N/A' }} ({{ $producto->subcategoria->categoria->nombre ?? 'N/A' }})</dd>

                        <dt class="col-sm-4">Proveedor:</dt>
                        <dd class="col-sm-8">{{ $producto->proveedor->nombre ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Modelos:</dt>
                        <dd class="col-sm-8">
                            @forelse ($producto->modelos as $modelo)
                                <span class="badge badge-info">{{ $modelo->nombre }} ({{ $modelo->marca->nombre ?? 'N/A' }})</span>
                            @empty
                                N/A
                            @endforelse
                        </dd>

                        <dt class="col-sm-4">Creado el:</dt>
                        <dd class="col-sm-8">{{ $producto->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Última Actualización:</dt>
                        <dd class="col-sm-8">{{ $producto->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Volver al Inventario</a>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inicializar tooltips de Bootstrap
            $('[data-toggle="tooltip"]').tooltip();
            console.log('Vista de detalle de producto cargada!');
        });
    </script>
@stop
