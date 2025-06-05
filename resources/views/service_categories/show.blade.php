@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3
            class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;"
        >
            SELECIONAR CATEGORIAS DE SERVIÇO
        </h3>
    </div>

    <div class="w-100 d-flex flex-column align-items-center py-5">
        <div
            class="shadow-sm bg-white rounded px-4 py-4 w-100"
            style="max-width: 800px;"
        >
            <p class="mb-4">
                Selecione até <strong>3 categorias</strong> nas quais você atua.
            </p>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('provider.categories.update') }}" method="POST">
                @csrf

                <div class="row">
                    @foreach($categories as $category)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input
                                    class="form-check-input category-checkbox"
                                    type="checkbox"
                                    name="categories[]"
                                    value="{{ $category->id }}"
                                    id="category_{{ $category->id }}"
                                    {{ $provider->categories->contains($category->id) ? 'checked' : '' }}
                                >
                                <label
                                    class="form-check-label"
                                    for="category_{{ $category->id }}"
                                >
                                    {{ $category->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fas fa-save me-2"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para limitar a seleção a 3 categorias --}}
    <script>
        document.querySelectorAll('.category-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                if (checkedCount > 3) {
                    alert('Você pode selecionar no máximo 3 categorias.');
                    this.checked = false;
                }
            });
        });
    </script>
@endsection
