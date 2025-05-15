<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CustomUserController,
    ProviderController,
    AddressController,
    ServiceCategoryController,
    ServiceRequestController,
    ExploreController,
    ReviewController
};

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Guest)
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login')->name('root');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro de usuários
    Route::prefix('register')->group(function () {
        Route::get('/', fn() => view('auth.register-selector'))->name('register.selector');
        Route::get('/client', fn() => view('auth.register-custom-user'))->name('register.custom-user.form');
        Route::post('/client', [CustomUserController::class, 'store'])->name('register.custom-user.post');
        Route::get('/provider', fn() => view('auth.register-provider'))->name('register.provider.form');
        Route::post('/provider', [ProviderController::class, 'store'])->name('register.provider');
    });

    // Recuperação de senha
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Rotas Comuns (auth:web,custom)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web,custom')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home
    Route::get('/home', fn() => view('home'))->name('home');

    // Endereços (Provider e CustomUser)
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas do Provider (auth:web)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    // Perfil do Provider
    Route::get('/provider/profile', [ProviderController::class, 'editProfile'])->name('provider.profile.edit');
    Route::post('/provider/profile/update-info', [ProviderController::class, 'updateInfo'])->name('provider.profile.updateInfo');
    Route::post('/provider/profile/update-photo', [ProviderController::class, 'updateProfilePhoto'])->name('provider.profile.updatePhoto');

    // Categorias do Provider
    Route::get('/provider/categories', [ProviderController::class, 'showCategories'])->name('provider.categories.edit');
    Route::post('/provider/categories', [ProviderController::class, 'updateCategories'])->name('provider.categories.update');

    // ServiceRequests recebidas
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::put('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');

    // Avaliar CustomUser
    Route::post('/service-request/{serviceRequest}/review', [ReviewController::class, 'store'])->name('service-requests.review');

    // ServiceCategories (admin)
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Rotas do CustomUser (auth:custom)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:custom')->group(function () {
    // Explorar Providers por Categoria
    Route::prefix('explore')->group(function () {
        Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
        Route::get('/category/{id}', [ExploreController::class, 'byCategory'])->name('explore.byCategory');
        Route::get('/provider/{id}', [ExploreController::class, 'showProvider'])->name('providers.show');
    });

    // Criar uma nova ServiceRequest
    Route::get('/provider/{provider}/request', [ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/provider/{provider}/request', [ServiceRequestController::class, 'store'])->name('service-requests.store');

    // Minhas solicitações (CustomUser)
    Route::get('/my-requests', [ServiceRequestController::class, 'index'])->name('custom-user.service-requests.index');
    Route::get('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('custom-user.service-requests.show');
    Route::put('/my-requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('custom-user.service-requests.cancel');

    // Avaliar Provider
    Route::post('/provider/{id}/review', [ReviewController::class, 'store'])->name('providers.review');
});

/*
|--------------------------------------------------------------------------
| Dashboard & Fallback
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

Route::fallback(function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});
