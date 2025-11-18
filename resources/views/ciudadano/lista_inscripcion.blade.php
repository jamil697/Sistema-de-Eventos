@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mis Inscripciones</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Detalle</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Festival Cultural</td>
                <td>10/12/2025</td>
                <td>
                    <span class="badge bg-warning">Pendiente</span>
                </td>
                <td>
                    <a class="btn btn-info btn-sm" href="#">Ver evento</a>
                </td>
            </tr>

            <tr>
                <td>Carrera 10K</td>
                <td>15/01/2026</td>
                <td>
                    <span class="badge bg-success">Aceptado</span>
                </td>
                <td>
                    <a class="btn btn-info btn-sm" href="#">Ver evento</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
