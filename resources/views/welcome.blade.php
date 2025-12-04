@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Eventos Disponibles para Inscripción</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($eventos->isEmpty())
        <div class="alert alert-info">
            No hay eventos publicados por el momento.
        </div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Fechas</th>
                    <th>Ubicación</th>
                    <th>Costo</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                @foreach($eventos as $evento)
                <tr>
                    <td>{{ $evento->titulo }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($evento->fechaInicio)->format('d/m/Y') }} <br>
                        <small>al</small> <br>
                        {{ \Carbon\Carbon::parse($evento->fechaFin)->format('d/m/Y') }}
                    </td>
                    <td>{{ $evento->ubicacion }}</td>
                    <td>{{ $evento->costo > 0 ? '$' . number_format($evento->costo, 2) : 'Gratuito' }}</td>
                    <td>
                        {{-- El botón debe dirigir al login/registro si el usuario NO está logueado,
                             o al proceso de inscripción si SÍ lo está. --}}
                        @auth
                            {{-- Si el usuario está autenticado, dirigimos a la ruta de inscripción --}}
                            <form action="{{ route('inscripcion.create', ['evento' => $evento->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    Inscribirse
                                </button>
                            </form>
                        @else
                            {{-- Si NO está autenticado, dirigimos al login --}}
                            <a class="btn btn-primary btn-sm" href="{{ route('login') }}">
                                ¡Inscríbete! (Login)
                            </a>
                        @endauth
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection