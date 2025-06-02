<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{ route('home') }}" class="navbar-brand mx-4 mb-3">
            <img src="{{ asset('images/logo_big_rect_round.png') }}"
                height="53">
        </a>

        <div class="d-flex align-items-center ms-4 mt-3 mb-4">
            <div class="position-relative">
                @php
                    $user = session('user_type') === 'provider' ? auth('web')->user() : auth('custom')->user();
                    $profilePhoto = $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : asset('images/user.png');
                @endphp

                <img class="rounded-circle"
                    src="{{ $profilePhoto }}"
                    alt="Foto de perfil"
                    style="width: 40px; height: 40px;">

                <div
                    class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                @php
                $user = session('user_type') === 'provider' ?
                auth('web')->user() : auth('custom')->user();
                $firstName = explode(' ', $user->user_name)[0];
                @endphp
                <h6 class="mb-0">{{ $firstName }}</h6>
                <span>{{ session('user_type') === 'provider' ? 'Prestador' :
                    'Cliente' }}</span>
            </div>
        </div>

        <div class="navbar-nav w-100">
            <a href="{{ route('home') }}"
                class="nav-item nav-link {{ request()->routeIs('provider.home') || request()->routeIs('custom-user.home') ? 'active' : '' }}">

                <i class="fa fa-home me-2"></i>Home
            </a>

            <a
                href="{{ session('user_type') === 'provider' ? route('provider.profile.show') : route('custom-user.profile.show') }}"
                class="nav-item nav-link {{ request()->routeIs('provider.profile.show') || request()->routeIs('custom-user.profile.show') ? 'active' : '' }}">
                <i class="fa fa-user me-2"></i>Perfil
            </a>

            <a href="{{ route('addresses.index') }}"
                class="nav-item nav-link {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt me-2"></i>Endereço
            </a>

            <a href="{{ route('chat.index') }}"
                class="nav-item nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}">
                <i class="fa fa-comments me-2"></i>Chat
            </a>

            {{-- Botão de Sair --}}
            <a href="#" class="nav-item nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out-alt me-2"></i>Sair
            </a>
        </div>
    </nav>

    {{-- Formulário de logout --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST"
        style="display: none;">
        @csrf
    </form>
</div>
<!-- Sidebar End -->
