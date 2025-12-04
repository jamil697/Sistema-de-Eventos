<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/eventos', function () {
        return view('admin.eventos.editar');
    })->name('admin.eventos.editar');

    Route::get('/lista', function () {
        return view('admin.eventos.lista');
    })->name('admin.eventos.lista');

    Route::get('/mostrar', function () {
        return view('admin.eventos.mostrar');
    })->name('admin.eventos.mostrar');

    Route::get('/registrar', function () {
        return view('admin.eventos.registrar');
    })->name('admin.eventos.registrar');

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

Route::prefix('admin')->group(function () {

        // CRUD de eventos
        Route::resource('eventos', App\Http\Controllers\EventoController::class);

        // Quitar recurso de un evento
        Route::delete('eventos/{evento}/quitar-recurso/{recurso}',
            [App\Http\Controllers\EventoController::class, 'quitarRecurso']
        )->name('admin.eventos.quitarRecurso');

     });    