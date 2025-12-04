@extends('layouts.app')

@section('content')

@if(Auth::user()->role === 'admin')
    <div class="container">
        <h2>Gestión de Eventos</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <h4>Lista de todos los Eventos</h4>
            {{-- Botón para Registrar otro evento --}}
            <a class="btn btn-success" href="{{ route('eventos.create') }}">Registrar Evento</a>
        </div>

        @if($eventos->isEmpty())
            <div class="alert alert-info">
                No hay eventos registrados en el sistema.
            </div>
        @else
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fechas</th>
                        <th>Estado</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($eventos as $evento)
                    <tr>
                        <td>{{ $evento->titulo }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($evento->fechaInicio)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($evento->fechaFin)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{-- Estilo visual según el estado --}}
                            <span class="badge bg-{{ $evento->estado == 'activo' ? 'success' : 'secondary' }}">
                                {{ ucfirst($evento->estado) }}
                            </span>
                        </td>
                        <td>{{ $evento->capacidad }}</td>
                        <td style="width: 250px;">
                            {{-- Ver Detalles (show) --}}
                            <a class="btn btn-info btn-sm" href="{{ route('eventos.show', $evento->id) }}">
                                Ver
                            </a>
                            {{-- Editar --}}
                            <a class="btn btn-warning btn-sm" href="{{ route('eventos.edit', $evento->id) }}">
                                Editar
                            </a>

                            {{-- Eliminar --}}
                            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@else
    {{-- Mensaje de advertencia si un usuario logueado que NO es admin intenta acceder --}}
    <div class="container">
        <div class="alert alert-danger">
            Acceso Denegado. No tienes permisos de administrador.
        </div>
    </div>
@endif
@endsection