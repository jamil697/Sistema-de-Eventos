@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuarios registrados</h1>

    @if($users->isEmpty())
        <p>No hay usuarios registrados.</p>
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Tipo de Usuario</th>
                        <th>Inscripciones</th>
                        <th>Registrado el</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->email === env('ADMIN_EMAIL'))
                                    <span class="badge bg-primary">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Ciudadano</span>
                                @endif
                            </td>
                            <td>{{ $u->registrations_count }}</td>
                            <td>{{ $u->created_at?->format('d/m/Y ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
