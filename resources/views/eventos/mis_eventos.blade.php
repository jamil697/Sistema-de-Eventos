@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 fw-bold text-dark mb-0">Mis Eventos</h1>
            <p class="text-muted small">Gestiona tus próximas asistencias</p>
        </div>
        <span class="badge bg-white text-dark border rounded-pill px-3 py-2 shadow-sm">
            Total: {{ $eventos->count() }}
        </span>
    </div>

    @if($eventos->isEmpty())
        {{-- ESTADO VACÍO --}}
        <div class="text-center py-5">
            <div class="mb-3">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                    <i class="bi bi-calendar-plus text-primary display-4 opacity-50"></i>
                </div>
            </div>
            <h3 class="h5 fw-bold text-dark">Tu agenda está vacía</h3>
            <p class="text-muted mb-4">Parece que no tienes planes próximos.</p>
            <a href="{{ route('events.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                Descubrir Eventos
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($eventos as $event)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden card-hover-effect">
                        
                        {{-- 1. IMAGEN DE PORTADA --}}
                        <div class="position-relative">
                            <div style="height: 180px;">
                                @if($event->imagen)
                                    <img src="{{ asset('storage/' . $event->imagen) }}" 
                                         class="w-100 h-100" 
                                         alt="{{ $event->titulo }}" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="w-100 h-100 bg-gradient-dark d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-event text-white fs-1 opacity-25"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Gradiente para la fecha --}}
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" 
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <div class="text-white">
                                    <small class="fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem; opacity: 0.8;">
                                        {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('M') }}
                                    </small>
                                    <h4 class="fw-bold mb-0 lh-1">
                                        {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d') }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        {{-- 2. CUERPO --}}
                        <div class="card-body p-3 d-flex flex-column bg-white">
                            
                            <h5 class="fw-bold text-dark mb-1 text-truncate">
                                <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                    {{ $event->titulo }}
                                </a>
                            </h5>
                            
                            <p class="text-muted small mb-3">
                                <i class="bi bi-geo-alt me-1 text-secondary"></i> {{ Str::limit($event->lugar, 25) }}
                            </p>

                            {{-- 3. SOLO UN BOTÓN GRANDE --}}
                            <div class="mt-auto">
                                <a href="{{ route('events.show', $event) }}" 
                                   class="btn btn-dark w-100 rounded-3 fw-bold shadow-sm py-2"
                                   style="background-color: #2c3e50; border-color: #2c3e50;">
                                    <i class="bi bi-ticket-detailed me-2"></i> Ver Evento
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Fondo oscuro para placeholder */
    .bg-gradient-dark {
        background: linear-gradient(45deg, #232526, #414345);
    }

    /* Efecto Hover Tarjeta */
    .card-hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection