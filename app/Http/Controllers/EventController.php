<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Recurso;
use App\Models\Categoria;
use App\Models\User;
use App\Notifications\EventUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        // Solo usuarios autenticados pueden crear/editar/borrar
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(Request $request)
    {
        // Todas las categorías para el combo de filtro
        $categorias = Categoria::orderBy('nombre')->get();

        // Empezamos el query base
        $query = Evento::with(['categoria'])
            ->withCount('registrations')
            ->orderBy('fecha_inicio', 'asc');

        // Leer el filtro de la URL: ?categoria=ID
        $categoriaId = $request->query('categoria');

        if (!empty($categoriaId)) {
            $query->where('categoria_id', $categoriaId);
        }

        // Paginación (manteniendo el filtro en los links)
        $events = $query->paginate(10)->appends($request->only('categoria'));

        return view('eventos.index', compact('events', 'categorias', 'categoriaId'));
    }



    public function create()
    {
        // Obtener recursos disponibles para asignar al crear evento
        $resources = Recurso::all();
        $categorias = Categoria::orderBy('nombre')->get();
        return view('eventos.create', compact('resources','categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'lugar' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'cupo' => 'nullable|integer|min:1',
            // resources.* expected as array 'resources[id]' = cantidad
            'resources' => 'nullable|array',
            'resources.*' => 'nullable|integer|min:1',
            'categoria_id' => 'nullable|exists:categorias,id',

        ]);

        $data['created_by'] = Auth::id();
        $events = Evento::create($data);

        // Asignar recursos si vienen
        if ($request->filled('resources')) {
            foreach ($request->input('resources') as $resId => $cantidad) {
                if ($cantidad > 0) {
                    $events->resources()->attach($resId, ['cantidad' => $cantidad]);
                }
            }
        }

        return redirect()->route('events.show', $events)->with('success','Evento creado correctamente.');
    }

    public function show(Evento $event)
        {
            $event->load(['resources','registrations.user']);
            $inscritosCount = $event->registrations()->count();
            $estaInscrito = Auth::check()
            ? $event->registrations()->where('user_id', Auth::id())->exists()
            : false;


            return view('eventos.show', compact('event','inscritosCount','estaInscrito'));
        }

    public function edit(Evento $event)
    {
        // Solo el creador o un admin debería editar; aquí dejamos al middleware/policy si lo configuras.
        if (! (
    Auth::check() &&
    (Auth::user()->email === env('ADMIN_EMAIL') || Auth::id() === $event->created_by)
    )) {
        abort(403, 'Acción no autorizada.');
    }

        $resources = Recurso::all();
        $categorias = Categoria::orderBy('nombre')->get();
        // preparar array con cantidades actuales
        $assigned = $event->resources->pluck('pivot.cantidad', 'id')->toArray();
        return view('eventos.edit', compact('event','resources','assigned','categorias'));
    }

    public function update(Request $request, Evento $event)
    {
        if (! Auth::check() || ! (Auth::user()->email === env('ADMIN_EMAIL') || Auth::id() === $event->created_by)) {
            abort(403, 'Acción no autorizada.');
        }
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'lugar' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'cupo' => 'nullable|integer|min:1',
            'resources' => 'nullable|array',
            'resources.*' => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',

        ]);

        $event->update($data);

        // Sync de recursos: usamos detach + attach para manejar cantidades
        $syncData = [];
        if ($request->filled('resources')) {
            foreach ($request->input('resources') as $resId => $cantidad) {
                if ((int)$cantidad > 0) {
                    $syncData[$resId] = ['cantidad' => (int)$cantidad];
                }
            }
        }
        $event->resources()->sync($syncData);

        // 👉 Notificar a los inscritos que el evento fue actualizado
        $userIds = $event->registrations()->pluck('user_id')->unique();

        if ($userIds->isNotEmpty()) {
            $users = User::whereIn('id', $userIds)->get();

            foreach ($users as $u) {
                $u->notify(new EventUpdatedNotification($event));
            }
        }



        return redirect()->route('events.show', $event)->with('success','Evento actualizado.');
    }

    public function destroy(Evento $event)
    {
        if (! Auth::check() || ! (Auth::user()->email === env('ADMIN_EMAIL') || Auth::id() === $event->created_by)) {
            abort(403, 'Acción no autorizada.');
        }
        $event->delete();
        return redirect()->route('events.index')->with('success','Evento eliminado.');
    }

   
}