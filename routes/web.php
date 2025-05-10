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
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ReviewController;

Route::redirect('/', '/login')->name('root');

/*
|--------------------------------------------------------------------------
| Rotas Públicas (sem autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterController::class, 'showRegistrationSelector'])->name('register.selector');

        // Cliente
        Route::get('/client', [RegisterController::class, 'showCustomUserRegistrationForm'])->name('register.custom-user.form');
        Route::post('/client', [CustomUserController::class, 'store'])->name('register.custom-user.post');

        // Prestador
        Route::get('/provider', [RegisterController::class, 'showProviderRegistrationForm'])->name('register.provider.form');
        Route::post('/provider', [ProviderController::class, 'store'])->name('register.provider');
    });

    // Recuperação de senha
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Rotas Públicas de Exploração
|--------------------------------------------------------------------------
| Permite busca por categoria, visualização de perfis, portfólios e avaliações
*/
Route::prefix('explore')->group(function () {
    Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
    Route::get('/category/{id}', [ExploreController::class, 'byCategory'])->name('explore.byCategory');
    Route::get('/provider/{id}', [ExploreController::class, 'showProvider'])->name('providers.show');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (requerem autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web,custom')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home
    Route::get('/home', fn () => view('home'))->name('home');


    // Endereços (gerenciados após o cadastro)
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit'); // ✅ Corrigido
        Route::put('/{address}', [AddressController::class, 'update'])->name('addresses.update'); // ✅ Corrigido
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

    // Gerenciamento de categorias do Provider (após cadastro)
    Route::get('/provider/categories', [ProviderController::class, 'editCategories'])->name('provider.categories.edit');
    Route::post('/provider/categories', [ProviderController::class, 'updateCategories'])->name('provider.categories.update');

    // Foto de perfil do Provider (edição separada)
    Route::get('/provider/profile', [ProviderController::class, 'editProfile'])->name('provider.profile.edit');
    Route::post('/provider/profile/photo', [ProviderController::class, 'updateProfilePhoto'])->name('provider.profile.photo');

    // Categorias de Serviço
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);

    // Serviços
    Route::resource('services', ServiceController::class);

    // Solicitações de Serviço
    Route::resource('service-requests', ServiceRequestController::class)->except(['create']);
    Route::get('services/{service}/request', [ServiceRequestController::class, 'create'])->name('service-requests.create');

    // Avaliações de Providers
    Route::post('/provider/{id}/review', [ReviewController::class, 'store'])->name('providers.review');
});

/*
|--------------------------------------------------------------------------
| Redirecionamentos e fallback
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => redirect()->route('home'));

Route::fallback(function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});
