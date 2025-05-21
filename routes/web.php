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
    ReviewController,
    ChatController,
    PortfolioController
};

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Guest)
|--------------------------------------------------------------------------
| Acesso livre para usuários não autenticados.
| Inclui login, registro e recuperação de senha.
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login')->name('root');

// Agrupamento de rotas públicas para guests (não autenticados)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Registro de usuários (CustomUser e Provider)
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
| Rotas acessíveis tanto para Providers quanto para CustomUsers.
| Inclui home, logout, endereços e chat.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web,custom')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Página inicial
    Route::get('/home', fn() => view('home'))->name('home');

    // CRUD de endereços (tanto Provider quanto CustomUser)
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

    // Chat entre Provider e CustomUser
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/service-request/{serviceRequest}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{chat}/message', [ChatController::class, 'storeMessage'])->name('chat.message.store');
});

/*
|--------------------------------------------------------------------------
| Rotas do Provider (auth:web)
|--------------------------------------------------------------------------
| Rotas específicas para usuários do tipo Provider.
| Inclui perfil, categorias, portfólio, service requests e avaliações.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    // Gerenciamento do perfil do Provider
    Route::get('/provider/profile', [ProviderController::class, 'editProfile'])->name('provider.profile.edit');
    Route::post('/provider/profile/update-info', [ProviderController::class, 'updateInfo'])->name('provider.profile.updateInfo');
    Route::post('/provider/profile/update-photo', [ProviderController::class, 'updateProfilePhoto'])->name('provider.profile.updatePhoto');

    // Gerenciamento das categorias que o Provider atende
    Route::get('/provider/categories', [ProviderController::class, 'showCategories'])->name('provider.categories.edit');
    Route::post('/provider/categories', [ProviderController::class, 'updateCategories'])->name('provider.categories.update');

    // CRUD de Portfólio
    Route::get('/provider/portfolio/create', [PortfolioController::class, 'create'])->name('provider.portfolio.create'); // Criação
    Route::post('/provider/portfolio', [PortfolioController::class, 'store'])->name('provider.portfolio.store'); // Armazenamento
    Route::get('/provider/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('provider.portfolio.edit'); // Edição
    Route::put('/provider/portfolio/{portfolio}', [PortfolioController::class, 'update'])->name('provider.portfolio.update'); // Atualização
    Route::delete('/provider/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('provider.portfolio.destroy'); // Exclusão completa
    Route::delete('/provider/portfolio/{portfolio}/image', [PortfolioController::class, 'deleteImage'])->name('provider.portfolio.delete-image'); // Exclusão de imagem individual

    // Gerenciamento de Service Requests recebidas
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::put('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');

    // Avaliação de clientes (CustomUser)
    Route::post('/service-request/{serviceRequest}/review', [ReviewController::class, 'store'])->name('service-requests.review');

    // Gerenciamento de categorias de serviço (restrito a administradores ou Providers autorizados)
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Rotas do CustomUser (auth:custom)
|--------------------------------------------------------------------------
| Rotas específicas para usuários do tipo CustomUser.
| Inclui explorar Providers, criar solicitações, gerenciar solicitações e avaliações.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:custom')->group(function () {
    // Explorar Providers por categoria ou individualmente
    Route::prefix('explore')->group(function () {
        Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
        Route::get('/category/{id}', [ExploreController::class, 'byCategory'])->name('explore.byCategory');
        Route::get('/provider/{id}', [ExploreController::class, 'showProvider'])->name('providers.show');
    });

    // Criar uma nova Service Request para um Provider
    Route::get('/provider/{provider}/request', [ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/provider/{provider}/request', [ServiceRequestController::class, 'store'])->name('service-requests.store');

    // Gerenciar minhas solicitações (Service Requests) como CustomUser
    Route::get('/my-requests', [ServiceRequestController::class, 'index'])->name('custom-user.service-requests.index');
    Route::get('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('custom-user.service-requests.show');
    Route::put('/my-requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('custom-user.service-requests.cancel');

    // Avaliar Providers após conclusão da Service Request
    Route::post('/provider/{id}/review', [ReviewController::class, 'store'])->name('providers.review');
});

/*
|--------------------------------------------------------------------------
| Dashboard & Fallback
|--------------------------------------------------------------------------
| Redirecionamento inteligente baseado em autenticação.
| Se o usuário estiver logado, vai para /home, senão volta para /login.
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
})->name('dashboard');

// Fallback para qualquer rota não encontrada
Route::fallback(function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});
