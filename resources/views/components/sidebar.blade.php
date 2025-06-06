<!-- resources/views/partials/sidebar.blade.php -->
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <!-- Logo / Home -->
        <a href="{{ route('home') }}" class="navbar-brand mx-4 mb-3">
            <img src="{{ asset('images/logo_big_rect_round.png') }}" height="53" alt="Logo">
        </a>

        <!-- Perfil do usuário -->
        <div class="d-flex align-items-center ms-4 mt-3 mb-4">
            <div class="position-relative">
                @php
                    // Define se o usuário logado é provider (auth:web) ou custom_user (auth:custom)
                    $user = session('user_type') === 'provider'
                        ? auth('web')->user()
                        : auth('custom')->user();
                    $profilePhoto = $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : asset('images/user.png');
                @endphp

                <img class="rounded-circle"
                     src="{{ $profilePhoto }}"
                     alt="Foto de perfil"
                     style="width: 40px; height: 40px;">

                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                @php
                    $firstName = explode(' ', $user->user_name)[0];
                @endphp
                <h6 class="mb-0">{{ $firstName }}</h6>
                <span>
                    {{ session('user_type') === 'provider' ? 'Prestador' : 'Cliente' }}
                </span>
            </div>
        </div>

        <!-- Navegação -->
        <div class="navbar-nav w-100">
            {{-- Bloco Comum: Home, Endereço e Chat --}}
            <a href="{{ route('home') }}"
               class="nav-item nav-link {{ request()->routeIs('provider.home') || request()->routeIs('custom-user.home') ? 'active' : '' }}">
                <i class="fa fa-home me-2"></i>Home
            </a>

            <a href="{{ route('addresses.index') }}"
               class="nav-item nav-link {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt me-2"></i>Endereço
            </a>

            <a href="{{ route('chat.index') }}"
               class="nav-item nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}">
                <i class="fa fa-comments me-2"></i>Chat
            </a>

            {{-- Bloco exclusivo para Provider --}}
            @if(session('user_type') === 'provider')
                <!-- Link para perfil do Provider -->
                <a href="{{ route('provider.profile.show') }}"
                   class="nav-item nav-link {{ request()->routeIs('provider.profile.*') ? 'active' : '' }}">
                    <i class="fa fa-user me-2"></i>Perfil
                </a>

                @php
                    /** @var \App\Models\Provider $provider */
                    $provider = auth('web')->user();
                    $portfolio = $provider && $provider->relationLoaded('portfolios')
                        ? $provider->portfolios->first()
                        : \App\Models\Portfolio::where('provider_id', $provider?->id)->first();
                @endphp

                @if($portfolio)
                    <!-- Se já existe portfólio: link para visualizar -->
                    <a href="{{ route('provider.portfolio.show', $portfolio) }}"
                       class="nav-item nav-link {{ request()->routeIs('provider.portfolio.show') ? 'active' : '' }}">
                        <i class="fa fa-briefcase me-2"></i>Portfólio
                    </a>
                @else
                    <!-- Se não existe portfólio: link para criar -->
                    <a href="{{ route('provider.portfolio.create') }}"
                       class="nav-item nav-link {{ request()->routeIs('provider.portfolio.create') ? 'active' : '' }}">
                        <i class="fa fa-briefcase me-2"></i>Portfólio
                    </a>
                @endif

                <!-- Categorias atendidas -->
                <a href="{{ route('service_categories.show') }}"
                   class="nav-item nav-link {{ request()->routeIs('service_categories.show') ? 'active' : '' }}">
                    <i class="fa fa-list me-2"></i>Categorias
                </a>

                <!-- Solicitações de ServiceRequest recebidas -->
                <a href="{{ route('service-requests.index') }}"
                   class="nav-item nav-link {{ request()->routeIs('service-requests.index') ? 'active' : '' }}">
                    <i class="fa fa-tasks me-2"></i>Solicitações
                </a>
            @endif

            {{-- Bloco exclusivo para CustomUser --}}
            @if(session('user_type') === 'custom_user')
                <!-- Perfil do CustomUser -->
                <a href="{{ route('custom-user.profile.show') }}"
                   class="nav-item nav-link {{ request()->routeIs('custom-user.profile.*') ? 'active' : '' }}">
                    <i class="fa fa-user me-2"></i>Perfil
                </a>

                <!-- Explorar Providers -->
                <a href="{{ route('explore.index') }}"
                   class="nav-item nav-link {{ request()->routeIs('explore.*') ? 'active' : '' }}">
                    <i class="fa fa-search me-2"></i>Explorar
                </a>

                <!-- Minhas Solicitações -->
                <a href="{{ route('custom-user.service-requests.index') }}"
                   class="nav-item nav-link {{ request()->routeIs('custom-user.service-requests.*') ? 'active' : '' }}">
                    <i class="fa fa-clipboard-list me-2"></i>Solicitações
                </a>
            @endif

            {{-- Botão de Sair --}}
            <a href="#" class="nav-item nav-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out-alt me-2"></i>Sair
            </a>
        </div>
    </nav>

    {{-- Formulário de logout (oculto) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
