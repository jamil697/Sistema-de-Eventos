<?php

namespace App\Http\Controllers;
use App\Models\Evento;
use App\Models\Registracion as Registration;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class RegistracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // solo usuarios autenticados
    }

    // Inscribir usuario autenticado a un evento
    public function store(Request $request, Evento $event)
    {
        $user = Auth::user(); 
        // 🚫 Bloquear que el admin se inscriba
        if ($user->email === env('ADMIN_EMAIL')) {
            return back()->with('info', 'Los administradores no pueden inscribirse en los eventos.');
        }

        // Verificar si ya está inscrito
        if ($event->registrations()->where('user_id',$user->id)->exists()) {
            return back()->with('info','Ya estás inscrito en este evento.');
        }

        // Verificar cupo
        if ($event->cupo && $event->registrations()->count() >= $event->cupo) {
            return back()->with('error','El evento está completo.');
        }

        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'estado' => 'inscrito'
        ]);

        // -- Aquí podrías notificar al usuario: $user->notify(new EventRegistered($event));
        return back()->with('success','Te has inscrito correctamente.');
    }

    // Cancelar inscripción del usuario autenticado
    public function destroy(Request $request, Evento $event)
    {
        $user = Auth::user(); 
        $reg = $event->registrations()->where('user_id', $user->id)->first();
        if (!$reg) {
            return back()->with('info','No estás inscrito en este evento.');
        }
        $reg->delete();
        return back()->with('success','Inscripción cancelada.');
    }
    public function misEventos()
    {
        $user = Auth::user(); 

        
        if ($user->email === env('ADMIN_EMAIL')) {
            return redirect()->route('events.index')
                ->with('info', 'Los administradores gestionan eventos, no se inscriben.');
        }

        $userId = $user->id;

        $eventos = Evento::whereHas('registrations', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        return view('eventos.mis_eventos', compact('eventos'));
    }
}
