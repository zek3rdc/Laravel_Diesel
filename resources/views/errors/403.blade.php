@extends('layouts.material')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center mb-0">Acceso Denegado</h5>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-5x text-danger mb-4"></i>
                    <h4>No tienes permiso para acceder a este módulo.</h4>
                    <p class="text-muted">Por favor, contacta al administrador si crees que esto es un error.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Volver al Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
