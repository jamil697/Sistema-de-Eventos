@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Eventos</h1>

    {{-- Mostrar botón SOLO si el usuario está autenticado y su email es el admin --}}
    @auth
        @if(auth()->user()->email === env('ADMIN_EMAIL'))
            <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">Crear evento</a>
        @endif
    @endauth

    {{-- DEBUG TEMPORAL: muestra email y ADMIN_EMAIL (quita luego) --}}
    {{-- @auth
        <div class="mb-3">
            <small>DEBUG - tu email: <strong>{{ auth()->user()->email }}</strong> — ADMIN_EMAIL: <strong>{{ env('ADMIN_EMAIL') }}</strong></small>
        </div>
    @endauth --}}

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

    @forelse($events as $event)
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title"><a href="{{ route('events.show', $event) }}">{{ $event->titulo }}</a></h5>
                <p class="card-text">{{ Str::limit($event->descripcion, 150) }}</p>
                <p class="card-text"><small>Fecha: {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d/m/Y H:i') }} — Lugar: {{ $event->lugar }}</small></p>
                <p class="card-text"><small>Inscritos: {{ $event->registrations_count ?? 0 }} @if($event->cupo) / Cupo: {{ $event->cupo }} @endif</small></p>

                @auth
                    {{-- Mostrar acciones admin en cada tarjeta (opcional) --}}
                    @if(auth()->user()->email === env('ADMIN_EMAIL'))
                        <div class="mt-2">
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-secondary">Editar</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminar evento?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </div>
                    @endif
                @endauth

            </div>
        </div>
    @empty
        <p>No hay eventos disponibles.</p>
    @endforelse

    <div class="mt-3">
        {{ $events->links() }}
    </div>
</div>
@endsection
