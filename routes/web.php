<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InscripcionController;

/* Página pública */
/*Route::get('/', function () {
    return view('welcome');
});*/

    Route::get('/', [EventoController::class, 'index'])->name('eventos.publicos');

/* Rutas de autenticación (deben quedar fuera del middleware) */
    Auth::routes();

/* TODO LO SIGUIENTE REQUIERE LOGIN */
    Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

//-----------------------------------------------------------------------
    /* Admin - eventos */

    Route::get('/eventos/lista', [EventoController::class, 'adminIndex'])->name('eventos.admin.index');

    Route::resource('eventos', EventoController::class)->except(['index']);

    Route::get('/mis-inscripciones', [InscripcionController::class, 'index'])->name('ciudadano.inscripciones.lista');

    /*Route::get('/eventos', function () {
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
    })->name('admin.eventos.registrar');*/

    /* Admin - inscripciones */
    /*Route::get('/admin/inscripciones/lista', function () {
        return view('admin.inscripciones.lista');
    })->name('admin.inscripciones.lista');*/

    /* Admin - recursos */
    /*Route::get('/admin/recursos/asignar', function () {
        return view('admin.recursos.asignar');
    })->name('admin.recursos.asignar');*/



//--------------------------------------------------------------------------------
    /* Ciudadano - inscripciones */

    // muestra el formulario de inscripción para un evento específico
    Route::get('/eventos/{evento}/inscribir', [InscripcionController::class, 'create'])->name('inscripcion.create');

    // para guardas datos del formulario
    Route::post('/eventos/{evento}/inscribir', [InscripcionController::class, 'store'])->name('inscripcion.store');





    /*Route::get('/ciudadano/buscar', function () {
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
    })->name('ciudadano.mostrar');*/

});
