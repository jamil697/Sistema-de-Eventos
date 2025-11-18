@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Explorar eventos</h2>

    <form class="row mb-3">
        <div class="col-md-3">
            <input class="form-control" type="date">
        </div>
        <div class="col-md-3">
            <select class="form-control">
                <option>Cultural</option>
                <option>Deportivo</option>
                <option>Social</option>
            </select>
        </div>
        <div class="col-md-3">
            <input class="form-control" placeholder="Ubicación">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Categoría</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Concierto Municipal</td>
                <td>05/12/2025</td>
                <td>Cultural</td>
                <td><a class="btn btn-info btn-sm" href="#">Ver</a></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
