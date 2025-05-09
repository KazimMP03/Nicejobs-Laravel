{{-- resources/views/service_categories/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Categoria de Serviço</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('service-categories.store') }}" method="POST">
        @csrf

        {{-- Nome --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nome*</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Slug --}}
        <div class="mb-3">
            <label for="slug" class="form-label">Slug*</label>
            <input
                type="text"
                id="slug"
                name="slug"
                class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug') }}"
                required
            >
            @error('slug')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descrição --}}
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea
                id="description"
                name="description"
                class="form-control @error('description') is-invalid @enderror"
                rows="3"
            >{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('service-categories.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
