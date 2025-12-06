<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\View\View;

use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    //
    public function index(): View
    {
        // Todos los usuarios con el conteo de inscripciones
        $users = User::withCount('registrations')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.index', compact('users'));
    }
}
