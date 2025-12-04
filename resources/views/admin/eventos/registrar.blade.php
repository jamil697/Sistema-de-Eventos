@extends('layouts.app')

@section('content')

@if(Auth::user()->role === 'admin')
    <div class="container">
        <h2>Registrar Nuevo Evento</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('eventos.store') }}" method="POST">
            @csrf

            <div class="mb-3">

                <label for="titulo" class="form-label">Nombre del evento</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
            </div>


            <div class="mb-3">
                <label for="tipo" class="form-label">Categoría</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="Cultural">Cultural</option>
                    <option value="Deportivo">Deportivo</option>
                    <option value="Educativo">Educativo</option>
                    <option value="Comunitario">Comunitario</option>
                </select>
            </div>


            <div class="mb-3">
                <label for="fechaInicio" class="form-label">Fecha (dd/mm/aaaa)</label>
                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="{{ old('fechaInicio') }}" required>
            </div>


            <div class="mb-3">
                <label for="fechaFin" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="{{ old('fechaFin') }}">
            </div>


            <div class="mb-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" required>
            </div>


            <div class="mb-3">
                <label for="capacidad" class="form-label">Capacidad Máxima</label>
                <input type="number" class="form-control" id="capacidad" name="capacidad" value="{{ old('capacidad') }}" required>
            </div>


            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="esDePago" name="esDePago" value="1" {{ old('esDePago') ? 'checked' : '' }}>
                <label class="form-check-label" for="esDePago">¿Es de pago?</label>
            </div>

            
            <div class="mb-3">
                <label for="costo" class="form-label">Costo</label>
                <input type="number" step="0.01" class="form-control" id="costo" name="costo" value="{{ old('costo', 0) }}">
            </div>


            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>Activo (Visible al público)</option>
                    <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado (Solo para registro histórico)</option>
                </select>
            </div>


            <button type="submit" class="btn btn-success">Guardar Evento</button>
            <a href="{{ route('eventos.admin.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>
@else
    <div class="container"><div class="alert alert-danger">Acceso Denegado. Solo administradores pueden registrar eventos.</div></div>
@endif
@endsection