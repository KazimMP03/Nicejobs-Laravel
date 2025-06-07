@extends('layouts.app')

@section('content')
<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        MEU PERFIL
    </h3>

    <a href="{{ route('provider.profile.edit') }}"
        class="btn btn-primary fw-bold">
        <i class="fas fa-edit me-2"></i> Editar Perfil
    </a>
</div>

<div class="d-flex justify-content-center align-items-center w-100" style="flex: 1; margin-top: 40px;">
    <table class="table mx-auto shadow-sm rounded bg-white"
        style="width: 800px; border-collapse: separate; border-spacing: 0.5rem 0.5rem;">
        
        @php
            // Definir URL da foto de perfil (ou placeholder genérico)
            $profilePhoto = $provider->profile_photo
                ? asset('storage/' . $provider->profile_photo)
                : asset('images/user.png');
        @endphp

        <tr>
            {{-- COLUNA ESQUERDA: FOTO + NOME + DESCRIÇÃO --}}
            <td class="text-center align-middle border-0 px-4 py-3 ms-3"
                style="background-color: #f8f9fa; border-radius: 0.5rem 0 0 0.5rem;">
                <img src="{{ $profilePhoto }}" alt="Foto do Prestador"
                    class="rounded-circle mb-3 shadow"
                    style="width: 140px; height: 140px; object-fit: cover;">
                <h4 class="fw-bold text-dark mb-2">
                    {!! wordwrap(e($provider->user_name), 25, '<br>', true) !!}
                </h4>
                @if($provider->provider_description)
                    <p class="text-muted" style="max-width: 200px; margin: 0 auto; font-size: 0.9rem; line-height: 1.2;">
                        {!! wordwrap(e($provider->provider_description), 25, '<br>', true) !!}
                    </p>
                @endif
            </td>

            {{-- TÍTULOS DOS CAMPOS --}}
            <td class="align-middle text-center fw-bold border-0 px-4 py-3 text-secondary"
                style="white-space: nowrap; background-color: #ffffff;">
                <p class="mb-3 mt-3">Tipo:</p>
                <p class="mb-3">E-mail:</p>
                <p class="mb-3">Telefone:</p>
                <p class="mb-3">CPF/CNPJ:</p>
                @if($provider->user_type === 'PF')
                    <p class="mb-3">Nascimento:</p>
                @else
                    <p class="mb-3">Fundação:</p>
                @endif
                <p class="mb-3">Disponibilidade:</p>
                <p class="mb-3">Alcance (KM):</p>
            </td>

            {{-- VALORES DOS CAMPOS --}}
            <td class="align-middle text-start border-0 px-4 py-3 text-dark"
                style="background-color: #ffffff;">
                <p class="mb-3 mt-3">{{ $provider->user_type }}</p>
                <p class="mb-3">{{ $provider->email }}</p>
                <p class="mb-3">{{ $provider->phone }}</p>
                <p class="mb-3">{{ $provider->tax_id }}</p>
                @if($provider->user_type === 'PF')
                    <p class="mb-3">{{ $provider->birth_date?->format('d/m/Y') }}</p>
                @else
                    <p class="mb-3">{{ $provider->foundation_date?->format('d/m/Y') }}</p>
                @endif
                <p class="mb-3">{{ ucfirst($provider->availability) }}</p>
                <p class="mb-3">{{ $provider->work_radius }}</p>
            </td>
        </tr>
    </table>
</div>

{{-- Seções adicionais: Categorias, Portfólio e Avaliações --}}
<div class="container mt-5" style="width: 850px;">

    {{-- CATEGORIAS DE SERVIÇO --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Categorias de serviço
        </h4>

        @if($provider->categories->isNotEmpty())
            <div class="d-flex flex-wrap gap-3">
                @foreach($provider->categories as $category)
                    <span class="text-white px-4 py-2"
                        style="
                            background: linear-gradient(90deg, var(--bs-primary), #6610f2);
                            border-radius: 1rem;
                            font-size: 0.95rem;
                            font-weight: 500;
                        ">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Nenhuma categoria selecionada.</p>
        @endif
    </section>

    {{-- PORTFÓLIO --}}
    <section class="mb-5 p-4 shadow-sm rounded bg-light">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Portfólio
        </h4>

        @php
            $portfolio = $provider->portfolios->first();
        @endphp

        @if($portfolio)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-center">{{ $portfolio->title }}</h5>
                    <p class="card-text text-secondary text-center">
                        {{ $portfolio->description }}
                    </p>

                    {{-- Container cinza para miniaturas --}}
                    <div
                        class="mx-auto"
                        style="
                            background-color: #f8f9fa;
                            border-radius: 0.5rem;
                            padding: 20px;
                            max-width: 500px;
                        ">
                        <div class="d-flex flex-wrap justify-content-center" style="gap: 20px;">
                            @foreach($portfolio->media_paths as $path)
                                @php
                                    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                @endphp

                                <div
                                    style="
                                        width: 140px;
                                        height: 140px;
                                        overflow: hidden;
                                        border-radius: 0.5rem;
                                    ">
                                    @if(in_array($extension, ['mp4','mov','avi','ogg']))
                                        {{-- Exibe vídeo --}}
                                        <video
                                            src="{{ asset('storage/' . $path) }}"
                                            controls
                                            class="img-fluid"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                        ></video>
                                    @else
                                        {{-- Exibe imagem --}}
                                        <img
                                            src="{{ asset('storage/' . $path) }}"
                                            alt="Imagem do Portfólio"
                                            class="img-fluid"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                        >
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- Fim do container cinza --}}

                    {{-- Linha separadora com menos margem inferior --}}
                    <hr class="mt-3 mb-1" style="border-top: 1px solid #dee2e6;">

                    {{-- Botão centralizado e com cor primária --}}
                    <div class="card-footer bg-white border-0 text-center py-2">
                        <a
                            href="{{ route('provider.portfolio.edit', $portfolio) }}"
                            class="btn btn-primary fw-bold">
                            <i class="fas fa-edit me-1"></i> Editar Portfólio
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Caso o prestador ainda não tenha portfólio --}}
            <p class="text-muted mb-3 text-center">Você ainda não adicionou um portfólio.</p>

            {{-- Botão “Criar Portfólio” centralizado e primário --}}
            <div class="text-center">
                <a
                    href="{{ route('provider.portfolio.create') }}"
                    class="btn btn-primary fw-bold">
                    <i class="fas fa-plus me-1"></i> Criar Portfólio
                </a>
            </div>
        @endif
    </section>

    {{-- AVALIAÇÕES RECEBIDAS (estilo iFood) --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        @php
            // Calcula a média de rating e converge para 1 casa decimal
            $avgRating = $provider->reviews->avg('rating') ? number_format($provider->reviews->avg('rating'), 1) : null;
            // Total de avaliações
            $totalReviews = $provider->reviews->count();
            // Para exibir estrelas cheias e vazias (somente em inteiros)
            if ($avgRating) {
                $fullStars = floor($avgRating);
                $hasHalf = ($avgRating - $fullStars) >= 0.5;
                $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
            } else {
                $fullStars = $emptyStars = 0;
                $hasHalf = false;
            }
        @endphp

        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Avaliações
        </h4>

        @if($totalReviews > 0)
            {{-- Container clicável: abre o modal ao clicar em qualquer parte deste bloco --}}
            <div
                class="d-flex align-items-center mb-3"
                data-bs-toggle="modal"
                data-bs-target="#reviewsModal"
                style="cursor: pointer;"
            >
                {{-- Nome do prestador (clicável) --}}
                <h5 class="mb-0 me-3 fw-semibold text-dark" style="font-size: 1.125rem;">
                    {{ $provider->user_name }}
                </h5>

                {{-- Estrelas médias e número de avaliações (também clicável) --}}
                <div class="d-flex align-items-center">
                    @for($i = 0; $i < $fullStars; $i++)
                        <i class="fas fa-star text-warning me-1"></i>
                    @endfor

                    @if($hasHalf)
                        <i class="fas fa-star-half-alt text-warning me-1"></i>
                    @endif

                    @for($i = 0; $i < $emptyStars; $i++)
                        <i class="far fa-star text-warning me-1"></i>
                    @endfor

                    {{-- Mostra a nota numérica e a quantidade de avaliações --}}
                    <span class="ms-2 text-secondary" style="font-size: 0.95rem;">
                        {{ $avgRating }} ({{ $totalReviews }} avaliação{{ $totalReviews > 1 ? 'ões' : '' }})
                    </span>
                </div>
            </div>

            {{-- Modal com todas as avaliações detalhadas (permanece inalterado) --}}
            <div
                class="modal fade"
                id="reviewsModal"
                tabindex="-1"
                aria-labelledby="reviewsModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        {{-- Cabeçalho do Modal --}}
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="reviewsModalLabel">
                                Avaliações de {{ $provider->user_name }}
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fechar"
                            ></button>
                        </div>

                        {{-- Corpo do Modal --}}
                        <div class="modal-body">
                            @foreach($provider->reviews as $review)
                                @php
                                    // Para cada review, calculamos quantas estrelas cheias/vazias (sem meio)
                                    $stars = $review->rating;
                                    $empty = 5 - $stars;
                                @endphp

                                <div class="border rounded p-3 mb-3 shadow-sm" style="background-color: #ffffff;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        {{-- Nome do avaliador --}}
                                        <strong>{{ $review->reviewer_name }}</strong>

                                        {{-- Estrelas da avaliação --}}
                                        <div class="d-flex align-items-center">
                                            @for($i = 0; $i < $stars; $i++)
                                                <i class="fas fa-star text-warning me-1"></i>
                                            @endfor
                                            @for($i = 0; $i < $empty; $i++)
                                                <i class="far fa-star text-warning me-1"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    {{-- Comentário --}}
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Rodapé do Modal --}}
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Fechar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Caso não haja avaliações --}}
            <p class="text-muted mb-0">Você ainda não recebeu avaliações.</p>
        @endif
    </section>
</div>
@endsection
