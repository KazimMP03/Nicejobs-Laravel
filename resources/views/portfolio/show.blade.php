@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $portfolio->title }}</h1>

    <p>{{ $portfolio->description }}</p>

    <div class="row">
        @foreach($portfolio->media_paths as $image)
            <div class="col-md-4 mb-3">
                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded">
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('provider.portfolio.edit', $portfolio->id) }}" class="btn btn-primary">
            Editar Portfólio
        </a>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Voltar para Dashboard
        </a>
    </div>
</div>
@endsection
