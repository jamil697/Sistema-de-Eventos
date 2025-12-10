<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // todas las notificaciones del usuario
        $notifications = $user->notifications()->latest()->paginate(10);

        // marcar como leídas las que estaban sin leer
        $user->unreadNotifications->markAsRead();

        return view('notificaciones.index', compact('notifications'));
    }
    public function markAllRead()
    {
        // Marca todas las notificaciones no leídas del usuario actual como leídas
        Auth::user()->unreadNotifications->markAsRead();

        // Regresa a la página anterior con un mensaje de éxito
        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }
}
