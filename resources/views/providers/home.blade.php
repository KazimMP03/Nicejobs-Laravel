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
                @if($avgRatingFormatted)
                <p class="mb-0" style="font-size: 0.9rem;">
                    {{ $avgRatingFormatted }} estrelas ({{ $totalReviews }} {{
                    $totalReviews === 1 ? 'avaliação' : 'avaliações' }})
                </p>
                @php
                $rating = floatval($avgRatingFormatted);
                @endphp

                @if ($rating <= 2.5)
                <small class="text-danger d-block mt-1">
                    <i class="fas fa-exclamation-triangle me-1"></i> Avaliação
                    baixa. Melhore seu atendimento!
                </small>
                @elseif ($rating < 4.0)
                <small class="text-warning d-block mt-1">
                    <i class="fas fa-info-circle me-1"></i> Avaliação mediana.
                    Ainda dá pra melhorar!
                </small>
                @else
                <small class="text-success d-block mt-1">
                    <i class="fas fa-gem me-1"></i> Muito bom! Continue assim.
                </small>
                @endif
                @else
                <p class="mb-0">Nenhuma avaliação ainda</p>
                <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle me-1"></i> Ainda não há
                    avaliações.
                </small>
                @endif
            </div>
        </div>

        {{-- Pedidos recebidos --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-inbox fa-2x text-info mb-2"></i>
                <h6 class="fw-bold mb-1">Pedidos recebidos</h6>

                <p class="mb-0" style="font-size: 0.9rem;">
                    {{ $totalRequests > 0 ? $totalRequests . ($totalRequests ===
                    1 ? ' pedido' : ' pedidos') : 'Nenhum pedido ainda' }}
                </p>

                @if ($totalRequests === 0 || $totalRequests <= 2)
                <small class="d-block mt-1 text-danger">
                    <i class="fas fa-times-circle me-1"></i> Poucos pedidos. É
                    hora de turbinar seu perfil!
                </small>
                @elseif ($totalRequests <= 5)
                <small class="d-block mt-1 text-warning">
                    <i class="fas fa-exclamation-triangle me-1"></i> Está indo,
                    mas ainda pode melhorar.
                </small>
                @else
                <small class="d-block mt-1 text-success">
                    <i class="fas fa-thumbs-up me-1"></i> Bom trabalho, continue
                    ativo!
                </small>
                @endif
            </div>
        </div>

        {{-- Último login --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-calendar-alt fa-2x text-secondary mb-2"></i>
                <h6 class="fw-bold mb-1">Último login</h6>
                <p class="mb-0">Há alguns minutos.</p>
                <small class="text-success d-block mt-1">
                    <i class="fas fa-clock me-1"></i> Muito bem, você é um
                    frequentador assíduo!
                </small>
            </div>
        </div>

        {{-- Perfil Completo --}}
        <div class="col-sm-6 col-lg-3">
            <div class="bg-white rounded shadow-sm text-center p-3">
                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                <h6 class="fw-bold mb-1">Perfil completo</h6>
                <p class="mb-0">{{ $profileCompletion }}% completo</p>

                @if($profileCompletion < 100 && $missingItemsMessage)
                <small class="d-block mt-1" style="color: orange;">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    {{ $missingItemsMessage }}
                </small>
                @elseif($profileCompletion === 100)
                <small class="d-block mt-1 text-success">
                    <i class="fas fa-check-circle me-1"></i> Parabéns! Perfil
                    100% completo.
                </small>
                @endif
            </div>
        </div>

    </div>

    {{-- AVALIAÇÕES --}}
    <div class="w-100 px-3 py-4 rounded shadow-sm mb-5"
        style="background-color: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06); font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <h5 class="fw-bold mb-4 text-dark text-center">Últimas avaliações
            recebidas</h5>

        @if ($latestReviews->isEmpty())
        <p class="text-muted text-center">Você ainda não recebeu avaliações.</p>
        @else
        <div class="row g-3 justify-content-center">
            @foreach ($latestReviews as $index => $review)
            @php
            $nome = $review->reviewer_name;
            $comentario = implode(' ', array_slice(explode(' ',
            $review->comment), 0, 3)) . (str_word_count($review->comment) > 3 ?
            '...' : '');
            $diasAtras =
            \Carbon\Carbon::parse($review->created_at)->diffForHumans();
            $nota = $review->rating;
            @endphp

            <div class="col-md-4">
                <div class="border rounded p-3 h-100 shadow-sm"
                    style="background-color: #f8f9fa;">
                    <div
                        class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">{{ $nome }}</strong>
                        @if ($index === 0)
                        <span class="badge bg-success">Nova</span>
                        @endif
                    </div>

                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $nota)
                        <i class="fas fa-star text-warning"></i>
                        @else
                        <i class="far fa-star text-warning"></i>
                        @endif
                        @endfor
                    </div>

                    <p class="mb-1">{{ $comentario }}</p>
                    <small class="text-muted">{{ ucfirst($diasAtras) }}</small>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- DICA DE MELHORIA --}}
    @if(isset($randomTip))
    <div class="alert alert-info text-center fw-semibold shadow-sm"
        role="alert">
        {{ $randomTip }}
    </div>
    @endif
</div>
@endsection
