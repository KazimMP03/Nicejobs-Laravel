@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/explore/categories.css') }}">
<div class="container py-4">
    {{-- Cabeçalho --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik',sans-serif;">
            ESCOLHA UMA CATEGORIA
        </h3>
    </div>

    {{-- Cards de categorias --}}
    <ul class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 list-unstyled mb-0">
        @foreach($categories as $category)
            <li class="col d-flex">
                <a href="{{ route('explore.byCategory', $category->id) }}"
                   class="category-card bg-white d-flex flex-column justify-content-center align-items-center text-decoration-none text-center p-4 position-relative rounded w-100 h-100">
                    <div class="icon-wrapper mb-3">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <h5 class="mb-0 text-primary px-2">{{ $category->name }}</h5>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
