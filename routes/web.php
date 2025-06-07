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
    PortfolioController,
    PasswordResetController,
    HomeController
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
    Route::get('/password/forgot', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Rotas Comuns (auth:web,custom)
|--------------------------------------------------------------------------
| Rotas acessíveis tanto para Providers quanto para CustomUsers.
| Inclui logout, home, endereços e chat.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web,custom')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Página inicial - redireciona para home específica de acordo com tipo de usuário
    Route::get('/home', function () {
        if (auth('web')->check()) {
            return redirect()->route('provider.home');
        }
        if (auth('custom')->check()) {
            return redirect()->route('custom-user.home');
        }
        return redirect()->route('login');
    })->name('home');

    // CRUD de Endereços (Provider e CustomUser)
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.index');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });

    // Chat
    Route::get('/chats', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chats/service-request/{serviceRequest}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chats/{chat}/message', [ChatController::class, 'storeMessage'])->name('chat.message.store');

    // Atualização de status de ServiceRequest (Acesso Comum: Provider e CustomUser)
    Route::put('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');
    // Avaliação de CustomUser e Provider
    Route::get('/service-requests/{serviceRequest}/review', [ReviewController::class, 'create'])->name('service-requests.review');
    Route::post('/service-requests/{serviceRequest}/review', [ReviewController::class, 'store'])->name('service-requests.review.store');
});

/*
|--------------------------------------------------------------------------
| Rotas do Provider (auth:web)
|--------------------------------------------------------------------------
| Rotas específicas para Providers.
| Inclui perfil, categorias, portfólio, solicitações (Service Requests) e avaliações.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    // Perfil do Provider
    Route::prefix('provider/profile')->group(function () {
        Route::get('/', fn() => view('providers.show', ['provider' => auth()->user()]))->name('provider.profile.show');
        Route::get('/edit', [ProviderController::class, 'editProfile'])->name('provider.profile.edit');
        Route::put('/', [ProviderController::class, 'updateInfo'])->name('provider.profile.update');
        Route::post('/photo', [ProviderController::class, 'updateProfilePhoto'])->name('provider.profile.updatePhoto');
    });

    // Página inicial do Provider
    Route::get('/provider/home', fn() => view('providers.home'))->name('provider.home');


    // Categorias atendidas
    Route::get('/provider/categories', [ServiceCategoryController::class, 'showCategories'])->name('service_categories.show');
    Route::post('/provider/categories', [ServiceCategoryController::class, 'updateCategories'])->name('provider.categories.update');

    // Portfólio
    Route::prefix('provider/portfolio')->group(function () {
        // Rota para criar
        Route::get('/create', [PortfolioController::class, 'create'])->name('provider.portfolio.create');
        // Rota para armazenar
        Route::post('/', [PortfolioController::class, 'store'])->name('provider.portfolio.store');
        // **NOVA ROTA**: mostrar um portfólio específico
        Route::get('/{portfolio}', [PortfolioController::class, 'show'])->name('provider.portfolio.show');
        // Rota para editar
        Route::get('/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('provider.portfolio.edit');
        // Rota para atualizar
        Route::put('/{portfolio}', [PortfolioController::class, 'update'])->name('provider.portfolio.update');
        // Rota para excluir portfólio inteiro
        Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])->name('provider.portfolio.destroy');
        // Rota para excluir apenas UMA imagem/vídeo
        Route::delete('/{portfolio}/image', [PortfolioController::class, 'deleteImage'])->name('provider.portfolio.delete-image');
    });

    // Service Requests Recebidas
    Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('service-requests.show');

    // Propor valor (Duplo Aceite)
    Route::put('/service-requests/{serviceRequest}/propose', [ServiceRequestController::class, 'proposePrice'])->name('service-requests.propose-price');

    // Gerenciamento de categorias de serviço (Admins ou Providers autorizados)
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Rotas do CustomUser (auth:custom)
|--------------------------------------------------------------------------
| Rotas específicas para clientes (CustomUser).
| Inclui explorar Providers, solicitações (Service Requests) e avaliações.
|--------------------------------------------------------------------------
*/
Route::middleware('auth:custom')->group(function () {
    // Explorar Providers
    Route::prefix('explore')->group(function () {
        Route::get('/', [ExploreController::class, 'index'])->name('explore.index');
        Route::get('/category/{id}', [ExploreController::class, 'byCategory'])->name('explore.byCategory');
        Route::get('/provider/{id}', [ExploreController::class, 'showProvider'])->name('explore.provider.show');
    });

    // Página inicial do CustomUser
    Route::get('/custom-user/home', [HomeController::class, 'index'])->name('custom-user.home');

    // Criar Service Request
    Route::get('/provider/{provider}/request', [ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/provider/{provider}/request', [ServiceRequestController::class, 'store'])->name('service-requests.store');

    // Gerenciar minhas solicitações
    Route::get('/my-requests', [ServiceRequestController::class, 'index'])->name('custom-user.service-requests.index');
    Route::get('/my-requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('custom-user.service-requests.show');
    Route::put('/my-requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('custom-user.service-requests.cancel');

    // Aceitar ou Recusar proposta (Duplo Aceite)
    Route::put('/my-requests/{serviceRequest}/accept-proposal', [ServiceRequestController::class, 'acceptProposal'])->name('service-requests.accept-proposal');
    Route::put('/my-requests/{serviceRequest}/reject-proposal', [ServiceRequestController::class, 'rejectProposal'])->name('service-requests.reject-proposal');

    // Perfil do CustomUser
    Route::prefix('custom-user/profile')->middleware('auth:custom')->group(function () {
        Route::get('/', fn() => view('custom_users.show', ['customUser' => auth()->user()]))->name('custom-user.profile.show');
        Route::get('/edit', [CustomUserController::class, 'editProfile'])->name('custom-user.profile.edit');
        Route::put('/', [CustomUserController::class, 'updateProfile'])->name('custom-user.profile.update');
        Route::post('/update-photo', [CustomUserController::class, 'updateProfilePhoto'])->name('custom-user.profile.updatePhoto');
    });
});

/*
|--------------------------------------------------------------------------
| Dashboard & Fallback
|--------------------------------------------------------------------------
| Redirecionamento inteligente e fallback para rotas não encontradas.
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
})->name('dashboard');

Route::fallback(function () {
    return auth('web')->check() || auth('custom')->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});
