@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Selecione as categorias de serviços que você oferece</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('provider.categories.update') }}" method="POST">
        @csrf

        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-3">
                    <input type="checkbox" class="btn-check" name="categories[]" value="{{ $category->id }}" id="cat-{{ $category->id }}"
                        {{ $provider->categories->contains($category->id) ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary w-100" for="cat-{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salvar Categorias</button>
        </div>
    </form>
</div>
@endsection
