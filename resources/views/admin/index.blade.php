@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- CABECERA: Título, Totales y Buscador --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Gestión de Usuarios</h1>
            <p class="text-muted small mb-0">
                Total registrados: <span class="fw-bold text-dark">{{ $users->total() }}</span>
            </p>
        </div>

        {{-- Barra de búsqueda (Visual, requiere lógica en controlador para funcionar) --}}
        <div class="d-flex gap-2">
            <form action="" method="GET" class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Buscar usuario..." style="min-width: 250px;">
                    <button class="btn btn-dark">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    @if($users->isEmpty())
        {{-- ESTADO VACÍO --}}
        <div class="text-center py-5 bg-light rounded-3 border border-dashed">
            <div class="mb-3">
                <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                    <i class="bi bi-people text-muted fs-2 opacity-50"></i>
                </div>
            </div>
            <h4 class="text-muted">No hay usuarios encontrados</h4>
            <p class="text-muted small">Intenta ajustar los filtros o registra nuevos usuarios.</p>
        </div>
    @else
        {{-- TABLA EN CARD --}}
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            
                            <th scope="col" class="py-3 text-uppercase text-muted small fw-bold">Usuario</th>
                            <th scope="col" class="py-3 text-uppercase text-muted small fw-bold">Rol</th>
                            <th scope="col" class="py-3 text-uppercase text-muted small fw-bold text-center">Inscripciones</th>
                            <th scope="col" class="py-3 text-uppercase text-muted small fw-bold">Fecha Registro</th>
                            <th scope="col" class="pe-4 py-3 text-end text-uppercase text-muted small fw-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($users as $u)
                            <tr>
                                
                                
                                {{-- USUARIO (Avatar + Nombre + Email) --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Generador de Avatar con Iniciales --}}
                                        <div class="flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random&color=fff&size=40" 
                                                 class="rounded-circle" 
                                                 alt="{{ $u->name }}" 
                                                 width="40" height="40">
                                        </div>
                                        <div class="ms-3">
                                            <div class="fw-bold text-dark">{{ $u->name }}</div>
                                            <div class="text-muted small">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ROL --}}
                                <td>
                                    @if($u->email === env('ADMIN_EMAIL'))
                                        <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-3">
                                            <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark border rounded-pill px-3">
                                            <i class="bi bi-person me-1"></i> Usuario
                                        </span>
                                    @endif
                                </td>

                                {{-- INSCRIPCIONES (Centrado) --}}
                                <td class="text-center">
                                    @if($u->registrations_count > 0)
                                        <span class="badge bg-primary rounded-pill">{{ $u->registrations_count }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- FECHA --}}
                                <td>
                                    <span class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $u->created_at?->format('d M, Y') }}
                                    </span>
                                </td>

                                {{-- ACCIONES --}}
                                <td class="pe-4 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Opciones
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li><h6 class="dropdown-header">Acciones</h6></li>
                                            
                                            {{-- 1. VER PERFIL: Lleva a una ruta que crearemos ahora --}}
                                            <li>
                                                <a class="dropdown-item" href="{{ route('users.show', $u) }}">
                                                    <i class="bi bi-eye me-2"></i> Ver perfil completo
                                                </a>
                                            </li>

                                            {{-- 2. ENVIAR CORREO: Abre tu aplicación de correo (Gmail/Outlook) --}}
                                            <li>
                                                <a class="dropdown-item" href="mailto:{{ $u->email }}?subject=Comunicado del Sistema de Eventos">
                                                    <i class="bi bi-envelope me-2"></i> Enviar correo
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Footer de la tabla con paginación --}}
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    /* Estilos personalizados para badges sutiles */
    .bg-purple-subtle { background-color: #e9d5ff; }
    .text-purple { color: #6b21a8; }
    .border-purple-subtle { border-color: #d8b4fe; }

    /* Ajuste para inputs de busqueda */
    .input-group-text { background-color: #fff; }
    
    /* Hover en filas */
    tbody tr { transition: background-color 0.2s; }
</style>
@endsection