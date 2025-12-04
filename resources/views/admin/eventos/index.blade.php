@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Eventos Disponibles</h2>

    @if($eventos->count() === 0)
        <div class="alert alert-info">
            No hay eventos publicados por el momento.
        </div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Fechas</th>
                    <th>Ubicación</th>
                    <th>Categoría</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($eventos as $evento)
                <tr>
                    <td>{{ $evento->titulo }}</td>
                    <td>
                        {{ $evento->fechaInicio }} <br>
                        <small>a</small> <br>
                        {{ $evento->fechaFin }}
                    </td>
                    <td>{{ $evento->ubicacion }}</td>
                    <td>{{ $evento->tipo }}</td>
                    <td>
                        <a class="btn btn-success btn-sm"
                           href="{{ route('ciudadano.inscripcion', ['evento' => $evento->id]) }}">
                           Inscribirse
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
