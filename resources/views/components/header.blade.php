<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Estilização do header -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}">

<header class="header">
    <!-- Logo -->
    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
        </a>
    </div>

    <!-- Endereços -->
    <a href="" class="address">
        <i class="fas fa-map-marker-alt"></i>
        <span>Endereços</span>
    </a>

    <!-- Chat -->
    <a href="" class="chat">
        <i class="fas fa-comment-alt"></i>
        <span>Chats</span>
    </a>

    <!-- Barra de pesquisa -->
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Buscar serviços, profissionais e mais...">
        <button class="search-button">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <!-- Perfil -->
    <a href="" class="profile">
    <img src="{{ asset('images/logo.png') }}" class="profile-img"/>
        <span class="profile-name">Caio</span>
    </a>

    <!-- Favoritos -->
    <a href="" class="favorites">
        <i class="fas fa-star"></i>
        <span>Favoritos</span>
    </a>

    <!-- Botão de Logout -->
    <a href="">
        <i class="fas fa-sign-out-alt"></i>
        <span>Sair</span>
    </a>
</header>