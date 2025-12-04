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

    <form action="{{ route('events.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $event->titulo) }}" required>
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
@endsection

@push('scripts')
{{-- mismo modal y scripts que en create --}}
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

<script>
document.addEventListener('DOMContentLoaded', function(){

  const token = '{{ csrf_token() }}';
  const modal = document.getElementById('newResourceModal');

  document.getElementById('btnOpenNewResource').addEventListener('click', ()=> modal.style.display = 'flex');
  document.getElementById('btnCloseNewResource').addEventListener('click', ()=> modal.style.display = 'none');

  document.getElementById('btnAddSelectedResource').addEventListener('click', function(){
    const select = document.getElementById('selectResource');
    const resId = select.value;
    const resText = select.options[select.selectedIndex]?.text;
    const cantidad = document.getElementById('selectCantidad').value || 1;
    if(!resId) {
      alert('Selecciona un recurso.');
      return;
    }

    const existingEl = document.querySelector('#assignedResourcesContainer .assigned-resource[data-resource-id="'+resId+'"]');
    if(existingEl){
      const input = existingEl.querySelector('input[type="number"]');
      input.value = parseInt(input.value) + parseInt(cantidad);
      return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'assigned-resource row g-2 align-items-center mb-2';
    wrapper.setAttribute('data-resource-id', resId);
    wrapper.innerHTML = `
      <div class="col-md-8"><strong>${resText}</strong></div>
      <div class="col-md-2"><input type="number" name="resources[${resId}]" value="${cantidad}" min="0" class="form-control"></div>
      <div class="col-md-2"><button type="button" class="btn btn-danger btn-remove-resource">Quitar</button></div>
    `;
    document.getElementById('assignedResourcesContainer').appendChild(wrapper);
  });

  document.getElementById('assignedResourcesContainer').addEventListener('click', function(e){
    if(e.target.classList.contains('btn-remove-resource')){
      e.target.closest('.assigned-resource').remove();
    }
  });

  document.getElementById('btnCreateResourceAjax').addEventListener('click', async function(){
    const nombre = document.getElementById('newResNombre').value.trim();
    const descripcion = document.getElementById('newResDescripcion').value.trim();
    const cantidad = document.getElementById('newResCantidad').value;

    if(!nombre){
      showNewResErrors(['El nombre es obligatorio.']);
      return;
    }

    try {
      const res = await fetch("{{ route('resources.store') }}", {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ nombre, descripcion, cantidad })
      });

      const data = await res.json();

      if(!res.ok){
        const errors = data.errors ? Object.values(data.errors).flat() : [data.message || 'Error'];
        showNewResErrors(errors);
        return;
      }

      const resource = data.resource;
      const option = document.createElement('option');
      option.value = resource.id;
      option.text = ${resource.nombre} (disp: ${resource.cantidad});
      document.getElementById('selectResource').appendChild(option);

      document.getElementById('selectResource').value = resource.id;
      document.getElementById('selectCantidad').value = 1;
      document.getElementById('btnAddSelectedResource').click();

      document.getElementById('newResNombre').value = '';
      document.getElementById('newResDescripcion').value = '';
      document.getElementById('newResCantidad').value = 1;
      modal.style.display = 'none';
      hideNewResErrors();

    } catch (err) {
      showNewResErrors(['Error de conexión']);
    }
  });

  function showNewResErrors(arr){
    const el = document.getElementById('newResourceErrors');
    el.innerHTML = '<ul>'+arr.map(x=><li>${x}</li>).join('')+'</ul>';
    el.classList.remove('d-none');
  }
  function hideNewResErrors(){
    const el = document.getElementById('newResourceErrors');
    el.classList.add('d-none');
    el.innerHTML = '';
  }

});
</script>
@endpush