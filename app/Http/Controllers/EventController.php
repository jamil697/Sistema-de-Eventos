<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Recurso;
use App\Models\Categoria;
use App\Models\User;
use App\Notifications\EventUpdatedNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

        $categoriaId = $request->query('categoria');

        if (!empty($categoriaId)) {
            $query->where('categoria_id', $categoriaId);
        }

        $events = $query->paginate(10)->appends($request->only('categoria'));

        return view('eventos.index', compact('events', 'categorias', 'categoriaId'));
    }



    public function create()
    {
        $resources = Recurso::all();
        $categorias = Categoria::orderBy('nombre')->get();
        return view('eventos.create', compact('resources','categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'lugar' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'cupo' => 'nullable|integer|min:1',
            'resources' => 'nullable|array',
            'resources.*' => 'nullable|integer|min:1',
            'categoria_id' => 'nullable|exists:categorias,id',

        ]);

        // 2. Lógica para guardar la imagen
        if ($request->hasFile('imagen')) {
            
        $file = $request->file('imagen');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $ruta = 'eventos/' . $filename;
            // 2. INVOCAR LA LIBRERÍA (Aquí ocurre la magia)
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);

            // 3. REDIMENSIONAR (Ancho 800px, altura automática)
        $image->scale(width: 800);

            // 4. GUARDAR EN STORAGE
         Storage::disk('public')->put($ruta, (string) $image->toJpeg(80));

        $data['imagen'] = $ruta;
        } else {
            // (Solo para UPDATE) Si no sube nueva, quitamos el campo para no borrar la existente
            if (isset($event)) {
                unset($data['imagen']);
            }
        }

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
        if (! (
    Auth::check() &&
    (Auth::user()->email === env('ADMIN_EMAIL') || Auth::id() === $event->created_by)
    )) {
        abort(403, 'Acción no autorizada.');
    }

        $resources = Recurso::all();
        $categorias = Categoria::orderBy('nombre')->get();
       
        $assigned = $event->resources->pluck('pivot.cantidad', 'id')->toArray();
        return view('eventos.edit', compact('event','resources','assigned','categorias'));
    }

  public function update(Request $request, $id)
    {
        $event = Evento::findOrFail($id);

        if (! Auth::check() || ! (Auth::user()->email === env('ADMIN_EMAIL') || Auth::id() === $event->created_by)) {
            abort(403, 'Acción no autorizada.');
        }

        $data = $request->validate([
            'titulo'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'imagen'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'lugar'        => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'cupo'         => 'nullable|integer|min:1',
            'resources'    => 'nullable|array',
            'resources.*'  => 'nullable|integer|min:0',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        // Lógica de la IMAGEN OPTIMIZADA
        if ($request->hasFile('imagen')) {
            
            // 1. Borrar imagen anterior si existe (Solo para el UPDATE)
            if (isset($event) && $event->imagen) {
                Storage::disk('public')->delete($event->imagen);
            }

            $file = $request->file('imagen');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $ruta = 'eventos/' . $filename;

            // 2. INVOCAR LA LIBRERÍA (Aquí ocurre la magia)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            // 3. REDIMENSIONAR (Ancho 800px, altura automática)
            $image->scale(width: 800);

            // 4. GUARDAR EN STORAGE
            Storage::disk('public')->put($ruta, (string) $image->toJpeg(80));

            $data['imagen'] = $ruta;
        } else {
            // (Solo para UPDATE) Si no sube nueva, quitamos el campo para no borrar la existente
            if (isset($event)) {
                unset($data['imagen']);
            }
        }

        // 6. Actualizamos el evento
        $event->update($data);

        // 7. Sync de recursos (Tabla pivote)
        $syncData = [];
        if ($request->filled('resources')) {
            foreach ($request->input('resources') as $resId => $cantidad) {
                if ((int)$cantidad > 0) {
                    $syncData[$resId] = ['cantidad' => (int)$cantidad];
                }
            }
        }
        // Usamos sync para actualizar las relaciones
        $event->resources()->sync($syncData);

        // 8. Notificar a los inscritos
        $userIds = $event->registrations()->pluck('user_id')->unique();

        if ($userIds->isNotEmpty()) {
            $users = User::whereIn('id', $userIds)->get();
            foreach ($users as $u) {
                $u->notify(new EventUpdatedNotification($event));
            }
        }

        return redirect()->route('events.show', $event)->with('success', 'Evento actualizado correctamente.');
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