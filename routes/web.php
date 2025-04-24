<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (acessíveis sem autenticação)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login')->name('root');

Route::middleware('guest')->group(function () {
    // Rotas de login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Rotas de registro
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'showRegistrationSelector'])
             ->name('register.selector');
        
        // Cadastro de Cliente
        Route::get('/client', [RegisterController::class, 'showCustomUserRegistrationForm'])
             ->name('register.custom-user.form');
        Route::post('/client', [CustomUserController::class, 'store'])
             ->name('register.custom-user.post'); // Alterado para .post

        // Cadastro de Prestador
        Route::get('/provider', [RegisterController::class, 'showProviderRegistrationForm'])
             ->name('register.provider.form');
        Route::post('/provider', [ProviderController::class, 'store'])
             ->name('register.provider');
    });

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
         ->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
         ->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (requerem autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])
             ->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])
             ->name('addresses.destroy');
    });
});

Route::get('/dashboard', fn() => redirect()->route('home'));

Route::fallback(function () {
    return auth()->check() 
        ? redirect()->route('home')
        : redirect()->route('login');
});