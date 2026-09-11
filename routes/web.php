<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudCompraController;
use App\Http\Controllers\SolicitudServicioController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('solicitudes-compra.index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::resource('solicitudes-compra', SolicitudCompraController::class);

    Route::resource('solicitudes-servicio', SolicitudServicioController::class);

    Route::resource('users', UserController::class);

    Route::patch('/solicitudes-compra/{solicitud}/estado', [SolicitudCompraController::class, 'updateStatus'])->name('solicitudes.update-status');

    Route::patch('/solicitudes-compra/{id}/estado', [SolicitudCompraController::class, 'updateStatus'])->name('solicitudes.update-status');

    Route::patch('/solicitudes-compra/{id}/cancelar', [SolicitudCompraController::class, 'cancelar'])
        ->name('solicitudes-compra.cancelar');

    Route::patch('/solicitudes-servicio/{id}/cancelar', [SolicitudServicioController::class, 'cancelar'])
        ->name('solicitudes-servicio.cancelar');
});

