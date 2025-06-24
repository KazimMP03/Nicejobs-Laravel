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
        {{-- Dropdown Navegar --}}
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown">
                <i class="fa fa-th me-lg-2"></i>
                <span class="d-none d-lg-inline-flex">Navegar</span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                {{-- Comum --}}
                <a href="{{ route('home') }}" class="dropdown-item">Home</a>
                <a href="{{ route('addresses.index') }}"
                    class="dropdown-item">Endereço</a>
                <a href="{{ route('chat.index') }}"
                    class="dropdown-item">Chat</a>

                {{-- Exclusivo CustomUser --}}
                @if(session('user_type') === 'custom_user')
                <a href="{{ route('explore.index') }}"
                    class="dropdown-item">Explorar</a>
                <a href="{{ route('custom-user.service-requests.index') }}"
                    class="dropdown-item">Minhas Solicitações</a>
                @endif

                {{-- Exclusivo Provider --}}
                @if(session('user_type') === 'provider')
                @php
                $provider = auth('web')->user();
                $portfolio = $provider &&
                $provider->relationLoaded('portfolios')
                ? $provider->portfolios->first()
                : \App\Models\Portfolio::where('provider_id',
                $provider?->id)->first();
                @endphp

                @if($portfolio)
                <a href="{{ route('provider.portfolio.show', $portfolio) }}"
                    class="dropdown-item">Portfólio</a>
                @else
                <a href="{{ route('provider.portfolio.create') }}"
                    class="dropdown-item">Portfólio</a>
                @endif

                <a href="{{ route('service_categories.show') }}"
                    class="dropdown-item">Categorias</a>
                <a href="{{ route('service-requests.index') }}"
                    class="dropdown-item">Solicitações</a>
                @endif
            </div>
        </div>

        {{-- Dropdown do Usuário --}}
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle"
                data-bs-toggle="dropdown">
                @php
                $isProvider = session('user_type') === 'provider';
                $user = $isProvider ? auth('web')->user() :
                auth('custom')->user();
                $profilePhoto = $user && $user->profile_photo
                ? asset('storage/' . $user->profile_photo)
                : asset('images/user.png');
                $firstName = $user ? explode(' ', $user->user_name)[0] :
                'Usuário';
                @endphp

                <img class="rounded-circle"
                    src="{{ $profilePhoto }}"
                    alt="Foto de perfil"
                    style="width: 40px; height: 40px; object-fit: cover;">

                <span class="d-none d-lg-inline-flex">{{ $firstName }}</span>
            </a>
            <div
                class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a
                    href="{{ $isProvider ? route('provider.profile.show') : route('custom-user.profile.show') }}"
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
