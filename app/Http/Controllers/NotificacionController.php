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
        $notifications = $user->notifications()->latest()->get();

        // marcar como leídas las que estaban sin leer
        $user->unreadNotifications->markAsRead();

        return view('notificaciones.index', compact('notifications'));
    }
}
