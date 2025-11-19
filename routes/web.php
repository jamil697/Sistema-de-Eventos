<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () { return view('welcome'); });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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

Route::get('/admin/eventos/recursos', function () {
    return view('admin.recursos.asignar');
})->name('admin.eventos.recursos');

/* Ciudadano - inscripciones (rutas únicas, NO repetir la misma URI) */
Route::get('/ciudadano/inscripciones/buscar', function () {
    return view('ciudadano.inscripciones.buscar');
})->name('ciudadano.inscripciones.buscar');

Route::get('/ciudadano/inscripciones/detalle', function () {
    return view('ciudadano.inscripciones.detalle');
})->name('ciudadano.inscripciones.detalle');

Route::get('/ciudadano/inscripciones/inscripcion', function () {
    return view('ciudadano.inscripciones.inscripcion');
})->name('ciudadano.inscripciones.inscripcion');

Route::get('/ciudadano/inscripciones/lista', function () {
    return view('ciudadano.inscripciones.lista_inscripcion');
})->name('ciudadano.inscripciones.lista_inscripcion');

Route::get('/ciudadano/inscripciones/mostrar', function () {
    return view('ciudadano.inscripciones.mostrar');
})->name('ciudadano.inscripciones.mostrar');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
