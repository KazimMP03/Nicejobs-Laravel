@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Selecione as categorias de serviços que você oferece</h2>

    <form action="{{ route('provider.categories.update') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="categories">Categorias</label>
            <select name="categories[]" id="categories" class="form-control" multiple required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $provider->categories->contains($category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Categorias</button>
    </form>
</div>
@endsection
