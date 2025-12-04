<?php

namespace App\Http\Controllers;
use App\Models\Inscripcion;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // esto es para obtener todas las inscripciones del usuario, incluyendo los datos del evento
        $inscripciones = Inscripcion::where('user_id', $userId)
                                    ->with('evento') // carga la relación "evento" para acceder a sus datos
                                    ->latest()
                                    ->get();
        
        return view('ciudadano.lista_inscripcion', compact('inscripciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Evento $evento)
    {
        $yaInscrito = Inscripcion::where('user_id', Auth::id())
                                 ->where('evento_id', $evento->id)
                                 ->exists();

        if ($yaInscrito) {
            // Redirigimos a la lista de eventos registrados
            return redirect()->route('ciudadano.inscripciones.lista') 
                             ->with('error', 'Ya estás inscrito en el evento: ' . $evento->titulo);
        }

        // 2. Retornar la vista del formulario, pasando el evento para mostrar detalles
        // Esta vista será resources/views/ciudadano/inscripcion.blade.php
        return view('ciudadano.inscripcion', compact('evento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Evento $evento)
    {
        $request->validate([
            // Asegúrate de que estos campos existan en tu formulario y en la tabla 'inscripciones'
            'telefono' => 'required|string|max:20',
            'ocupacion' => 'required|string|max:100',
        ]);
        
        $userId = Auth::id();

        // 2. Verificar duplicados (Doble check de seguridad)
        $existeInscripcion = Inscripcion::where('user_id', $userId)
                                         ->where('evento_id', $evento->id)
                                         ->exists();

        if ($existeInscripcion) {
            return redirect()->route('ciudadano.inscripciones.lista')
                             ->with('error', 'La inscripción para este evento ya ha sido realizada.');
        }

        // 3. Crear la inscripción (usando los datos del usuario, evento y formulario)
        Inscripcion::create([
            'user_id' => $userId,
            'evento_id' => $evento->id,
            'fecha_inscripcion' => now(), 
            'estado' => 'registrado',
            // Mapear los campos adicionales del formulario
            'telefono' => $request->telefono,
            'ocupacion' => $request->ocupacion,
        ]);

        // 4. Redirigir al ciudadano a su lista de eventos registrados
        return redirect()->route('ciudadano.inscripciones.lista') // Usando el nombre corregido
                         ->with('success', '¡Inscripción al evento "' . $evento->titulo . '" completada con éxito!');
    }

    /**
     * Display the specified resource.
     */
    //para ver os detalles de una inscrpn especifica
    public function show(string $id)
    {
        if ($inscripcion->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado a esta inscripción.');
        }
        $inscripcion->load('evento');
        return view('ciudadano.detalle', compact('inscripcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
