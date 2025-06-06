{{-- resources/views/portfolio/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    {{-- Meta tag CSRF necessária para delete-image.js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;">
            EDITAR PORTFÓLIO
        </h3>
    </div>

    <div class="w-100 d-flex flex-column align-items-center py-5">
        <div class="shadow-sm bg-white rounded px-4 py-4 w-100" style="max-width: 800px;">

            {{-- Mensagens de sucesso/erro --}}
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
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

            @php
                $alreadyCount = count($portfolio->media_paths ?? []);
            @endphp

            <form action="{{ route('provider.portfolio.update', $portfolio) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="portfolio-form">
                @csrf
                @method('PUT')

                {{-- Título --}}
                <div class="form-floating mb-4" style="margin-top: 20px;">
                    <input id="title"
                           type="text"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           placeholder="Título"
                           value="{{ old('title', $portfolio->title) }}"
                           required>
                    <label for="title">Título</label>
                    @error('title')
                        <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Descrição --}}
                <div class="form-floating mb-4">
                    <textarea id="description"
                              name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Descrição"
                              style="height: 120px;"
                              required>{{ old('description', $portfolio->description) }}</textarea>
                    <label for="description">Descrição</label>
                    @error('description')
                        <div class="text-danger mt-1" style="font-size: .9rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Input de upload de novos arquivos (até 9) --}}
                @if($alreadyCount < 9)
                    <div class="mb-4">
                        <label for="files" class="form-label text-secondary d-flex align-items-center">
                            <i class="fas fa-cloud-upload-alt me-2"></i>
                            Adicionar arquivos (máx: {{ 9 - $alreadyCount }} restantes)
                        </label>
                        <input id="files"
                               type="file"
                               name="files[]"
                               multiple
                               accept="image/*,video/*"
                               class="form-control @error('files') is-invalid @enderror">
                        @error('files')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{$message }}</div>
                        @enderror
                        @error('files.*')
                            <div class="text-danger mt-1" style="font-size: .9rem;">{{$message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- Container para preview de novas mídias --}}
                <div id="preview-container"
                     class="d-flex flex-wrap gap-3 justify-content-center mt-4 bg-light rounded-3 d-none"
                     style="padding: 20px; max-width: 500px; margin: 0 auto;">
                </div>

                {{-- Exibição das mídias já cadastradas --}}
                @if(!empty($portfolio->media_paths) && count($portfolio->media_paths) > 0)
                    <div class="mb-4">
                        <h6 class="form-subtitle">Arquivos atuais ({{ $alreadyCount }}/9)</h6>
                        <div class="mx-auto"
                             style="background-color: #f8f9fa; border-radius: 0.5rem; padding: 20px; max-width: 500px;">
                            <div class="d-flex flex-wrap justify-content-center" style="gap: 20px;">
                                @foreach($portfolio->media_paths as $path)
                                    @php
                                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="position-relative"
                                         style="width: 140px; height: 140px; overflow: hidden; border-radius: 0.5rem;">
                                        {{-- Miniatura --}}
                                        @if(in_array($extension, ['mp4', 'mov', 'avi', 'ogg']))
                                            <video src="{{ asset('storage/' . $path) }}"
                                                   controls
                                                   class="img-fluid"
                                                   style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1;">
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $path) }}"
                                                 alt="Mídia do portfólio"
                                                 class="img-fluid"
                                                 style="width: 100%; height: 100%; object-fit: cover; position: relative; z-index: 1;">
                                        @endif

                                        {{-- Botão “×” para excluir --}}
                                        <button type="button"
                                                class="btn btn-danger p-0 position-absolute"
                                                style="top: 5px; left: 5px; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;"
                                                title="Excluir arquivo"
                                                onclick="deleteImage('{{ $path }}')">
                                            <i class="fas fa-times" style="font-size: 12px; color: #fff;"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Botões: Cancelar | Atualizar --}}
                <div class="d-flex justify-content-center mt-4 gap-3">
                    <a href="{{ route('provider.portfolio.show', $portfolio) }}"
                       class="btn btn-cancel fw-bold"
                       style="margin-right: 50px;">
                        <i class="fas fa-arrow-left me-2"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fas fa-save me-2"></i> Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Variável global para deleteImage.js --}}
    <script>
        const deleteImageRoute = "{{ route('provider.portfolio.delete-image', $portfolio) }}";
    </script>

    {{-- Importa os dois scripts separados --}}
    <script src="{{ asset('js/images-preview.js') }}"></script>
    <script src="{{ asset('js/images-delete.js') }}"></script>
@endsection
