@extends('layouts.app')

@section('content')

{{-- ESTILOS PARA LA ANIMACIÓN Y EFECTOS --}}
<style>
    /* Animación de fondo degradado */
    .hero-animated-bg {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Efecto Glassmorphism */
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    /* Texto con gradiente */
    .text-gradient {
        background: linear-gradient(45deg, #1e293b, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Botón con gradiente */
    .bg-gradient-primary {
        background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        transition: all 0.3s ease;
    }
    .bg-gradient-primary:hover {
        filter: brightness(110%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
    }

    /* Iconos con fondo */
    .icon-square {
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .icon-circle {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    /* Tipografía */
    .fw-black { font-weight: 900; }
    .tracking-wide { letter-spacing: 0.5px; }

    /* Efecto Hover sutil */
    .hover-lift { transition: transform 0.2s; }
    .hover-lift:hover { transform: translateY(-3px); }
</style>

<div class="container py-4">

    {{-- 1. ENCABEZADO "HERO" CON IMAGEN Y FONDO ANIMADO --}}
    <div class="position-relative mb-5 rounded-4 overflow-hidden shadow-lg hero-animated-bg p-1"> <div class="bg-white rounded-4 overflow-hidden position-relative">
            @if($event->imagen)
                {{-- Imagen ajustada --}}
                <img src="{{ asset('storage/' . $event->imagen) }}" 
                     alt="{{ $event->titulo }}" 
                     class="w-100" 
                     style="height: 450px; object-fit: cover; object-position: center;">
                {{-- Overlay oscuro sutil en la parte inferior para contraste --}}
                <div class="position-absolute bottom-0 start-0 w-100 h-50" 
                     style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);"></div>
            @else
                {{-- Placeholder Vibrante --}}
                <div class="d-flex align-items-center justify-content-center text-white hero-animated-bg" 
                     style="height: 350px;">
                    <div class="text-center">
                        <i class="bi bi-image display-1 opacity-50 drop-shadow"></i>
                        <p class="mt-2 fw-bold letter-spacing-1 text-uppercase">Vista Previa del Evento</p>
                    </div>
                </div>
            @endif

            {{-- Categoría Flotante (Glassmorphism) --}}
            <div class="position-absolute top-0 end-0 m-4">
                <span class="badge glass-effect text-white shadow px-4 py-2 rounded-pill text-uppercase fw-bold border-0" style="letter-spacing: 1px; font-size: 0.8rem;">
                    {{ $event->categoria->nombre ?? 'General' }}
                </span>
            </div>
        </div>
    </div>


    {{-- 2. LAYOUT DE DOS COLUMNAS --}}
    <div class="row g-5">
        
        {{-- COLUMNA IZQUIERDA: DETALLES Y DESCRIPCIÓN --}}
        <div class="col-lg-8">
            
            {{-- Título con Gradiente --}}
            <h1 class="display-4 fw-black mb-3 text-gradient lh-1">
                {{ $event->titulo }}
            </h1>
            
            {{-- Meta info con iconos coloreados --}}
            <div class="d-flex flex-wrap align-items-center mb-5 text-secondary small fw-bold text-uppercase tracking-wide gap-4">
                <div class="d-flex align-items-center">
                    <span class="icon-circle bg-primary bg-opacity-10 text-primary me-2">
                        <i class="bi bi-shield-check"></i>
                    </span>
                    <span>Organizado por Admin</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="icon-circle bg-info bg-opacity-10 text-info me-2">
                        <i class="bi bi-clock-history"></i>
                    </span>
                    <span>Publicado {{ $event->created_at->diffForHumans() }}</span>
                </div>
            </div>

            @if(session('success')) <div class="alert alert-success shadow-sm border-0 border-start border-4 border-success mb-4 rounded-3"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger mb-4 rounded-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div> @endif
            @if(session('info')) <div class="alert alert-info shadow-sm border-0 border-start border-4 border-info mb-4 rounded-3"><i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}</div> @endif

            {{-- Descripción con estilo "Bloque de Texto" --}}
            <div class="mb-5">
                <h4 class="fw-bold mb-4 d-flex align-items-center text-dark">
                    <span class="bg-primary rounded-pill me-3" style="width: 4px; height: 24px;"></span>
                    Acerca del evento
                </h4>
                
                {{-- Caja decorativa para la descripción --}}
                <div class="p-4 rounded-4 bg-light bg-opacity-50 border position-relative shadow-sm" 
                    style="max-height: 250px; overflow-y: auto;">
                    
                    <div class="lead text-secondary" style="line-height: 1.8; font-size: 1rem;">
                        {!! nl2br(e($event->descripcion)) !!}
                    </div>
                </div>
            </div>

            {{-- Recursos Asignados con diseño de "Inventario" --}}
            @if($event->resources->count())
                <div class="mb-5">
                    <h5 class="fw-bold mb-4 d-flex align-items-center text-dark">
                        <span class="bg-warning rounded-pill me-3" style="width: 4px; height: 24px;"></span>
                        Recursos Disponibles
                    </h5>
                    
                    <div class="row g-3">
                        @foreach($event->resources as $r)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 rounded-4 shadow-sm border h-100 bg-white hover-lift">
                                    <div class="icon-square bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $r->nombre }}</h6>
                                        <span class="badge bg-light text-secondary border rounded-pill">Inventario</span>
                                    </div>
                                    <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">x{{ $r->pivot->cantidad }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


        {{-- COLUMNA DERECHA: SIDEBAR DE ACCIÓN (STICKY) --}}
        <div class="col-lg-4">
            {{-- Card con Borde superior de color --}}
            <div class="card border-0 shadow-lg sticky-top overflow-hidden rounded-4" style="top: 2rem; z-index: 99; border-top: 6px solid #4f46e5;">
                <div class="card-body p-4 p-lg-5">
                    
                    {{-- Fecha y Hora Destacada --}}
                    <div class="text-center pb-4 mb-4 border-bottom">
                        <span class="d-block text-uppercase fw-bold text-primary mb-1 tracking-wide" style="font-size: 0.9rem;">
                            {{ ucfirst(\Carbon\Carbon::parse($event->fecha_inicio)->translatedFormat('F Y')) }}
                        </span>
                        <div class="display-3 fw-black text-dark lh-1 mb-2">
                            {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d') }}
                        </div>
                        <div class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-clock me-1 text-primary"></i> {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('H:i') }} hrs
                        </div>
                        @if($event->fecha_fin)
                            <small class="d-block text-muted mt-3 bg-light rounded px-2 py-1 d-inline-block">
                                <i class="bi bi-arrow-right-short"></i> Hasta: {{ ucfirst(\Carbon\Carbon::parse($event->fecha_fin)->translatedFormat('d M')) }}
                            </small>
                        @endif
                    </div>

                    {{-- Ubicación --}}
                    <div class="mb-4 d-flex align-items-start">
                        <div class="icon-circle bg-danger bg-opacity-10 text-danger me-3 flex-shrink-0">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Ubicación</small>
                            <h6 class="fw-bold mb-1 text-dark">{{ $event->lugar }}</h6>
                            
                            {{-- AQUÍ ESTÁ EL CAMBIO --}}
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->lugar) }}" 
                               target="_blank" 
                               class="text-decoration-none small fw-bold text-primary">
                                Ver en mapa <i class="bi bi-arrow-right-short"></i>
                            </a>
                            
                        </div>
                    </div>

                    {{-- Cupos --}}
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <small class="fw-bold text-muted text-uppercase">Disponibilidad</small>
                            <span class="fw-bold text-primary fs-5">{{ $event->cupo ? ($event->cupo - $inscritosCount) : '∞' }}</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px; background-color: #e2e8f0;">
                            @php $percent = $event->cupo ? ($inscritosCount / $event->cupo) * 100 : 0; @endphp
                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $percent }}%; border-radius: 5px;"></div>
                        </div>
                    </div>

                    {{-- BOTONES (Lógica original mantenida) --}}
                    <div class="d-grid gap-3">
                        @auth
                            @if(auth()->user()->email === env('ADMIN_EMAIL'))
                                <div class="alert alert-warning py-2 text-center mb-0 small fw-bold rounded-3 border-warning">
                                    <i class="bi bi-gear-fill me-1"></i> Panel de Admin
                                </div>
                                @if(auth()->id() === $event->created_by || auth()->user()->email === env('ADMIN_EMAIL'))
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-dark fw-bold w-100 py-2">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Eliminar evento?');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger w-100 fw-bold py-2">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($estaInscrito)
                                    <div class="p-3 rounded-3 bg-success bg-opacity-10 text-success text-center border border-success border-opacity-25">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Inscrito</h5>
                                    </div>
                                    <form action="{{ route('events.unregister', $event) }}" method="POST" onsubmit="return confirm('¿Cancelar?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light text-danger w-100 border fw-bold hover-lift">Cancelar mi cupo</button>
                                    </form>
                                @else
                                    @if($event->cupo && $inscritosCount >= $event->cupo)
                                        <button class="btn btn-secondary w-100 py-3 rounded-3 fw-bold" disabled>
                                            <i class="bi bi-x-circle me-2"></i> Cupos Agotados
                                        </button>
                                    @else
                                        <form action="{{ route('events.register', $event) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-primary btn-lg w-100 shadow-lg fw-bold bg-gradient-primary border-0 py-3 rounded-3">
                                                ¡Inscribirme Ahora!
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                            
                            {{-- Botones extra creador no admin --}}
                            @if(auth()->id() === $event->created_by && auth()->user()->email !== env('ADMIN_EMAIL'))
                                <hr class="my-2">
                                <div class="text-center text-muted small mb-2">Opciones de creador</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-outline-secondary w-100">Editar</a>
                                    </div>
                                    <div class="col-6">
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('¿Eliminar?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger w-100">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 fw-bold shadow py-3 rounded-3 bg-gradient-primary border-0">
                                Inicia sesión para inscribirte
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection