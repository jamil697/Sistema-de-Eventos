@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'ciudadano')
    <div class="container">
        <h2>Detalle de Mi Registro</h2>

        <a href="{{ route('ciudadano.inscripciones.lista') }}" class="btn btn-secondary mb-3">
            ← Volver a Mis Eventos Registrados
        </a>

        @php
            // La variable $inscripcion contiene la inscripción y su evento asociado.
            $evento = $inscripcion->evento;
            $badgeClass = ($inscripcion->estado === 'registrado') ? 'warning' : 'success';
        @endphp

        <div class="card mt-3 border-info">
            <div class="card-header bg-info text-white">
                <h4>{{ $evento->titulo }}</h4>
            </div>
            <div class="card-body">
                
                <h5 class="mb-3">Información del Evento</h5>
                <dl class="row">
                    <dt class="col-sm-3">Fecha del Evento:</dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($evento->fechaInicio)->format('d/m/Y') }} 
                        @if($evento->fechaInicio != $evento->fechaFin)
                            al {{ \Carbon\Carbon::parse($evento->fechaFin)->format('d/m/Y') }}
                        @endif
                    </dd>

                    <dt class="col-sm-3">Ubicación:</dt>
                    <dd class="col-sm-9">{{ $evento->ubicacion }}</dd>

                    <dt class="col-sm-3">Categoría:</dt>
                    <dd class="col-sm-9">{{ $evento->tipo }}</dd>
                    
                    <dt class="col-sm-3">Costo:</dt>
                    <dd class="col-sm-9">{{ $evento->esDePago ? '$' . number_format($evento->costo, 2) : 'Gratuito' }}</dd>
                </dl>
                
                <p><strong>Descripción:</strong> {{ $evento->descripcion ?? 'No hay descripción disponible para este evento.' }}</p>

                <hr>

                <h5 class="mb-3">Detalles de Mi Inscripción</h5>
                <dl class="row">
                    <dt class="col-sm-3">Registrado por:</dt>
                    <dd class="col-sm-9">{{ Auth::user()->name }} (ID: {{ $inscripcion->user_id }})</dd>
                    
                    <dt class="col-sm-3">Fecha de Registro:</dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Estado de la Inscripción:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $badgeClass }} fs-6">
                            {{ ucfirst($inscripcion->estado) }}
                        </span>
                    </dd>
                    
                    {{-- Si tuvieras campos adicionales en Inscripcion, irían aquí --}}
                    {{-- <dt class="col-sm-3">Comentario/Notas:</dt>
                    <dd class="col-sm-9">{{ $inscripcion->comentario ?? 'Ninguno' }}</dd> --}}
                </dl>

                {{-- El botón "Inscribirme" ya no tiene sentido aquí, pero podemos poner una acción relevante --}}
                {{-- <a href="#" class="btn btn-danger">Cancelar Inscripción (Requiere lógica)</a> --}}
            </div>
        </div>
    </div>
@else
    <div class="container"><div class="alert alert-danger">Acceso Denegado. Esta área es exclusiva para ciudadanos.</div></div>
@endif
@endsection