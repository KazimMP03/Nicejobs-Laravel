@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/explore/providers.css') }}">

@section('content')
<div class="container explore-category py-4">
    <div
        class="w-100 d-flex justify-content-between align-items-center mb-4 px-1 text-uppercase">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block;
                   font-size: 1.8rem;
                   border-color: #0d6efd;
                   font-family: 'Rubik', sans-serif;">
            HOME
        </h3>
    </div>

    <div class="w-100 text-center mb-4 px-3 py-4 rounded shadow-sm"
        style="background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); 
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
        <h5 class="mb-0"
            style="color: #009CFF; font-weight: 500; line-height: 1.5;">
            Está em dúvida por onde começar?<br>
            <span style="font-weight: 600; color: #2c3e50;">Encontre um
                prestador entre os mais bem avaliados do site!</span>
        </h5>
    </div>

   @if($topProviders->isEmpty())
    <p class="text-center text-muted">Nenhum prestador encontrado.</p>
    @else
        <div class="row gy-4">
            @foreach($topProviders as $provider)
            <div class="col-12 col-sm-6 col-lg-4 d-flex">
                <div class="provider-card flex-fill d-flex flex-column">
                    {{-- HEADER --}}
                    <div class="card-header d-flex align-items-center">
                        @php
                        $photo = $provider->profile_photo
                            ? asset('storage/' . $provider->profile_photo)
                            : asset('images/user.png');
                        @endphp
                        <img src="{{ $photo }}"
                            alt="Foto de {{ $provider->user_name }}"
                            class="avatar me-3">
                        <h5 class="mb-0">{{ $provider->user_name }}</h5>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body flex-grow-1">
                        <p>{{ Str::limit($provider->provider_description, 100) }}</p>

                        @if($provider->categories->isNotEmpty())
                        <div class="provider-categories mt-2">
                            @foreach($provider->categories as $category)
                                <span class="category-badge">
                                    <i class="fas fa-tag me-1"></i>{{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- FOOTER: estrelas + quantidade --}}
                    <div class="card-footer text-center">
                        @php
                        $avg = $provider->reviews_avg_rating
                            ? round($provider->reviews_avg_rating * 2) / 2
                            : 0;
                        $fullStars = floor($avg);
                        $halfStar = ($avg - $fullStars) === 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp

                        @for($i = 0; $i < $fullStars; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        @if($halfStar)
                            <i class="fas fa-star-half-alt"></i>
                        @endif
                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="far fa-star"></i>
                        @endfor

                        <span class="ms-2">({{ $provider->reviews_count }})</span>

                        <div class="mt-3">
                            <a href="{{ route('explore.provider.show', $provider->id) }}"
                                class="btn btn-primary btn-explore">
                                <i class="fas fa-eye me-2"></i> Ver perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
