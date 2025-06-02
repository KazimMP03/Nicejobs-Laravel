@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Prestadores em: {{ $category->name }}</h2>

    @if($providers->isEmpty())
        <p>Nenhum prestador disponível nesta categoria.</p>
    @else
        <div class="row">
            @foreach($providers as $provider)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>{{ $provider->user_name }}</h5>
                            <p>{{ Str::limit($provider->provider_description, 100) }}</p>
                            <a href="{{ route('explore.provider.show', $provider->id) }}" class="btn btn-primary">
                                Ver perfil
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
