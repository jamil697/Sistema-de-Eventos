
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Inscripción al Evento</h2>

    <div class="card mt-3">
        <div class="card-body">

            <h4>Formulario de Inscripción</h4>

            <form>
                <div class="mb-3">
                    <label>Nombre completo</label>
                    <input type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label>DNI</label>
                    <input type="text" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Correo electrónico</label>
                    <input type="email" class="form-control">
                </div>

                <button class="btn btn-success">Enviar Inscripción</button>
            </form>
        </div>
    </div>
</div>
@endsection
