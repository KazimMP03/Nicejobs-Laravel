<!-- Navbar Start -->
<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">

    <a href="#" class="sidebar-toggler flex-shrink-0 me-3">
        <i class="fa fa-bars"></i>
    </a>
    <img src="{{ asset('images/logo_big_rect_round.png') }}"
        id="sidebar-mini-logo" class="d-none ms-2" style="height: 30px;">

    <form class="d-flex ms-4 w-100" style="max-width: 400px;">
        <input class="form-control border-0" type="search"
            placeholder="Buscar serviços...">
    </form>
    <div class="navbar-nav align-items-center ms-auto">
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown">
                <i class="fa fa-th me-lg-2"></i>
                <span class="d-none d-lg-inline-flex">Navegar</span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="{{ route('home') }}"
                    class="dropdown-item">Home</a>
                <a href="{{ route('addresses.index') }}"
                    class="dropdown-item">Endereço</a>
                <a href="{{ route('chat.index') }}"
                    class="dropdown-item">Chat</a>

            </div>
        </div>

        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2"
                    src="{{ asset('images/user.png') }}" alt
                    style="width: 40px; height: 40px;">
                <span class="d-none d-lg-inline-flex">
                    @php
                    $user = session('user_type') === 'provider' ?
                    auth('web')->user() : auth('custom')->user();
                    $firstName = explode(' ', $user->user_name)[0];
                    @endphp
                    {{ $firstName }}
                </span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a
                    href="{{ session('user_type') === 'provider' ? route('provider.profile.show') : route('custom-user.profile.show') }}"
                    class="dropdown-item">
                    Perfil
                </a>
                <a href="#" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sair
                </a>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST"
        style="display: none;">
        @csrf
    </form>
</nav>
<!-- Navbar End -->
