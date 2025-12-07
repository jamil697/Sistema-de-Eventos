@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar evento</h1>
    @auth
        @if(auth()->user()->email === env('ADMIN_EMAIL'))
            <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">Crear evento</a>
        @endif
    @endauth

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- IMPORTANTE: enctype agregado para permitir subida de imagenes --}}
    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $event->titulo) }}" required>
        </div>

        {{-- CAMPO DE IMAGEN AGREGADO --}}
        <div class="mb-3">
            <label class="form-label">Imagen de portada</label>
            
            {{-- Muestra la imagen actual si existe --}}
            @if($event->imagen)
                <div class="mb-2">
                    <label>Imagen actual:</label><br>
                    <img src="{{ asset('storage/' . $event->imagen) }}" alt="Imagen actual" style="max-height: 150px; border-radius: 5px;">
                </div>
            @endif

            <input type="file" name="imagen" class="form-control" accept="image/*">
            <small class="text-muted">Sube un archivo solo si deseas cambiar la imagen actual.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $event->descripcion) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Lugar</label>
            <input type="text" name="lugar" class="form-control" value="{{ old('lugar', $event->lugar) }}">
        </div>

        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha inicio</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', \Carbon\Carbon::parse($event->fecha_inicio)->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha fin</label>
                <input type="datetime-local" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $event->fecha_fin ? \Carbon\Carbon::parse($event->fecha_fin)->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Cupo (opcional)</label>
            <input type="number" name="cupo" class="form-control" value="{{ old('cupo', $event->cupo) }}">
        </div>

        <div class="mb-3">
              <label class="form-label">Categoría</label>
              <select name="categoria_id" class="form-control">
                  <option value="">-- Selecciona una categoría --</option>
                  @foreach($categorias as $cat)
                      <option value="{{ $cat->id }}"
                          {{ old('categoria_id', $event->categoria_id) == $cat->id ? 'selected' : '' }}>
                          {{ $cat->nombre }}
                      </option>
                  @endforeach
              </select>
          </div>


        {{-- BLOQUE DE RECURSOS --}}
        <div class="card mb-3 p-3">
            <h5>Asignar recursos</h5>

            <div class="row g-2 align-items-end">
                <div class="col-md-7">
                    <label for="selectResource" class="form-label">Seleccionar recurso</label>
                    <select id="selectResource" class="form-control">
                        <option value="">-- Selecciona un recurso --</option>
                        @foreach(\App\Models\Recurso::orderBy('nombre')->get() as $r)
                            <option value="{{ $r->id }}">{{ $r->nombre }} (disp: {{ $r->cantidad }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Cantidad</label>
                    <input id="selectCantidad" type="number" min="1" value="1" class="form-control">
                </div>

                <div class="col-md-3">
                    <button id="btnAddSelectedResource" type="button" class="btn btn-primary w-100">Añadir seleccionado</button>
                </div>
            </div>

            <hr>

            <div id="assignedResourcesContainer">
                {{-- cargar recursos ya asignados --}}
                @foreach($event->resources as $r)
                    <div class="assigned-resource row g-2 align-items-center mb-2" data-resource-id="{{ $r->id }}">
                        <div class="col-md-8"><strong>{{ $r->nombre }}</strong></div>
                        <div class="col-md-2"><input type="number" name="resources[{{ $r->id }}]" value="{{ $r->pivot->cantidad }}" min="0" class="form-control"></div>
                        <div class="col-md-2"><button type="button" class="btn btn-danger btn-remove-resource">Quitar</button></div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                <button id="btnOpenNewResource" type="button" class="btn btn-outline-secondary">Crear nuevo recurso</button>
            </div>
        </div>

        <button class="btn btn-success">Actualizar evento</button>
    </form>
</div>

{{-- EL MODAL TAMBIÉN ES NECESARIO AQUÍ --}}
<div id="newResourceModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1050; align-items: center; justify-content: center;">
  <div class="card p-3" style="width:520px; margin:auto;">
    <h5>Crear recurso rápido</h5>
    <div id="newResourceErrors" class="alert alert-danger d-none"></div>

    <div class="mb-2">
      <label>Nombre</label>
      <input id="newResNombre" type="text" class="form-control">
    </div>
    <div class="mb-2">
      <label>Descripción</label>
      <input id="newResDescripcion" type="text" class="form-control">
    </div>
    <div class="mb-2">
      <label>Cantidad disponible</label>
      <input id="newResCantidad" type="number" min="0" value="1" class="form-control">
    </div>

    <div class="d-flex gap-2">
      <button id="btnCreateResourceAjax" type="button" class="btn btn-success">Crear y añadir</button>
      <button id="btnCloseNewResource" type="button" class="btn btn-secondary">Cancelar</button>
    </div>
  </div>
</div>
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