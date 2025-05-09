{{-- resources/views/services/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Serviço</h1>
    <form action="{{ route('services.store') }}" method="POST">
        @csrf

        {{-- Categoria --}}
        <div class="mb-3">
            <label for="service_category_id" class="form-label">Categoria*</label>
            <select name="service_category_id" id="service_category_id"
                    class="form-select @error('service_category_id') is-invalid @enderror">
                <option value="">Selecione...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('service_category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('service_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Título --}}
        <div class="mb-3">
            <label for="title" class="form-label">Título*</label>
            <input type="text" name="title" id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descrição --}}
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea name="description" id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Preço --}}
        <div class="mb-3">
            <label for="price" class="form-label">Preço (R$)*</label>
            <input type="number" name="price" id="price" step="0.01" min="0"
                   class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price') }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
