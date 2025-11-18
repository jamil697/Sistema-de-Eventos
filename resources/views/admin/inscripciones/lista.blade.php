@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inscripciones del evento</h2>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Juan Pérez</td>
                <td>12345678</td>
                <td>Pendiente</td>
                <td>
                    <button class="btn btn-success btn-sm">Aceptar</button>
                    <button class="btn btn-danger btn-sm">Rechazar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
