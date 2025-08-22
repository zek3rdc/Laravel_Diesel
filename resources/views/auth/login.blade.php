<x-guest-layout>
    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/login/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @endsection

    <div class="login-wrapper">
        <!-- Info Panel (Left Side) -->
        <div class="info-panel">
            <div class="floating-shapes">
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
                <div class="shape"></div>
            </div>
            <div class="logo-icon">
                <i class="fas fa-gas-pump"></i>
            </div>
            <h1>Pacheco Diesel</h1>
            <p>Gestión de servicios y control de calidad a tu alcance.</p>
        </div>

        <!-- Login Panel (Right Side) -->
        <div class="login-panel">
            <div class="login-card">
                <!-- Header -->
                <div class="login-header">
                    <h1 class="login-title">Bienvenido de Nuevo</h1>
                    <p class="login-subtitle">Ingresa a tu cuenta para continuar</p>
                </div>

                <!-- Body -->
                <div class="login-body">
                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-3" />

                    <!-- Session Status -->
                    @session('status')
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ $value }}
                        </div>
                    @endsession

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf

                        <!-- Email Field -->
                        <div class="input-group">
                            <i class="input-icon fas fa-user"></i>
                            <input type="email" class="input-field @error('email') is-invalid @enderror" id="email" name="email"
                                   placeholder="Correo Electrónico" required autocomplete="username" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="input-group password-field-container">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" class="input-field @error('password') is-invalid @enderror" id="password" name="password"
                                   placeholder="Contraseña" required autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="remember-section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    Recordarme
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-password">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span id="loginBtnText">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="login-footer">
                    <a href="/" class="back-home">
                        <i class="fas fa-arrow-left"></i>
                        Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script src="{{ asset('js/login/login.js') }}"></script>
    @endsection
</x-guest-layout>
