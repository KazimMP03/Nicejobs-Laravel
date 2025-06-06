{{-- resources/views/portfolio/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/utils.css') }}">

    {{-- Cabeçalho com título e botão de edição --}}
    <div class="w-100 d-flex justify-content-between align-items-start mb-4 px-1">
        <h3
            class="fw-bold text-primary border-bottom border-3 pb-1 mb-0"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik', sans-serif;"
        >
            MEU PORTFÓLIO
        </h3>

        <a
            href="{{ route('provider.portfolio.edit', $portfolio) }}"
            class="btn btn-primary fw-bold"
        >
            <i class="fas fa-edit me-2"></i> Editar
        </a>
    </div>

    <div class="w-100 d-flex flex-column align-items-center py-5">
        <div
            class="shadow-sm bg-white rounded px-4 py-4 w-100"
            style="max-width: 800px;"
        >
            @if($portfolio)
                {{-- Título do portfólio --}}
                <h5 class="fw-bold text-center mb-3">{{ $portfolio->title }}</h5>

                {{-- Descrição --}}
                <p class="text-secondary text-center mb-4">
                    {{ $portfolio->description }}
                </p>

                {{-- Container cinza para miniaturas --}}
                <div
                    class="mx-auto"
                    style="
                        background-color: #f8f9fa;
                        border-radius: 0.5rem;
                        padding: 20px;
                        max-width: 500px;
                    ">
                    <div class="d-flex flex-wrap justify-content-center" style="gap: 20px;">
                        @foreach($portfolio->media_paths as $path)
                            @php
                                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                            @endphp

                            <div
                                style="
                                    width: 140px;
                                    height: 140px;
                                    overflow: hidden;
                                    border-radius: 0.5rem;
                                ">
                                @if(in_array($extension, ['mp4','mov','avi','ogg']))
                                    {{-- Exibe vídeo --}}
                                    <video
                                        src="{{ asset('storage/' . $path) }}"
                                        controls
                                        class="img-fluid"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                    ></video>
                                @else
                                    {{-- Exibe imagem --}}
                                    <img
                                        src="{{ asset('storage/' . $path) }}"
                                        alt="Imagem do Portfólio"
                                        class="img-fluid"
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                    >
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    Você ainda não adicionou um portfólio.
                </div>
            @endif
        </div>
    </div>
@endsection
