@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Selecionar Categorias de Serviço</h2>
    <p>Selecione até <strong>3 categorias</strong> nas quais você atua.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
                        <input class="form-check-input category-checkbox"
                               type="checkbox"
                               name="categories[]"
                               value="{{ $category->id }}"
                               id="category_{{ $category->id }}"
                               {{ $provider->categories->contains($category->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category_{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar Categorias</button>
    </form>
</div>

{{-- Script para limitar a seleção a 3 categorias --}}
<script>
    document.querySelectorAll('.category-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            if (checkedCount > 3) {
                alert('Você pode selecionar no máximo 6 categorias.');
                this.checked = false;
            }
        });
    });
</script>
@endsection
