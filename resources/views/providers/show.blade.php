@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Meu Perfil</h2>

    {{-- Botão para editar o perfil --}}
    <div class="mb-3">
        <a href="{{ route('provider.profile.edit') }}" class="btn btn-outline-primary">Editar Perfil</a>
    </div>

    {{-- Dados básicos --}}
    <div class="mb-4">
        <p><strong>Nome:</strong> {{ $provider->user_name }}</p>
        <p><strong>Descrição:</strong> {{ $provider->provider_description }}</p>
        <p><strong>Disponibilidade:</strong> {{ ucfirst($provider->availability) }}</p>
        <p><strong>Raio de atuação:</strong> {{ $provider->work_radius }} km</p>

        @if($provider->profile_photo)
            <img src="{{ asset('storage/' . $provider->profile_photo) }}" alt="Foto de Perfil" width="150" class="rounded mt-2">
        @endif
    </div>

    {{-- Categorias atendidas --}}
    <div class="mb-4">
        <h4>Categorias de Serviço</h4>
        <ul>
            @forelse($provider->categories as $category)
                <li>{{ $category->name }}</li>
            @empty
                <p>Nenhuma categoria selecionada.</p>
            @endforelse
        </ul>
    </div>

    {{-- Portfólio --}}
    <div class="mb-4">
        <h4>Portfólio</h4>

        @php
            $portfolio = $provider->portfolios->first();
        @endphp

        @if($portfolio)
            <div class="mb-3">
                <h5>{{ $portfolio->title }}</h5>
                <p>{{ $portfolio->description }}</p>

                <div class="d-flex flex-wrap gap-2">
                    @foreach($portfolio->media_paths as $path)
                        <img src="{{ asset('storage/' . $path) }}" alt="Imagem do Portfólio" width="150" class="img-thumbnail">
                    @endforeach
                </div>

                <div class="mt-2">
                    <a href="{{ route('provider.portfolio.edit', $portfolio) }}" class="btn btn-outline-secondary">Editar Portfólio</a>
                </div>
            </div>
        @else
            <p>Você ainda não adicionou um portfólio.</p>
            <a href="{{ route('provider.portfolio.create') }}" class="btn btn-success">Criar Portfólio</a>
        @endif
    </div>

    {{-- Avaliações --}}
    <div class="mb-4">
        <h4>Avaliações Recebidas</h4>
        @forelse($provider->reviews as $review)
            <div class="border p-2 rounded mb-2">
                <strong>{{ $review->reviewer_name }}</strong>
                <span> - Nota: {{ $review->rating }}/5</span>
                <p>{{ $review->comment }}</p>
            </div>
        @empty
            <p>Você ainda não recebeu avaliações.</p>
        @endforelse
    </div>
</div>
@endsection
