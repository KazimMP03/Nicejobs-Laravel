{{-- resources/views/explore/providers.blade.php --}}
@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/explore/providers.css') }}">

@section('content')
<div class="container explore-category py-4">
    {{-- Cabeçalho igual ao de “Escolha uma Categoria” --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1 text-uppercase">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block;
                   font-size: 1.8rem;
                   border-color: #0d6efd;
                   font-family: 'Rubik', sans-serif;">
            Prestadores em: {{ $category->name }}
        </h3>
    </div>

    @if($providers->isEmpty())
        <p class="text-center text-muted">Nenhum prestador disponível nesta categoria.</p>
    @else
        <div class="row gy-4">
            @foreach($providers as $provider)
                <div class="col-12 col-sm-6 col-lg-4 d-flex">
                    <div class="provider-card flex-fill d-flex flex-column">
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
                        <div class="card-body flex-grow-1">
                            <p>{{ Str::limit($provider->provider_description, 100) }}</p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('explore.provider.show', $provider->id) }}"
                                class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i> Ver perfil
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
