@extends('layouts.app')

@section('content')
{{-- Verificación de rol: Mantiene la seguridad en la vista --}}
@if(Auth::user()->role === 'ciudadano')
    <div class="container">
        <h2>Mis Eventos Registrados</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- 1. Verificar si hay inscripciones --}}
        @if($inscripciones->isEmpty())
            <div class="alert alert-info mt-3">
                Aún no estás inscrito en ningún evento. ¡Explora los <a href="{{ route('eventos.publicos') }}">eventos disponibles</a>!
            </div>
        @else
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Fecha Inscripción</th>
                        <th>Fecha Evento</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- 2. Iterar sobre las inscripciones del usuario --}}
                    @foreach($inscripciones as $inscripcion)
                    <tr>
                        {{-- Accedemos al evento a través de la relación 'evento' --}}
                        <td>
                            <strong>{{ $inscripcion->evento->titulo }}</strong>
                            <br><small>{{ $inscripcion->evento->tipo }}</small>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($inscripcion->evento->fechaInicio)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{-- Muestra el estado de la inscripción --}}
                            @php
                                $badgeClass = ($inscripcion->estado === 'registrado') ? 'warning' : 'success';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ ucfirst($inscripcion->estado) }}
                            </span>
                        </td>
                        <td>
                            {{-- 3. Botón para ver los detalles de la inscripción --}}
                            <a class="btn btn-info btn-sm"
                               href="{{ route('ciudadano.inscripciones.show', $inscripcion->id) }}">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@else
    {{-- Mensaje para usuarios logueados que no son 'ciudadano' --}}
    <div class="container"><div class="alert alert-danger">Acceso Denegado. Esta área es exclusiva para ciudadanos.</div></div>
@endif
@endsection