@extends('layouts.app')

@section('content')

{{-- ESTILOS PARA LA ANIMACIÓN DEL BANNER --}}
<style>
    .hero-animated {
        /* Los mismos colores vibrantes del Login */
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        color: white;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Efecto cristal para el botón dentro del banner */
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: white;
        transition: all 0.3s;
    }
    .btn-glass:hover {
        background: rgba(255, 255, 255, 0.4);
        color: white;
        transform: scale(1.05);
    }
</style>

<div class="container py-4">

    {{-- 1. HERO SECTION ANIMADO (AQUÍ ESTÁ LA MAGIA) --}}
    <div class="position-relative p-5 mb-5 rounded-4 shadow-lg overflow-hidden hero-animated">
        <div class="position-relative z-1">
            <h1 class="display-4 fw-bold mb-2">Descubre Experiencias</h1>
            <p class="lead mb-4 fw-normal opacity-90" style="max-width: 600px; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                Explora los mejores eventos culturales, deportivos y corporativos de la municipalidad.
            </p>
            
            @auth
                @if(auth()->user()->email === env('ADMIN_EMAIL'))
                    <a href="{{ route('events.create') }}" class="btn btn-glass fw-bold rounded-pill px-4 py-2 shadow-sm">
                        <i class="bi bi-plus-circle-fill me-2"></i> Nuevo Evento
                    </a>
                @endif
            @endauth
        </div>
        
        {{-- Decoración de fondo sutil --}}
        <div class="position-absolute top-0 end-0 translate-middle-y opacity-25" style="margin-right: -50px; transform: rotate(-15deg);">
            <i class="bi bi-ticket-perforated-fill" style="font-size: 18rem; color: rgba(255,255,255,0.2);"></i>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 shadow-sm mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div> @endif
    
    {{-- 2. BARRA DE FILTROS LIMPIA --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
            <span class="bg-primary rounded-pill me-2" style="width: 4px; height: 20px;"></span>
            Próximos Eventos
        </h5>

        <form method="GET" action="{{ url('/') }}" class="d-flex align-items-center">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border-0" style="background-color: #f8f9fa;">
                <span class="input-group-text border-0 ps-3 text-muted bg-transparent"><i class="bi bi-funnel-fill"></i></span>
                <select name="categoria" class="form-select border-0 bg-transparent fw-bold text-secondary" style="min-width: 200px; cursor: pointer; focus:box-shadow-none;" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ (isset($categoriaId) && $categoriaId == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            @if(!empty($categoriaId))
                <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted ms-2" title="Limpiar filtro">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- PAGINACIÓN SUPERIOR (Alineada a la derecha) --}}
    <div class="d-flex justify-content-end mb-4">
        {{ $events->links('vendor.pagination.modern') }}
    </div>

    {{-- 3. GRID DE EVENTOS MODERNOS --}}
    <div class="row g-4">
        @forelse($events as $event)
            <div class="col-12 col-sm-6 col-lg-3"> 
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-lift">
                    
                    {{-- IMAGEN CON BADGES --}}
                    <div class="position-relative">
                        <div style="height: 200px; overflow: hidden;">
                            @if($event->imagen)
                                <a href="{{ route('events.show', $event) }}">
                                    <img src="{{ asset('storage/' . $event->imagen) }}" 
                                         alt="{{ $event->titulo }}" 
                                         class="w-100 h-100 object-fit-cover transition-zoom">
                                </a>
                            @else
                                <a href="{{ route('events.show', $event) }}" class="d-block w-100 h-100 bg-light d-flex align-items-center justify-content-center text-decoration-none">
                                    <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                                </a>
                            @endif
                        </div>

                        {{-- FECHA FLOTANTE (CALENDARIO) --}}
                        <div class="position-absolute top-0 start-0 m-3 bg-white rounded-3 text-center shadow-sm p-2" style="min-width: 50px; line-height: 1;">
                            <span class="d-block text-uppercase fw-bold text-danger" style="font-size: 0.65rem;">
                                {{-- CAMBIO AQUÍ: Usamos translatedFormat --}}
                                {{ \Carbon\Carbon::parse($event->fecha_inicio)->translatedFormat('M') }}
                            </span>
                            <span class="d-block h5 fw-bold text-dark mb-0">
                                {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d') }}
                            </span>
                        </div>

                        {{-- BADGE CATEGORÍA --}}
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-dark bg-opacity-75 backdrop-blur rounded-pill fw-normal">
                                {{ $event->categoria->nombre ?? 'General' }}
                            </span>
                        </div>
                    </div>

                    {{-- CUERPO --}}
                    <div class="card-body p-3 d-flex flex-column">
                        <h5 class="card-title fw-bold mb-2 lh-sm">
                            <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark stretched-link">
                                {{ Str::limit($event->titulo, 45) }}
                            </a>
                        </h5>

                        <p class="card-text small text-muted mb-3 flex-grow-1">
                            {{ Str::limit($event->descripcion, 70) }}
                        </p>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-light mt-auto">
                            <div class="d-flex align-items-center text-secondary small">
                                <i class="bi bi-geo-alt-fill me-1 text-primary"></i>
                                <span class="text-truncate" style="max-width: 120px;">{{ $event->lugar }}</span>
                            </div>
                            
                            <small class="text-primary fw-bold">
                                Ver <i class="bi bi-arrow-right"></i>
                            </small>
                        </div>
                    </div>

                    {{-- ACCIONES DE ADMIN --}}
                    @auth
                        @if(auth()->user()->email === env('ADMIN_EMAIL'))
                            <div class="card-footer bg-light border-0 p-2 text-center position-relative z-2">
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-link text-decoration-none text-secondary p-0 me-3">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar evento?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-link text-decoration-none text-danger p-0">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-search text-muted fs-1 opacity-25"></i>
                        </div>
                    </div>
                    <h4 class="text-muted fw-bold">No se encontraron eventos</h4>
                    <p class="text-muted small">Intenta cambiar el filtro o vuelve más tarde.</p>
                    @if(!empty($categoriaId))
                        <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill btn-sm mt-2">Limpiar filtros</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{-- Llamamos a nuestra vista personalizada 'modern' --}}
        {{ $events->links('vendor.pagination.modern') }}
    </div>
</div>

<style>
    /* Efecto de elevación al pasar el mouse */
    .card-hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    /* Zoom suave en la imagen */
    .transition-zoom {
        transition: transform 0.5s ease;
    }
    .card-hover-lift:hover .transition-zoom {
        transform: scale(1.05);
    }
    
    /* Utilidad para blur */
    .backdrop-blur {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    .object-fit-cover { object-fit: cover; }
</style>
@endsection