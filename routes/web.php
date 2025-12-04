<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\NotificationController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function(){
    Route::resource('events', EventController::class);
    Route::post('events/{event}/register', [RegistrationController::class,'store'])->name('events.register');
    Route::delete('events/{event}/register', [RegistrationController::class,'destroy'])->name('events.unregister');

    Route::resource('recursos', ResourceController::class)->middleware('auth');
    // NUEVO: ver eventos en los que estoy inscrito
    Route::get('/mis-eventos', [RegistrationController::class, 'misEventos'])->name('mis-eventos');
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');

});
Route::get('/', [EventController::class,'index']);