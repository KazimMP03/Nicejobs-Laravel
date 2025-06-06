@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3
            class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;"
        >
            CRIAR PORTFÓLIO
        </h3>
    </div>

    <div class="w-100 d-flex flex-column align-items-center py-5">
        <div
            class="shadow-sm bg-white rounded px-4 py-4 w-100"
            style="max-width: 800px;"
        >
            {{-- Frase para instrução do usuário --}}
            <h5 class="form-subtitle">
                Cadastre seu portfólio
            </h5>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('provider.portfolio.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="portfolio-form"
            >
                @csrf

                <div class="form-floating mb-4" style="margin-top: 20px;">
                    <input
                        id="title"
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        placeholder="Título"
                        value="{{ old('title') }}"
                        required
                    >
                    <label for="title">Título</label>
                    @error('title')
                        <div class="text-danger mt-1" style="font-size: .9rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <textarea
                        id="description"
                        name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Descrição"
                        style="height: 120px;"
                        required
                    >{{ old('description') }}</textarea>
                    <label for="description">Descrição</label>
                    @error('description')
                        <div class="text-danger mt-1" style="font-size: .9rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="files" class="form-label text-secondary d-flex align-items-center">
                        <i class="fas fa-cloud-upload-alt me-2" aria-hidden="true"></i>
                        Imagens ou vídeos (máx: 9)
                    </label>
                    <input
                        id="files"
                        type="file"
                        name="files[]"
                        multiple
                        accept="image/*,video/*"
                        class="form-control @error('files') is-invalid @enderror"
                        required
                    >
                    @error('files')
                        <div class="text-danger mt-1" style="font-size: .9rem;">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('files.*')
                        <div class="text-danger mt-1" style="font-size: .9rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Container cinza-claro para as miniaturas, inicia oculto --}}
                <div
                    id="preview-container"
                    class="d-flex flex-wrap gap-3 justify-content-center mt-4 bg-light rounded-3 d-none"
                    style="padding: 20px; max-width: 500px; margin: 0 auto;"
                ></div>

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fas fa-save me-2"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Importa o script de preview de imagens/vídeos --}}
    <script src="{{ asset('js/images-preview.js') }}"></script>
@endsection
