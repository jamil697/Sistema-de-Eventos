<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Página principal → listado de eventos para ciudadanos
Route::get('/', function () {
    return view('eventos.editar');
})->name('eventos.editar');

// editar de un evento
Route::get('/admin/eventos/lista', function () {
    return view('eventos.lista');
})->name('eventos.lista');

// Registro a un evento
Route::get('/admin/eventos/mostrar', function () {
    return view('eventos.mostrar');
})->name('eventos.mostrar');

// lista de eventos
Route::get('/inscripciones/eventos/registrar', function () {
    return view('admin.eventos.registrar');
})->name('admin.eventos.registrar');

// Crear evento
Route::get('/admin/inscripciones/lista', function () {
    return view('admin.inscripciones.lista');
})->name('admin.inscripciones.lista');

// Editar evento
Route::get('/admin/recursos/asignar', function () {
    return view('admin.recursos.asignar');
})->name('admin.recursos.asignar');

// Asignar recursos
Route::get('/admin/eventos/recursos', function () {
    return view('admin.recursos.asignar');
})->name('admin.eventos.recursos');

// Lista de inscripciones
Route::get('/ciudadano/inscripciones', function () {
    return view('ciudadano.inscripciones.buscar');
})->name('ciudadano.inscripciones.buscar');

// Reportes
Route::get('/ciudadano/inscripciones', function () {
    return view('ciudadano.inscripciones.detalle');
})->name('ciudadano.inscripciones.detalle');

Route::get('/ciudadano/inscripciones', function () {
    return view('ciudadano.inscripciones.inscripcion');
})->name('ciudadano.inscripciones.inscripcion');

Route::get('/ciudadano/inscripciones', function () {
    return view('ciudadano.inscripciones.lista_inscripcion');
})->name('ciudadano.inscripciones.lista_inscripcion');

Route::get('/ciudadano/inscripciones', function () {
    return view('ciudadano.inscripciones.mostrar');
})->name('ciudadano.inscripciones.mostrar');