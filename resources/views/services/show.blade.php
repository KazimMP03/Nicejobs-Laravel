{{-- resources/views/services/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $service->title }}</h1>
    <p><strong>Categoria:</strong> {{ $service->category->name }}</p>
    <p><strong>Preço:</strong> R$ {{ number_format($service->price,2,',','.') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($service->status) }}</p>
    <hr>
    <p>{{ $service->description }}</p>
    <a href="{{ route('services.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
