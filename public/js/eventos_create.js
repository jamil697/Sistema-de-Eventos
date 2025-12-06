// public/js/eventos_create.js

document.addEventListener('DOMContentLoaded', function(){

    // 1. Leemos la configuración que pasaremos desde la vista
    const storeUrl = window.pageConfig.storeUrl;
    const token = window.pageConfig.token;
    
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
        // USAMOS LA VARIABLE storeUrl AQUÍ
        const res = await fetch(storeUrl, {
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
        option.text = `${resource.nombre} (disp: ${resource.cantidad})`;
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
        console.error(err);
        showNewResErrors(['Error de conexión']);
      }
    });
   
    function showNewResErrors(arr){
      const el = document.getElementById('newResourceErrors');
      el.innerHTML = '<ul>' + arr.map(x => `<li>${x}</li>`).join('') + '</ul>';
      el.classList.remove('d-none');
    }
   
    function hideNewResErrors(){
      const el = document.getElementById('newResourceErrors');
      el.classList.add('d-none');
      el.innerHTML = '';
    }
   
  });