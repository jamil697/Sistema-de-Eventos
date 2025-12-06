@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mis eventos inscritos</h1>

    @if($eventos->isEmpty())
        <p>No estás inscrito en ningún evento.</p>
    @else
        @foreach($eventos as $event)
            <div class="card mb-2">
                <div class="card-body">
                    <h5><a href="{{ route('events.show', $event) }}">{{ $event->titulo }}</a></h5>
                    <p>{{ \Str::limit($event->descripcion, 150) }}</p>
                    <small>
                        Fecha: {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d/m/Y H:i') }}<br>
                        Lugar: {{ $event->lugar }}
                    </small>

                    <form action="{{ route('events.unregister', $event) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Cancelar inscripción</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection