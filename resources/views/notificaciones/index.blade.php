@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notificaciones</h1>

    @if($notifications->isEmpty())
        <p>No tienes notificaciones.</p>
    @else
        <ul class="list-group">
            @foreach($notifications as $n)
                @php $data = $n->data; @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $data['mensaje'] ?? 'Notificación' }}</strong><br>
                        @if(!empty($data['titulo']))
                            Evento: <a href="{{ route('events.show', $data['event_id']) }}">{{ $data['titulo'] }}</a><br>
                        @endif
                        <small>Fecha: {{ $data['fecha'] ?? $n->created_at }}</small>
                    </div>
                    @if($n->read_at === null)
                        <span class="badge bg-primary">Nuevo</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection