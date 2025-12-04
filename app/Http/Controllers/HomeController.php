<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importar la fachada Auth

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard, redirigiendo según el rol.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener el objeto de usuario autenticado
        $user = Auth::user();

        // 1. Redireccionar al Administrador
        if ($user->role === 'admin') {
            // Redirige al administrador a su lista de gestión de eventos (CRUD)
            return redirect()->route('eventos.admin.index');
        }

        // 2. Redireccionar al Ciudadano
        if ($user->role === 'ciudadano') {
            // Redirige al ciudadano a su lista de eventos a los que se ha inscrito
            return redirect()->route('ciudadano.inscripciones.lista');
        }

        // 3. Fallback (Si el rol no está definido, simplemente redirige a la lista pública)
        return redirect()->route('eventos.publicos');
    }
}