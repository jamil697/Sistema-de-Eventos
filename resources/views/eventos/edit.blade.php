@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-gradient mb-0">Editar Evento</h1>
            <p class="text-muted small">Modificando: <strong>{{ $event->titulo }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            @auth
                @if(auth()->user()->email === env('ADMIN_EMAIL'))
                    <a href="{{ route('events.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-plus-lg me-2"></i> Nuevo
                    </a>
                @endif
            @endauth
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Ups, hay problemas:</strong>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- COLUMNA IZQUIERDA: DATOS PRINCIPALES --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Información General</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- Título --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Título del Evento</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-type-h1"></i></span>
                                <input type="text" name="titulo" class="form-control bg-light border-start-0" value="{{ old('titulo', $event->titulo) }}" required>
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Descripción Detallada</label>
                            <textarea name="descripcion" class="form-control bg-light" rows="5">{{ old('descripcion', $event->descripcion) }}</textarea>
                        </div>

                        {{-- Imagen --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Imagen de Portada</label>
                            
                            {{-- Previsualización de la imagen actual --}}
                            @if($event->imagen)
                                <div class="mb-3 p-2 border rounded-3 bg-light d-inline-block">
                                    <img src="{{ asset('storage/' . $event->imagen) }}" alt="Actual" class="rounded-2" style="height: 100px; object-fit: cover;">
                                    <div class="small text-muted mt-1 text-center">Imagen Actual</div>
                                </div>
                            @endif

                            <div class="p-4 border border-2 border-dashed rounded-4 text-center bg-light position-relative hover-effect">
                                <i class="bi bi-cloud-arrow-up display-4 text-primary opacity-50"></i>
                                <p class="small text-muted mt-2 mb-0">Arrastra una nueva imagen para cambiarla</p>
                                <input type="file" name="imagen" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/*">
                            </div>
                        </div>

                        <div class="row g-3">
                            {{-- Categoría --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Categoría</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags"></i></span>
                                    <select name="categoria_id" class="form-select bg-light border-start-0">
                                        <option value="">Seleccionar...</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}" 
                                                {{ old('categoria_id', $event->categoria_id) == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Cupos --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Cupo Máximo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-people"></i></span>
                                    <input type="number" name="cupo" class="form-control bg-light border-start-0" value="{{ old('cupo', $event->cupo) }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: LOGÍSTICA Y RECURSOS --}}
            <div class="col-lg-4">
                
                {{-- CARD: FECHAS Y LUGAR --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="fw-bold text-primary"><i class="bi bi-geo-alt me-2"></i>Logística</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Lugar / Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-pin-map"></i></span>
                                <input type="text" name="lugar" class="form-control bg-light border-start-0" value="{{ old('lugar', $event->lugar) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Inicio</label>
                            <input type="datetime-local" name="fecha_inicio" class="form-control bg-light" 
                                   value="{{ old('fecha_inicio', \Carbon\Carbon::parse($event->fecha_inicio)->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Fin (Opcional)</label>
                            <input type="datetime-local" name="fecha_fin" class="form-control bg-light" 
                                   value="{{ old('fecha_fin', $event->fecha_fin ? \Carbon\Carbon::parse($event->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
                        </div>

                    </div>
                </div>

                {{-- CARD: RECURSOS --}}
                <div class="card border-0 shadow-sm rounded-4 bg-gradient-subtle">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam me-2"></i>Recursos</h5>
                        <button id="btnOpenNewResource" type="button" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="bi bi-plus-lg"></i> Nuevo
                        </button>
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- Selector --}}
                        <div class="bg-white p-3 rounded-3 shadow-sm mb-3">
                            <label class="form-label small fw-bold text-muted">Agregar del inventario</label>
                            <select id="selectResource" class="form-select form-select-sm mb-2">
                                <option value="">-- Seleccionar --</option>
                                @foreach(\App\Models\Recurso::orderBy('nombre')->get() as $r)
                                    <option value="{{ $r->id }}">{{ $r->nombre }} (Stock: {{ $r->cantidad }})</option>
                                @endforeach
                            </select>
                            <div class="d-flex gap-2">
                                <input id="selectCantidad" type="number" min="1" value="1" class="form-control form-control-sm" placeholder="Cant.">
                                <button id="btnAddSelectedResource" type="button" class="btn btn-sm btn-dark px-3">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        {{-- Lista Dinámica (Cargada desde DB) --}}
                        <div id="assignedResourcesContainer" class="d-flex flex-column gap-2">
                            @foreach($event->resources as $r)
                                <div class="assigned-resource d-flex align-items-center justify-content-between p-2 bg-white rounded-3 shadow-sm border" data-resource-id="{{ $r->id }}">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="icon-square bg-light text-primary rounded me-2" style="width: 30px; height: 30px; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-box"></i>
                                        </div>
                                        <div class="text-truncate" style="max-width: 120px;">
                                            <small class="fw-bold d-block text-dark lh-1">{{ $r->nombre }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <input type="number" name="resources[{{ $r->id }}]" value="{{ $r->pivot->cantidad }}" min="1" class="form-control form-control-sm text-center p-0" style="width: 40px;">
                                        <button type="button" class="btn btn-link text-danger p-0 btn-remove-resource"><i class="bi bi-x-circle-fill"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- BOTÓN FLOTANTE AL FINAL --}}
        <div class="d-grid mt-4 mb-5">
            <button class="btn btn-primary btn-lg rounded-pill shadow-lg fw-bold py-3 bg-gradient-primary border-0">
                <i class="bi bi-check2-circle me-2"></i> Actualizar Evento
            </button>
        </div>
    </form>
</div>

{{-- MODAL --}}
<div id="newResourceModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1050; backdrop-filter: blur(5px); align-items: center; justify-content: center;">
  <div class="card border-0 shadow-lg rounded-4 p-0" style="width: 450px; max-width: 90%;">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
        <h5 class="fw-bold mb-0">Nuevo Recurso Rápido</h5>
    </div>
    <div class="card-body p-4">
        <div id="newResourceErrors" class="alert alert-danger d-none small"></div>

        <div class="mb-3">
            <label class="small fw-bold text-muted">Nombre del recurso</label>
            <input id="newResNombre" type="text" class="form-control bg-light" placeholder="Ej: Proyector">
        </div>
        <div class="mb-3">
            <label class="small fw-bold text-muted">Descripción</label>
            <input id="newResDescripcion" type="text" class="form-control bg-light">
        </div>
        <div class="mb-4">
            <label class="small fw-bold text-muted">Cantidad Inicial</label>
            <input id="newResCantidad" type="number" min="1" value="1" class="form-control bg-light">
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button id="btnCloseNewResource" type="button" class="btn btn-light rounded-pill px-4">Cancelar</button>
            <button id="btnCreateResourceAjax" type="button" class="btn btn-primary rounded-pill px-4">Crear y Añadir</button>
        </div>
    </div>
  </div>
</div>

<style>
    /* Estilos Específicos */
        .text-gradient {
        background: linear-gradient(45deg, #1e293b, #4f46e5);
        -webkit-background-clip: text; /* Para Chrome, Safari, Edge */
        background-clip: text;         /* <--- AGREGA ESTA LÍNEA (Estándar) */
        -webkit-text-fill-color: transparent;
        color: transparent;            /* <--- AGREGA ESTA LÍNEA (Respaldo) */
    }
    .bg-gradient-subtle {
        background: linear-gradient(180deg, #f8f9fa 0%, #eef2ff 100%);
    }
    .bg-gradient-primary {
        background: linear-gradient(90deg, #4f46e5 0%, #4338ca 100%);
        transition: transform 0.2s;
    }
    .bg-gradient-primary:hover {
        transform: translateY(-2px);
    }
    
    .border-dashed { border-style: dashed !important; }
    .hover-effect:hover { background-color: #e9ecef !important; }

    .assigned-resource { transition: all 0.2s; }
    .assigned-resource:hover { transform: translateX(2px); border-color: #4f46e5 !important; }
</style>
@endsection

@push('scripts')
    <script>
        window.pageConfig = {
            storeUrl: "{{ route('resources.store') }}",
            token: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('js/eventos_create.js') }}"></script>
@endpush