@extends('layouts.app')

@section('content')

{{-- REPETIMOS EL ESTILO PARA QUE COINCIDA --}}
<style>
    body {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        height: 100vh;
    }
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.9); /* Un poco más opaco para leer mejor */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
</style>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 85vh;">
        <div class="col-md-6">
            
            <div class="card card-glass border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Crear Cuenta</h3>
                        <p class="text-muted small">Únete al sistema de gestión</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold ms-1">{{ __('Nombre') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-person"></i></span>
                                <input id="name" type="text" class="form-control bg-white border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Tu nombre completo">
                            </div>
                            @error('name')
                                <span class="text-danger small mt-1 d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label text-dark small fw-bold ms-1">{{ __('Correo') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control bg-white border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ejemplo@correo.com">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Pass --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark small fw-bold ms-1">{{ __('Contraseña') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-lock"></i></span>
                                    <input id="password" type="password" class="form-control bg-white border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                                </div>
                                @error('password')
                                    <span class="text-danger small mt-1 d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            {{-- Confirm Pass --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label text-dark small fw-bold ms-1">{{ __('Confirmar') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-check2-circle"></i></span>
                                    <input id="password-confirm" type="password" class="form-control bg-white border-start-0 ps-0" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow fw-bold bg-gradient">
                                {{ __('Registrarse') }}
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-dark small mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Ingresa aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection