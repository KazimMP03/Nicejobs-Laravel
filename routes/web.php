<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CustomUserController,
    ProviderController,
    AddressController,
    ServiceCategoryController,
    ServiceController,
    ServiceRequestController,
    ExploreController,
    ReviewController
};

Route::redirect('/', '/login')->name('root');

/*
|--------------------------------------------------------------------------
| Rotas Públicas (sem autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Página de login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro de usuário (serve para Provider e CustomUser)
    Route::prefix('register')->group(function () {
        Route::get('/', fn() => view('auth.register-selector'))->name('register.selector');
        // CustomUser
        Route::get('/client', fn() => view('auth.register-custom-user'))->name('register.custom-user.form');
        Route::post('/client', [CustomUserController::class, 'store'])->name('register.custom-user.post');
        // Provider
        Route::get('/provider', fn() => view('auth.register-provider'))->name('register.provider.form');
        Route::post('/provider', [ProviderController::class, 'store'])->name('register.provider');
    });

    // Esqueceu a senha
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
});

/*
|--------------------------------------------------------------------------
| Rotas Comuns (auth:web,custom) | Provider e CustomUser
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web,custom')->group(function () {
    // Logout do sistema
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Página inicial (Home)
    Route::get('/home', fn() => view('home'))->name('home');

    // Rotas de endereço
    Route::prefix('addresses')->group(function () {
        // Lista de endereços
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');

        // Criação de endereços
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');

        // Edição de endereços
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit'); 
        Route::put('/{address}', [AddressController::class, 'update'])->name('addresses.update');

        // Endereço padrão
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault'); 
        
        // Para excluir um endereço
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas do Provider (auth:web)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    // Ainda não foi implementado a lógica para perfil de provider
    // Route::get('/provider/profile', [ProviderController::class, 'editProfile'])->name('provider.profile.edit');
    // Route::post('/provider/profile/photo', [ProviderController::class, 'updateProfilePhoto'])->name('provider.profile.photo');

    // Lista as categorias de serviços
    Route::get('/provider/categories', [ProviderController::class, 'showCategories'])->name('provider.categories.edit');

    // Permite que o Provider associe-se a categorias de serviços
    Route::post('/provider/categories', [ProviderController::class, 'updateCategories'])->name('provider.categories.update');

    Route::resource('services', ServiceController::class);
    Route::resource('service-requests', ServiceRequestController::class)->except(['create']);

    Route::post('/custom-user/{id}/review', [ReviewController::class, 'storeForCustomUser'])->name('custom-users.review');
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Rotas do CustomUser (auth:custom)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:custom')->group(function () {
    Route::prefix('explore')->group(function () {
        Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
        Route::get('/category/{id}', [ExploreController::class, 'byCategory'])->name('explore.byCategory');
        Route::get('/provider/{id}', [ExploreController::class, 'showProvider'])->name('providers.show');
    });

    Route::get('services/{service}/request', [ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('services/{service}/request', [ServiceRequestController::class, 'store'])->name('service-requests.store');

    Route::get('/my-requests', [ServiceRequestController::class, 'index'])->name('custom-user.service-requests.index');
    Route::get('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('custom-user.service-requests.show');
    Route::put('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('custom-user.service-requests.update');
    Route::delete('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'destroy'])->name('custom-user.service-requests.destroy');

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
