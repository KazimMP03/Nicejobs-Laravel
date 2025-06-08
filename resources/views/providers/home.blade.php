@extends('layouts.app')

@section('content')
<div class="container explore-category py-4">
    {{-- TÍTULO --}}
    <div
        class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block;
                   font-size: 1.8rem;
                   border-color: #0d6efd;
                   font-family: 'Rubik', sans-serif;">
            HOME
        </h3>
    </div>

    {{-- BOAS-VINDAS --}}
    <div class="w-100 text-center mb-5 px-3 py-4 rounded shadow-sm"
        style="background-color: #ffffff;
               box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
               font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <h5 class="mb-0"
            style="color: #009CFF; font-weight: 500; line-height: 1.5;">
            Olá, {{ Auth::user()->user_name }} 👋<br>
            <span style="font-weight: 600; color: #2c3e50;">Veja um resumo do
                seu desempenho e mantenha seu perfil sempre atrativo para
                conquistar mais clientes, seguindo nossas dicas de ouro!</span>
        </h5>
    </div>

    {{-- PAINEL DE RESUMO EM CARDS --}}
    <div class="row g-3 mb-4">
        {{-- Avaliação Média --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-star fa-2x text-warning mb-2"></i>
                <h6 class="fw-bold mb-1">Avaliação média</h6>
                <p class="mb-0">4 estrelas (5 avaliações)</p>
                <small class="text-success d-block mt-1">
                    <i class="fas fa-gem me-1"></i> Muito bom! Continue assim.
                </small>
            </div>
        </div>

        {{-- Pedidos recebidos --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-inbox fa-2x text-info mb-2"></i>
                <h6 class="fw-bold mb-1">Pedidos recebidos</h6>
                <p class="mb-0">5 pedidos</p>
                <small class="d-block mt-1" style="color: orange;">
                    <i class="fas fa-arrow-down me-1"></i> Pouca demanda.
                    Reforce seu perfil!
                </small>
            </div>
        </div>

        {{-- Último login --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-calendar-alt fa-2x text-secondary mb-2"></i>
                <h6 class="fw-bold mb-1">Último login</h6>
                <p class="mb-0">3 dias atrás</p>
                <small class="text-danger d-block mt-1">
                    <i class="fas fa-clock me-1"></i> Volte com mais frequência!
                </small>
            </div>
        </div>

        {{-- Perfil Completo --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                <h6 class="fw-bold mb-1">Perfil completo</h6>
                <p class="mb-0">80% completo</p>
                <small class="d-block mt-1" style="color: orange;">
                    <i class="fas fa-exclamation-circle me-1"></i> Adicione uma
                    categoria e um portfólio!
                </small>

            </div>
        </div>
    </div>

    {{-- AVALIAÇÕES --}}
    <div class="w-100 px-3 py-4 rounded shadow-sm mb-5"
        style="background-color: #ffffff;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
           font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <h5 class="fw-bold mb-4 text-dark text-center">
            Últimas avaliações recebidas
        </h5>

        <div class="row g-3 justify-content-center">
            {{-- Avaliação 1 --}}
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 shadow-sm"
                    style="background-color: #f8f9fa;">
                    <div
                        class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">Ruy Toledo</strong>
                        <span class="badge bg-success">Nova</span>
                    </div>
                    <div class="mb-2">
                        @for ($i = 0; $i < 4; $i++)
                        <i class="fas fa-star text-warning"></i>
                        @endfor
                        <i class="fas fa-star-half-alt text-warning"></i>
                    </div>
                    <p class="mb-1">Excelente prestador.</p>
                    <small class="text-muted">Há 2 dias</small>
                </div>
            </div>

            {{-- Avaliação 2 --}}
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 shadow-sm"
                    style="background-color: #f8f9fa;">
                    <div
                        class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">Regiane Lopes</strong>
                    </div>
                    <div class="mb-2">
                        @for ($i = 0; $i < 4; $i++)
                        <i class="fas fa-star text-warning"></i>
                        @endfor
                    </div>
                    <p class="mb-1">Serviço bom.</p>
                    <small class="text-muted">Há 5 dias</small>
                </div>
            </div>

            {{-- Avaliação 3 --}}
            <div class="col-md-4">
                <div class="border rounded p-3 h-100 shadow-sm"
                    style="background-color: #f8f9fa;">
                    <div
                        class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">Afonso Rodrigues</strong>
                    </div>
                    <div class="mb-2">
                        @for ($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-warning"></i>
                        @endfor
                    </div>
                    <p class="mb-1">Caprichoso,impecável.</p>
                    <small class="text-muted">Há 1 semana</small>
                </div>
            </div>
        </div>
    </div>

    {{-- DICA DE MELHORIA --}}
    <div class="alert alert-info text-center fw-semibold shadow-sm"
        role="alert">
        💡 Dica: perfis com foto e descrição detalhada recebem até 3x mais
        pedidos!
    </div>
</div>
@endsection
