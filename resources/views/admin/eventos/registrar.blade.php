@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'admin')
    <div class="container">
        <h2>Registrar evento</h2>

        <form>
            <div class="mb-3">
                <label>Nombre del evento</label>
                <input type="text" class="form-control">
            </div>

            <div class="mb-3">
                <label>Descripción</label>
                <textarea class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Fecha</label>
                <input type="date" class="form-control">
            </div>

            <div class="mb-3">
                <label>Categoría</label>
                <select class="form-control">
                    <option>Cultural</option>
                    <option>Deportivo</option>
                    <option>Social</option>
                </select>
            </div>

            <button class="btn btn-success">Guardar</button>
        </form>
    </div>
@endif
@endsection
