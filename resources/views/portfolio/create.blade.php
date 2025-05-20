@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Portfólio</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('provider.portfolio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="images">Imagens (até 9)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar Portfólio</button>
    </form>
</div>
@endsection
