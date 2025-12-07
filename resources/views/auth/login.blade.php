@extends('layouts.app')

@section('content')

{{-- ESTILOS EXCLUSIVOS PARA ESTA PÁGINA --}}
<style>
    /* 1. Fondo degradado animado */
    body {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        height: 100vh; /* Ocupa toda la pantalla */
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* 2. Tarjeta con efecto "Glass" (Vidrio) */
    .card-glass {
        background: rgba(255, 255, 255, 0.85); /* Blanco semitransparente */
        backdrop-filter: blur(10px);           /* Efecto borroso detrás */
        -webkit-backdrop-filter: blur(10px);   /* Para Safari */
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
</style>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="col-md-5">
            
            {{-- TARJETA GLASSEADA --}}
            <div class="card card-glass border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    
                    {{-- Encabezado --}}
                    <div class="text-center mb-4">
                        <div class="bg-white text-primary rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Bienvenido</h3>
                        <p class="text-muted small">Ingresa a tu cuenta municipal</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold ms-1">{{ __('Email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control bg-white border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@correo.com">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label text-dark small fw-bold ms-1">{{ __('Contraseña') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small text-primary fw-bold" href="{{ route('password.request') }}">
                                        {{ __('¿Olvidaste?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-key"></i></span>
                                <input id="password" type="password" class="form-control bg-white border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Remember --}}
                        <div class="mb-4 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-dark small" for="remember">
                                {{ __('Recordarme') }}
                            </label>
                        </div>

                        {{-- Botón --}}
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow fw-bold bg-gradient">
                                {{ __('Ingresar') }} <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </div>

                        {{-- Footer --}}
                        <div class="text-center">
                            <p class="text-dark small mb-0">¿Nuevo aquí? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Crear cuenta</a></p>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection