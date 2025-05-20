@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Portfólio</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
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

    <form action="{{ route('provider.portfolio.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" class="form-control" value="{{ $portfolio->title }}" required>
        </div>

        <div class="form-group mt-3">
            <label for="description">Descrição</label>
            <textarea name="description" class="form-control" rows="5" required>{{ $portfolio->description }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label for="images">Adicionar novas imagens (máx. 9)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-4">Atualizar Portfólio</button>
    </form>

    <hr class="my-5">
    <h4>Imagens atuais</h4>
    <div class="row">
        @foreach($portfolio->media_paths as $image)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $image) }}" class="card-img-top img-fluid">
                    <div class="card-body text-center">
                        <button class="btn btn-sm btn-danger delete-image-btn" data-image="{{ $image }}">Excluir</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<form id="delete-image-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="image_path" id="image-path-input">
</form>

<script>
    document.querySelectorAll('.delete-image-btn').forEach(button => {
        button.addEventListener('click', function () {
            const imagePath = this.dataset.image;
            const form = document.getElementById('delete-image-form');
            const input = document.getElementById('image-path-input');
            input.value = imagePath;
            form.action = `/provider/portfolio/{{ $portfolio->id }}/image`;
            form.submit();
        });
    });
</script>
@endsection
