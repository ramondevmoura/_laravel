<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use App\Http\Controllers\DashboardController;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

// Página inicial
Route::middleware(['guest', 'redirect.role'])->get('/', fn () => inertia('Login'))->name('login');

// Autenticação
Route::middleware('guest')->post('/login', [AuthController::class, 'login']);
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth')->get('/user', [AuthController::class, 'user']);

// Dashboard genérico
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index']);

// Pedidos de viagem
Route::middleware('auth')->group(function () {
    Route::get('/viagens', [TravelRequestController::class, 'index']);
    Route::get('/viagens/{id}', [TravelRequestController::class, 'show']);
    Route::post('/viagens', [TravelRequestController::class, 'store']);
    Route::patch('/viagens/{id}/status', [TravelRequestController::class, 'updateStatus']);
    Route::post('/viagens/{id}/cancelar', [TravelRequestController::class, 'cancel']);
    Route::delete('/viagens/{id}', [TravelRequestController::class, 'destroy'])->middleware('auth');
    Route::put('/viagens/{id}', [TravelRequestController::class, 'update']); //

});

Route::middleware('auth')->get('/notificacoes', function () {
    return response()->json(Auth::user()->notifications()->latest()->get());
});
