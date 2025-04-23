{{-- resources/views/layouts/header.blade.php --}}

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Estilização do header -->
<link rel="stylesheet" href="{{ asset('css/components/header.css') }}">

<header class="header">
    <!-- Logo -->
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
        </a>
    </div>

    <!-- Endereços -->
    <a href="{{ route('addresses.index') }}" class="nav-link">
        <i class="fas fa-map-marker-alt"></i>
        <span class="nav-text">Endereços</span>
    </a>

    <!-- Chat -->
    <a href="#" class="nav-link chat">
        <i class="fas fa-comment-alt"></i>
        <span class="nav-text">Chats</span>
    </a>

    <!-- Barra de pesquisa -->
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Buscar serviços, profissionais e mais...">
        <button class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Perfil -->
    <a href="#" class="nav-link profile">
        <img src="{{ asset('images/logo.png') }}" class="profile-img" alt="Foto de perfil"/>
        <span class="nav-text profile-name">
            {{ strtok(auth()->user()->user_name, ' ') }}
        </span>
    </a>

    <!-- Favoritos -->
    <a href="#" class="nav-link favorites">
        <i class="fas fa-star"></i>
        <span class="nav-text">Favoritos</span>
    </a>

    <!-- Botão de Logout -->
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-button nav-link">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text">Sair</span>
        </button>
    </form>
</header>
