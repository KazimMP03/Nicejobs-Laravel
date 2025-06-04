@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $provider->user_name }}</h2>

    {{-- Dados básicos --}}
    <div class="mb-4">
        <p><strong>Descrição:</strong> {{ $provider->provider_description }}</p>
        <p><strong>Disponibilidade:</strong> {{ ucfirst($provider->availability) }}</p>
        <p><strong>Raio de atuação:</strong> {{ $provider->work_radius }} km</p>

        @if($provider->profile_photo)
            <img src="{{ asset('storage/' . $provider->profile_photo) }}" 
                 alt="Foto do prestador" 
                 width="150" 
                 class="rounded mt-2">
        @endif
    </div>

    {{-- Categorias atendidas --}}
    <div class="mb-4">
        <h4>Categorias de Serviço</h4>
        <ul>
            @foreach($provider->categories as $category)
                <li>{{ $category->name }}</li>
            @endforeach
        </ul>
    </div>

    {{-- Portfólio --}}
    <div class="mb-4">
        <h4>Portfólio</h4>

        @forelse($provider->portfolios as $portfolio)
            <div class="mb-3">
                <h5>{{ $portfolio->title }}</h5>
                <p>{{ $portfolio->description }}</p>

                {{-- CORREÇÃO: Iterar em media_paths (array) --}}
                @if(is_array($portfolio->media_paths) && count($portfolio->media_paths) > 0)
                    <div class="row">
                        @foreach($portfolio->media_paths as $imagePath)
                            <div class="col-md-4 mb-2">
                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                     alt="Imagem do portfólio - {{ $portfolio->title }}" 
                                     class="img-fluid img-thumbnail">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">Nenhuma imagem disponível neste portfólio.</p>
                @endif
            </div>
        @empty
            <p>Este prestador ainda não adicionou portfólios.</p>
        @endforelse
    </div>

    {{-- Avaliações --}}
    <div class="mb-4">
        <h4>Avaliações</h4>
        @forelse($provider->reviews as $review)
            <div class="border p-2 rounded mb-2">
                <strong>{{ $review->reviewer_name }}</strong>
                <span> - Nota: {{ $review->rating }}/5</span>
                <p>{{ $review->comment }}</p>
            </div>
        @empty
            <p>Este prestador ainda não recebeu avaliações.</p>
        @endforelse
    </div>

    {{-- Botão para solicitar um serviço --}}
    @auth('custom')
        <div class="mb-4">
            <a href="{{ route('service-requests.create', ['provider' => $provider->id]) }}" 
               class="btn btn-primary">
                Solicitar Serviço
            </a>
        </div>
    @endauth
</div>
@endsection
