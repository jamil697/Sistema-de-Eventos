<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
=======
use Illuminate\Http\Request;
>>>>>>> origin/main

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
<<<<<<< HEAD
        $eventos = Evento::where('estado', 'activo') 
                         ->orderBy('fechaInicio', 'asc') 
                         ->get();
        return view('welcome', compact('eventos'));
    }

    //Esto es solo para el administrador
    public function adminIndex()
    {
        $eventos = Evento::orderBy('fechaInicio', 'asc')->get();
=======
        $eventos = Evento::all();
>>>>>>> origin/main
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
<<<<<<< HEAD
        $request->validate([
            'titulo'          => 'required|string|max:255',
            'tipo'            => 'required|string|max:100',
            'fechaInicio'     => 'required|date',
            'fechaFin'        => 'required|date|after_or_equal:fechaInicio',
            'ubicacion'       => 'required|string|max:255',
            'capacidad'       => 'required|integer|min:1',
            'esDePago'        => 'required|boolean',
            'estado'          => 'required|in:activo,finalizado',
            'costo'           => 'nullable|numeric|min:0',
            
        ]);

        Evento::create([
            'titulo' => $request->titulo,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'ubicacion' => $request->ubicacion,
            'capacidad' => $request->capacidad,
            'tipo' => $request->tipo,
            'esDePago' => $request->has('esDePago'),
            'costo' => $request->costo ?? 0,
            'estado' => $request->estado, 
            'administrador_id' => Auth::id(),
        ]);
        
        // reedirigimos a la nueva ruta de lista del admin
        return redirect()->route('eventos.admin.index')
                         ->with('success', '¡Evento "' . $request->titulo . '" Registrado con éxito');
=======
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
>>>>>>> origin/main
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento)
    {
<<<<<<< HEAD
        // Este 'show' puede ser usado por el Admin para ver detalles o más adelante por el ciudadano.
=======
>>>>>>> origin/main
        return view('admin.eventos.mostrar', compact('evento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
<<<<<<< HEAD
=======
        
>>>>>>> origin/main
        return view('admin.eventos.editar', compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evento $evento)
    {
<<<<<<< HEAD
        $validated = $request->validate([
            'titulo'          => 'required|string|max:255',
            'tipo'            => 'required|string|max:100',
            'fechaInicio'     => 'required|date',
            'fechaFin'        => 'required|date|after_or_equal:fechaInicio',
            'ubicacion'       => 'required|string|max:255',
            'capacidad'       => 'required|integer|min:1',
            'esDePago'        => 'required|boolean',
            'estado'          => 'required|in:activo,finalizado',
            'costo'           => 'nullable|numeric|min:0',
        ]);

        $evento->update($validated); // Usamos la instancia $evento que nos da Laravel

        return redirect()->route('eventos.admin.index')->with('success', 'Evento actualizado');
=======
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
>>>>>>> origin/main
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();
<<<<<<< HEAD
        return redirect()->route('eventos.admin.index')->with('success', 'Evento eliminado');
    }
}

=======
        return redirect()->route('admin.eventos.lista')
                         ->with('success', 'Evento eliminado.');
    }
}
>>>>>>> origin/main
