@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Eventos publicados</h2>

    <a class="btn btn-success mb-3" href="#">Registrar evento</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Categoría</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Festival Cultural</td>
                <td>10/12/2025</td>
                <td>Cultural</td>
                <td>
                    <a class="btn btn-info btn-sm" href="#">Ver</a>
                    <a class="btn btn-warning btn-sm" href="#">Editar</a>
                    <a class="btn btn-danger btn-sm" href="#">Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
