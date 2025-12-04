@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->titulo }}</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif

    <p>{{ $event->descripcion }}</p>
    <p><strong>Lugar:</strong> {{ $event->lugar }}</p>
    <p><strong>Fecha inicio:</strong> {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d/m/Y H:i') }}</p>
    @if($event->fecha_fin)
        <p><strong>Fecha fin:</strong> {{ \Carbon\Carbon::parse($event->fecha_fin)->format('d/m/Y H:i') }}</p>
    @endif
    <p><strong>Inscritos:</strong> {{ $inscritosCount }} @if($event->cupo) / Cupo: {{ $event->cupo }} @endif</p>

    <h5>Recursos asignados</h5>
    @if($event->resources->count())
        <ul>
            @foreach($event->resources as $r)
                <li>{{ $r->nombre }} — Cantidad asignada: {{ $r->pivot->cantidad }}</li>
            @endforeach
        </ul>
    @else
        <p>No se asignaron recursos.</p>
    @endif

    {{-- Zona de acciones según rol --}}
    @auth
        {{-- SI ES ADMIN: solo gestiona (editar/eliminar), no se inscribe --}}
        @if(auth()->user()->email === env('ADMIN_EMAIL'))
            <div class="mt-3">
                {{-- Admin (o creador) puede editar/eliminar --}}
                @if(auth()->id() === $event->created_by || auth()->user()->email === env('ADMIN_EMAIL'))
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary">Editar evento</a>

                    <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar evento?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Eliminar evento</button>
                    </form>
                @endif
            </div>

            <p class="text-muted mt-3">
                Eres administrador. Los administradores gestionan los eventos, no se inscriben.
            </p>

        {{-- SI NO ES ADMIN: comportamiento de ciudadano --}}
        @else
            {{-- Inscribirse / cancelar inscripción --}}
            @if($estaInscrito)
                <form action="{{ route('events.unregister', $event) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar tu inscripción?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-warning">Cancelar inscripción</button>
                </form>
            @else
                <form action="{{ route('events.register', $event) }}" method="POST">
                    @csrf
                    <button class="btn btn-success mt-2">Inscribirme</button>
                </form>
            @endif

            {{-- Si el usuario también es creador del evento, puede editar/eliminar --}}
            @if(auth()->id() === $event->created_by)
                <div class="mt-3">
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary">Editar evento</a>

                    <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Eliminar evento?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Eliminar evento</button>
                    </form>
                </div>
            @endif

            {{-- Link a "Mis eventos" solo para ciudadanos --}}
            <a href="{{ route('mis-eventos') }}" class="btn btn-outline-primary btn-sm mt-3">Ver mis eventos</a>
        @endif

    @else
        {{-- Invitación a iniciar sesión si es visitante --}}
        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2">Inicia sesión para inscribirte</a>
    @endauth
</div>
@endsection