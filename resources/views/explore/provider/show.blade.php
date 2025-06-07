@extends('layouts.app')

@section('content')
<div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
    <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
        style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
        {{ $provider->user_name }}
    </h3>

    @auth('custom')
    <a href="{{ route('service-requests.create', ['provider' => $provider->id]) }}"
    class="btn btn-primary fw-bold">
        <i class="fas fa-paper-plane me-2"></i> Solicitar Serviço
    </a>
    @endauth
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

<div class="container mt-5" style="width: 850px;">

    {{-- Categorias de Serviço --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Categorias de Serviço
        </h4>

        @if($provider->categories->isNotEmpty())
            <div class="d-flex flex-wrap gap-3">
                @foreach($provider->categories as $category)
                    <span class="text-white px-4 py-2"
                          style="background: linear-gradient(90deg, var(--bs-primary), #6610f2);
                                 border-radius: 1rem; font-size: 0.95rem; font-weight: 500;">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">Nenhuma categoria selecionada.</p>
        @endif
    </section>

    {{-- Portfólio --}}
    <section class="mb-5 p-4 shadow-sm rounded bg-light">
        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Portfólio
        </h4>

        @php $portfolio = $provider->portfolios->first(); @endphp

        @if($portfolio)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-center">{{ $portfolio->title }}</h5>
                    <p class="card-text text-secondary text-center">{{ $portfolio->description }}</p>
                    <div class="mx-auto mt-4" style="background-color: #f8f9fa; border-radius: 0.5rem; padding: 20px; max-width: 500px;">
                        <div class="d-flex flex-wrap justify-content-center" style="gap: 20px;">
                            @foreach($portfolio->media_paths as $path)
                                @php
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                @endphp
                                <div style="width: 140px; height: 140px; overflow: hidden; border-radius: 0.5rem;">
                                    @if(in_array($ext, ['mp4','mov','avi','ogg']))
                                        <video src="{{ asset('storage/' . $path) }}"
                                               controls
                                               class="img-fluid"
                                               style="width: 100%; height: 100%; object-fit: cover;">
                                        </video>
                                    @else
                                        <img src="{{ asset('storage/' . $path) }}"
                                             alt="Portfólio"
                                             class="img-fluid"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-muted mb-3 text-center">Este prestador ainda não adicionou um portfólio.</p>
        @endif
    </section>

    {{-- Avaliações --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        @php
            $avgRating = $provider->reviews->avg('rating') ? number_format($provider->reviews->avg('rating'),1) : null;
            $totalReviews = $provider->reviews->count();
            $fullStars = $avgRating ? floor($avgRating) : 0;
            $hasHalf   = $avgRating ? ($avgRating - $fullStars) >= 0.5 : false;
            $emptyStars= 5 - $fullStars - ($hasHalf ? 1 : 0);
        @endphp

        <h4 class="fw-bold mb-4 text-primary border-start border-4 border-primary ps-3 text-uppercase">
            Avaliações
        </h4>

        @if($totalReviews > 0)
            <div class="d-flex align-items-center mb-3" data-bs-toggle="modal" data-bs-target="#reviewsModal" style="cursor: pointer;">
                <h5 class="mb-0 me-3 fw-semibold text-dark" style="font-size: 1.125rem;">
                    {{ $provider->user_name }}
                </h5>
                <div class="d-flex align-items-center">
                    @for($i=0; $i<$fullStars; $i++)
                        <i class="fas fa-star text-warning me-1"></i>
                    @endfor
                    @if($hasHalf)
                        <i class="fas fa-star-half-alt text-warning me-1"></i>
                    @endif
                    @for($i=0; $i<$emptyStars; $i++)
                        <i class="far fa-star text-warning me-1"></i>
                    @endfor
                    <span class="ms-2 text-secondary" style="font-size: 0.95rem;">
                        {{ $avgRating }} ({{ $totalReviews }} avaliação{{ $totalReviews>1?'ões':'' }})
                    </span>
                </div>
            </div>

            {{-- Modal de Avaliações --}}
            <div class="modal fade" id="reviewsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Avaliações de {{ $provider->user_name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @foreach($provider->reviews as $review)
                                @php
                                    $stars = $review->rating;
                                    $empty = 5 - $stars;
                                @endphp
                                <div class="border rounded p-3 mb-3 shadow-sm" style="background-color: #ffffff;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>{{ $review->reviewer_name }}</strong>
                                        <div class="d-flex align-items-center">
                                            @for($i=0; $i<$stars; $i++)
                                                <i class="fas fa-star text-warning me-1"></i>
                                            @endfor
                                            @for($i=0; $i<$empty; $i++)
                                                <i class="far fa-star text-warning me-1"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-muted mb-0">Este prestador ainda não recebeu avaliações.</p>
        @endif
    </section>

</div>
@endsection
