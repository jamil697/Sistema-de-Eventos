<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function __construct()
    {
        // Ajusta middleware/authorization según tu sistema de roles
        $this->middleware('auth');
        // Si quieres más control: $this->middleware('can:manage-resources');
    }

    public function index()
    {
        $resources = Recurso::orderBy('nombre')->paginate(10);
        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:1'
        ]);

        $resource = Recurso::create($data);
        // Si la petición espera JSON (AJAX), devolver JSON con el recurso creado
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'resource' => $resource
            ]);
        }

        return redirect()->route('resources.index')->with('success','Recurso creado.');
    }

    public function show(Recurso $resource)
    {
        $resource->load('events');
        return view('resources.show', compact('resource'));
    }

    public function edit(Recurso $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Recurso $resource)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cantidad' => 'required|integer|min:0'
        ]);

        $resource->update($data);

        return redirect()->route('resources.index')->with('success','Recurso actualizado.');
    }

    public function destroy(Recurso $resource)
    {
        // Si el recurso está asignado a eventos, decide si permites borrarlo
        $resource->events()->detach();
        $resource->delete();

        return redirect()->route('resources.index')->with('success','Recurso eliminado.');
    }
}
