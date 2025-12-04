<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventos = Evento::all();
        return view('admin.eventos.lista', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.eventos.registrar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date',
            'ubicacion' => 'required|string',
            'capacidad' => 'required|integer',
            'esDePago' => 'required|boolean',
            'estado' => 'required|string',
            'costo' => 'nullable|numeric',
            'administrador_id' => 'required|integer'
        ]);

        Evento::create($validated);

        return redirect()->route('admin.eventos.lista')
                         ->with('success', 'Evento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
        return view('admin.eventos.mostrar', compact('evento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        
        return view('admin.eventos.editar', compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento)
    {
         $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date',
            'ubicacion' => 'required|string',
            'capacidad' => 'required|integer',
            'esDePago' => 'required|boolean',
            'estado' => 'required|string',
            'costo' => 'nullable|numeric',
            'administrador_id' => 'required|integer'
        ]);

        $evento->update($validated);

        return redirect()->route('admin.eventos.lista')
                         ->with('success', 'Evento actualizado correctamente.');
    }

    public function asignarRecurso(Request $request, Evento $evento)
    {
        $request->validate([
        'recurso_id' => 'required|exists:recursos,id',
        'cantidad'   => 'required|integer|min:1',
    ]);

        // asignar
        $evento->recursos()->attach($request->recurso_id, [
        'cantidad' => $request->cantidad
    ]);

        return back()->with('ok', 'Recurso asignado correctamente.');
    }


    public function quitarRecurso(Evento $evento, Recurso $recurso)
    {
        $evento->recursos()->detach($recurso->id);

        return back()->with('ok', 'Recurso quitado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('admin.eventos.lista')
                         ->with('success', 'Evento eliminado.');
    }
}
