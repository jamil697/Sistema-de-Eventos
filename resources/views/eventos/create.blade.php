@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear evento</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Lugar</label>
            <input type="text" name="lugar" class="form-control" value="{{ old('lugar') }}">
        </div>

        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha inicio</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha fin</label>
                <input type="datetime-local" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Cupo (opcional)</label>
            <input type="number" name="cupo" class="form-control" value="{{ old('cupo') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="categoria_id" class="form-control">
                <option value="">-- Selecciona una categoría --</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>
        </div>


        {{-- BLOQUE DE RECURSOS (selector + crear modal + assigned list) --}}
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
            {{-- aquí se inyectan dinámicamente los recursos seleccionados (inputs name="resources[id]") --}}
            {{-- Si quieres precargar algo en create, puedes usar old() --}}
            @if(old('resources'))
              @foreach(old('resources') as $rid => $cant)
                @php $res = \App\Models\Recurso::find($rid); @endphp
                @if($res)
                  <div class="assigned-resource row g-2 align-items-center mb-2" data-resource-id="{{ $res->id }}">
                    <div class="col-md-8"><strong>{{ $res->nombre }}</strong></div>
                    <div class="col-md-2"><input type="number" name="resources[{{ $res->id }}]" value="{{ $cant }}" min="0" class="form-control"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-remove-resource">Quitar</button></div>
                  </div>
                @endif
              @endforeach
            @endif
          </div>

          <div class="mt-3">
            <button id="btnOpenNewResource" type="button" class="btn btn-outline-secondary">Crear nuevo recurso</button>
          </div>
        </div>

        <button class="btn btn-success">Guardar evento</button>
    </form>
</div>

    {{-- PEGA ESTO AL FINAL DE TU SECCIÓN @section('content'), ANTES DE @endsection --}}

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