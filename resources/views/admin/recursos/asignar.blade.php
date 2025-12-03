@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'admin')
    <div class="container">
        <h2>Asignar recursos al evento</h2>

        <form>
            <div class="mb-3">
                <label>Espacio</label>
                <select class="form-control">
                    <option>Auditorio</option>
                    <option>Estadio</option>
                    <option>Plaza</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Equipos</label>
                <select class="form-control">
                    <option>Sonido</option>
                    <option>Iluminación</option>
                </select>
            </div>

            <button class="btn btn-primary">Asignar</button>
        </form>
    </div>
@endif
@endsection
