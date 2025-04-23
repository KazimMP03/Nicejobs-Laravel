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

// Redirecionamento raiz
Route::get('/', function () {
     return redirect()->route('login');
});

// Rotas de login
Route::middleware('guest')->group(function () {
     Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
     Route::post('/login', [AuthController::class, 'login'])->name('login.post');

     // Rotas de registro
     Route::get('/register/custom-user', [RegisterController::class, 'showCustomUserRegistrationForm'])
          ->name('register.custom-user.form');

     Route::post('/register/custom-user', [CustomUserController::class, 'store'])
          ->name('register.custom-user');

     Route::get('/register/provider', [RegisterController::class, 'showProviderRegistrationForm'])
          ->name('register.provider.form');

     Route::post('/register/provider', [ProviderController::class, 'store'])
          ->name('register.provider');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (requerem autenticação)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
     // Rota de logout
     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

     // Rota da home/dashboard
     Route::get('/home', [HomeController::class, 'index'])->name('home');

     // Gestão de endereços (rotas individuais + resource)
     Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
     Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
     Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
     
     // Rotas RESTful completas (exceto visualização individual)
     Route::resource('addresses', AddressController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| Redirecionamentos Personalizados
|--------------------------------------------------------------------------
*/

// Redirecionamento após login bem-sucedido
Route::get('/dashboard', function () {
     return redirect()->route('home');
});

// Redirecionamento após registro bem-sucedido
Route::get('/register/success', function () {
     return redirect()->route('login')->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
})->name('register.success');