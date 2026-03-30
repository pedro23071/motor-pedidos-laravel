<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Dashboard\PedidoDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

// Home (opcional)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Login (redirige directo a GitHub)
Route::get('/login', function () {
    return redirect()->route('auth.github.redirect');
})->name('login');

// OAuth GitHub
Route::get('/auth/github/redirect', [OAuthController::class, 'redirectToGithub'])
    ->name('auth.github.redirect');

Route::get('/auth/github/callback', [OAuthController::class, 'handleGithubCallback'])
    ->name('auth.github.callback');

// Logout
Route::post('/logout', [OAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard React
    Route::get('/dashboard', [PedidoDashboardController::class, 'react'])
        ->name('dashboard');

    // API del dashboard (React)
    Route::get('/dashboard/data', [PedidoDashboardController::class, 'data'])
        ->name('dashboard.data');

});