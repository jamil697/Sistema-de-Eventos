@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Botón de volver con estilo --}}
    <div class="mb-4">
        <a href="{{ route('admin.index') }}" class="btn btn-light shadow-sm text-primary fw-bold rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Volver a Usuarios
        </a>
    </div>

    <div class="row g-4">
        
        {{-- COLUMNA IZQUIERDA: PERFIL CON ESTILO --}}
        <div class="col-md-4">
            <div class="card border-0 shadow overflow-hidden h-100">
                
                {{-- 1. PORTADA CON GRADIENTE (NUEVO) --}}
                <div class="profile-cover bg-gradient-primary" style="height: 130px;"></div>

                <div class="card-body text-center position-relative pt-0">
                    
                    {{-- 2. AVATAR SUPERPUESTO --}}
                    <div class="mb-3 mt-n5">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=fff&color=0d6efd&size=128&bold=true" 
                             class="rounded-circle shadow-lg border border-4 border-white" 
                             width="120" height="120"
                             alt="{{ $user->name }}">
                    </div>
                    
                    <h3 class="fw-bold mb-1 text-dark">{{ $user->name }}</h3>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    {{-- Badges con más color --}}
                    <div class="mb-4">
                        @if($user->email === env('ADMIN_EMAIL'))
                            <span class="badge bg-purple-subtle text-purple border border-purple-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-shield-check me-1"></i> Super Admin
                            </span>
                        @else
                            <span class="badge bg-blue-subtle text-blue border border-blue-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-person-check me-1"></i> Ciudadano Verificado
                            </span>
                        @endif
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <a href="mailto:{{ $user->email }}" class="btn btn-primary shadow-sm btn-lg gradient-btn border-0">
                            <i class="bi bi-envelope-paper-fill me-2"></i> Enviar Mensaje
                        </a>
                    </div>

                    <hr class="text-muted opacity-25">

                    {{-- Detalles con Iconos Coloridos --}}
                    <div class="text-start px-2">
                        <small class="text-muted text-uppercase fw-bold d-block mb-3 tracking-wide">Información</small>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-square bg-light text-primary rounded-3 me-3">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Fecha de Registro</small>
                                    <span class="fw-bold text-dark">{{ $user->created_at->format('d M, Y') }}</span>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <div class="icon-square bg-light text-danger rounded-3 me-3">
                                    <i class="bi bi-fingerprint"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">ID de Sistema</small>
                                    <span class="fw-bold text-dark">USER-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </li>
                            <li class="d-flex align-items-center">
                                <div class="icon-square bg-light text-success rounded-3 me-3">
                                    <i class="bi bi-ticket-detailed"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Inscripciones</small>
                                    <span class="fw-bold text-dark">{{ $eventosInscritos->count() }} Eventos</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: TIMELINE DE EVENTOS --}}
        <div class="col-md-8">
            <div class="card border-0 shadow h-100">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-activity me-2"></i> Actividad Reciente
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill">{{ $eventosInscritos->count() }} Registros</span>
                </div>
                
                <div class="card-body p-0">
                    @if($eventosInscritos->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-inbox text-muted fs-1 opacity-25"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">Sin actividad</h5>
                            <p class="text-muted small">El usuario aún no participa en eventos.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($eventosInscritos as $event)
                                <div class="list-group-item p-3 border-light hover-effect">
                                    <div class="d-flex align-items-center">
                                        
                                        {{-- 3. FECHA CON COLOR VIBRANTE --}}
                                        <div class="text-center rounded-3 p-2 me-3 text-white shadow-sm d-flex flex-column justify-content-center" 
                                             style="min-width: 65px; height: 65px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <small class="d-block text-uppercase fw-bold" style="font-size: 0.65rem; opacity: 0.8;">
                                                {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('M') }}
                                            </small>
                                            <span class="d-block h4 fw-bold mb-0">
                                                {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d') }}
                                            </span>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-dark">
                                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark stretched-link">
                                                            {{ $event->titulo }}
                                                        </a>
                                                    </h6>
                                                    <p class="mb-0 text-muted small">
                                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $event->lugar }}
                                                    </p>
                                                </div>
                                                
                                                {{-- Estado --}}
                                                @if(\Carbon\Carbon::parse($event->fecha_inicio)->isPast())
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">Finalizado</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill border border-success border-opacity-25">
                                                        <span class="dot-indicator bg-success me-1"></span> Próximo
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* 1. Fondo Gradiente para el Banner */
    .bg-gradient-primary {
        background: linear-gradient(45deg, #4e54c8, #8f94fb);
    }
    
    /* 2. Ajuste para subir el avatar sobre el banner */
    .mt-n5 { margin-top: -3.5rem !important; }

    /* 3. Colores personalizados tipo Tailwind */
    .bg-purple-subtle { background-color: #f3e8ff; }
    .text-purple { color: #7e22ce; }
    .border-purple-subtle { border-color: #d8b4fe; }

    .bg-blue-subtle { background-color: #dbeafe; }
    .text-blue { color: #1e40af; }
    .border-blue-subtle { border-color: #93c5fd; }

    /* 4. Cuadrados para iconos */
    .icon-square {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* 5. Botón con gradiente */
    .gradient-btn {
        background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
        transition: transform 0.2s;
    }
    .gradient-btn:hover {
        transform: translateY(-2px);
    }

    /* 6. Efecto Hover en la lista */
    .hover-effect {
        transition: background-color 0.2s;
    }
    .hover-effect:hover {
        background-color: #f8f9fa;
    }

    /* 7. Pequeño punto verde para estado */
    .dot-indicator {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        vertical-align: middle;
    }
    
    .tracking-wide { letter-spacing: 1px; }
</style>
@endsection