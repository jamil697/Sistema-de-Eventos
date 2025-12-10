@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- 1. CABECERA CON GRADIENTE --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 text-white d-flex justify-content-between align-items-center bg-gradient-header">
            <div>
                <h1 class="h3 fw-bold mb-1"><i class="bi bi-bell-fill me-2"></i>Centro de Notificaciones</h1>
                <p class="mb-0 opacity-75">Revisa tus últimas actualizaciones y eventos.</p>
            </div>
            
            @if($notifications->isNotEmpty())
                <form action="{{ route('notifications.markAllRead') }}" method="POST"> 
                     @csrf
                     <button class="btn btn-light text-primary fw-bold shadow-sm rounded-pill px-4">
                        <i class="bi bi-check2-all me-1"></i> Marcar todo leído
                     </button>
                </form>
            @endif
        </div>
    </div>

    @forelse($notifications as $n)
        @php 
            $data = $n->data; 
            $isUnread = $n->read_at === null;
        @endphp

        {{-- TARJETA DE NOTIFICACIÓN --}}
        <div class="card mb-3 border-0 shadow-sm notification-card {{ $isUnread ? 'unread-glow' : '' }}">
            <div class="card-body d-flex align-items-center p-3">
                
                {{-- 2. ICONO CON GRADIENTE --}}
                <div class="flex-shrink-0 me-3">
                    <div class="icon-square shadow-sm {{ $isUnread ? 'gradient-icon-new' : 'bg-light text-secondary' }}">
                        <i class="bi bi-bell-fill fs-5"></i>
                    </div>
                </div>

                {{-- 3. CONTENIDO --}}
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 fw-bold {{ $isUnread ? 'text-dark' : 'text-muted' }}">
                            {{ $data['mensaje'] ?? 'Nueva notificación' }}
                        </h6>
                        
                        {{-- FECHA --}}
                        <small class="text-muted" style="font-size: 0.75rem;">
                            {{ $n->created_at->diffForHumans() }}
                        </small>
                    </div>

                    @if(!empty($data['titulo']))
                        <div class="d-flex align-items-center mt-1">
                            <span class="badge bg-light text-primary border border-primary border-opacity-25 me-2">Evento</span>
                            <a href="{{ route('events.show', $data['event_id']) }}" class="text-decoration-none fw-bold text-primary event-link">
                                {{ $data['titulo'] }} <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- 4. PUNTO DE LUZ (PULSE) SOLO SI NO ES LEÍDO --}}
                @if($isUnread)
                    <div class="ms-3">
                        <div class="pulse-dot" title="Nuevo"></div>
                    </div>
                @endif
            </div>
        </div>

    @empty
        {{-- ESTADO VACÍO VIBRANTE --}}
        <div class="text-center py-5">
            <div class="mb-4 position-relative d-inline-block">
                <div class="icon-square gradient-icon-empty shadow" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-inbox"></i>
                </div>
                {{-- Decoración flotante --}}
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                    0
                    <span class="visually-hidden">mensajes</span>
                </span>
            </div>
            <h4 class="fw-bold text-dark">¡Todo limpio!</h4>
            <p class="text-muted">No tienes notificaciones pendientes. Disfruta tu día.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $notifications->links() }} 
    </div>
</div>

<style>
    /* 1. Gradiente del Encabezado */
    .bg-gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* 2. Cuadrado del Icono */
    .icon-square {
        width: 50px;
        height: 50px;
        border-radius: 12px; /* Bordes más redondeados (Squircle) */
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
    }

    /* Icono para NO LEÍDOS (Naranja/Rosado Vibrante) */
    .gradient-icon-new {
        background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
        color: #d63384;
    }

    /* Icono para Empty State (Azul suave) */
    .gradient-icon-empty {
        background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
        color: #0d6efd;
    }

    /* 3. Tarjeta de Notificación */
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .notification-card:hover {
        transform: translateY(-3px); /* Se eleva */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    /* Estilo especial para NO LEÍDOS */
    .unread-glow {
        background-color: #fff;
        border-left: 4px solid #d63384; /* Borde rosa */
        background: linear-gradient(to right, rgba(255, 154, 158, 0.05), transparent); /* Sutil degradado de fondo */
    }

    /* 4. Animación PULSE (El punto que late) */
    .pulse-dot {
        width: 12px;
        height: 12px;
        background-color: #d63384;
        border-radius: 50%;
        position: relative;
    }
    
    .pulse-dot::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #d63384;
        border-radius: 50%;
        animation: pulse-animation 1.5s infinite;
        z-index: -1;
    }

    @keyframes pulse-animation {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(3); opacity: 0; }
    }

    /* Link del evento */
    .event-link:hover {
        text-decoration: underline !important;
    }
</style>
@endsection