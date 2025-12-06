<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistracionController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function(){
    Route::resource('events', EventController::class);
    Route::post('events/{event}/register', [RegistracionController::class,'store'])->name('events.register');
    Route::delete('events/{event}/register', [RegistracionController::class,'destroy'])->name('events.unregister');

    Route::resource('resources', ResourceController::class)->middleware('auth');
    // NUEVO: ver eventos en los que estoy inscrito
    Route::get('/mis-eventos', [RegistracionController::class, 'misEventos'])->name('mis-eventos');
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notifications.index');

    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])
        ->name('admin.index');

});
Route::get('/', [EventController::class,'index']);