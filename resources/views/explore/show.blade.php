@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $provider->user_name }}</h2>

    <p><strong>Descrição:</strong> {{ $provider->provider_description }}</p>
    <p><strong>Disponibilidade:</strong> {{ ucfirst($provider->availability) }}</p>
    <p><strong>Raio de atuação:</strong> {{ $provider->work_radius }} km</p>

    <hr>

    <h4>Categorias atendidas:</h4>
    <ul>
        @foreach($provider->categories as $category)
            <li>{{ $category->name }}</li>
        @endforeach
    </ul>

    <hr>

    <h4>Portfólio</h4>
    @forelse($provider->portfolios as $portfolio)
        <div>
            <strong>{{ $portfolio->title }}</strong><br>
            <p>{{ $portfolio->description }}</p>
        </div>
    @empty
        <p>Este prestador ainda não adicionou portfólios.</p>
    @endforelse

    <hr>

    <h4>Avaliações</h4>
    @forelse($provider->reviews as $review)
        <div class="mb-2">
            <strong>{{ $review->customUser->user_name }}</strong>
            <span> - Nota: {{ $review->rating }}/5</span><br>
            <p>{{ $review->comment }}</p>
        </div>
    @empty
        <p>Este prestador ainda não recebeu avaliações.</p>
    @endforelse

    <hr>

    @auth('custom')
        <h4>Deixar uma avaliação</h4>
        <form action="{{ route('providers.review', $provider->id) }}" method="POST">
            @csrf
            <div class="mb-2">
                <label for="rating">Nota (1 a 5):</label>
                <input type="number" name="rating" min="1" max="5" required class="form-control" style="width: 100px;">
            </div>
            <div class="mb-2">
                <label for="comment">Comentário:</label>
                <textarea name="comment" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Enviar Avaliação</button>
        </form>
    @endauth

    <hr>

    @if($provider->services->isNotEmpty())
        <h4>Solicitar serviço</h4>
        <a href="{{ route('service-requests.create', ['service' => $provider->services->first()->id]) }}"
           class="btn btn-primary">
           Solicitar agora
        </a>
    @else
        <p>Este prestador ainda não cadastrou serviços.</p>
    @endif
</div>
@endsection
