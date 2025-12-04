<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventoController;


/* Página pública */
Route::get('/', function () {
    return view('welcome');
});

/* Rutas de autenticación (deben quedar fuera del middleware) */
Auth::routes();

/* TODO LO SIGUIENTE REQUIERE LOGIN */
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');

/* Admin - eventos */
    
    Route::middleware(['auth'])->group(function () {

    Route::prefix('admin')->group(function () {

        // CRUD de eventos (admin/eventos/...)
        Route::resource('eventos', EventoController::class);

        // Asignar recurso a un evento
        Route::post('eventos/{evento}/asignar-recurso',
            [EventoController::class, 'asignarRecurso']
        )->name('admin.eventos.asignarRecurso');

        // Quitar recurso de un evento
        Route::delete('eventos/{evento}/quitar-recurso/{recurso}',
            [EventoController::class, 'quitarRecurso']
        )->name('admin.eventos.quitarRecurso');

        });

    });



    /* Admin - inscripciones */
    Route::get('/admin/inscripciones/lista', function () {
        return view('admin.inscripciones.lista');
    })->name('admin.inscripciones.lista');

    /* Admin - recursos */
    Route::get('/admin/recursos/asignar', function () {
        return view('admin.recursos.asignar');
    })->name('admin.recursos.asignar');

    /* Ciudadano - inscripciones */
    Route::get('/ciudadano/buscar', function () {
        return view('ciudadano.inscripcion');
    })->name('ciudadano.buscar');

    Route::get('/ciudadano/detalle', function () {
        return view('ciudadano.detalle');
    })->name('ciudadano.detalle');

    Route::get('/ciudadano/inscripcion', function () {
        return view('ciudadano.inscripcion');
    })->name('ciudadano.inscripcion');

    Route::get('/ciudadano/lista', function () {
        return view('ciudadano.lista_inscripcion');
    })->name('ciudadano.lista_inscripcion');

    Route::get('/ciudadano/mostrar', function () {
        return view('ciudadano.mostrar');
    })->name('ciudadano.mostrar');

});
