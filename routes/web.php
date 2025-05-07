<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (acessíveis sem autenticação)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login')->name('root');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'showRegistrationSelector'])
             ->name('register.selector');

        // Cliente
        Route::get('/client', [RegisterController::class, 'showCustomUserRegistrationForm'])
             ->name('register.custom-user.form');
        Route::post('/client', [CustomUserController::class, 'store'])
             ->name('register.custom-user.post');

        // Prestador
        Route::get('/provider', [RegisterController::class, 'showProviderRegistrationForm'])
             ->name('register.provider.form');
        Route::post('/provider', [ProviderController::class, 'store'])
             ->name('register.provider');
    });

    // Esqueci a senha
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
         ->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
         ->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (requerem autenticação de qualquer guard)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:web,custom')->group(function () {
    // Logout único trata ambos os guards
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Endereços
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])
             ->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])
             ->name('addresses.destroy');
    });

    // Categorias de Serviço
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);

    // Serviços
    Route::resource('services', ServiceController::class);

    // Solicitações de Serviço
    Route::resource('service-requests', ServiceRequestController::class)->except(['create']);
    // formulário de solicitação de um serviço
    Route::get('services/{service}/request', [ServiceRequestController::class, 'create'])
         ->name('service-requests.create');
});

// Redirecionamentos finais
Route::get('/dashboard', fn() => redirect()->route('home'));

Route::fallback(function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});
