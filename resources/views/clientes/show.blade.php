@extends('layouts.material')

@section('title', 'Detalles del Cliente')

@section('css')
    <!-- Force-load Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
@endsection

@section('content')
    <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3"><i class="material-icons opacity-10 me-2">person</i>Detalles del Cliente</h6>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="px-4 py-3">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-bold">Cédula:</p>
                        <p>{{ $cliente->cedula }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-bold">Nombre Completo:</p>
                        <p>{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-bold">Correo Electrónico:</p>
                        <p>{{ $cliente->correo ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-bold">Teléfono:</p>
                        <p>{{ $cliente->telefono ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-bold">Dirección:</p>
                        <p>{{ $cliente->direccion ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">Editar Cliente</a>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary ms-2">Volver al Listado</a>
                </div>
            </div>
        </div>
    </div>
@endsection
