@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/utils.css') }}">

<div class="container">

    {{-- Cabeçalho --}}
    <div class="w-100 d-flex justify-content-between align-items-center mb-4 px-1">
        <h3 class="fw-bold text-primary border-bottom border-3 pb-1 mb-0 text-uppercase"
            style="display: inline-block; font-size: 1.8rem; border-color: #0d6efd; font-family: 'Rubik',sans-serif;">
            AVALIAR 
            @if(auth()->user() instanceof \App\Models\Provider)
                CLIENTE: {{ mb_strtoupper($serviceRequest->customUser->user_name) }}
            @else
                PRESTADOR: {{ mb_strtoupper($serviceRequest->provider->user_name) }}
            @endif
        </h3>
    </div>

    {{-- Seção: Informações do Serviço --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold text-primary border-start border-4 border-primary ps-3 text-uppercase mb-4">
            INFORMAÇÕES DO SERVIÇO
        </h4>
        <div class="card border-0">
            <div class="card-body bg-white">
                <div class="row gx-4 gy-3 align-items-center">
                    {{-- Descrição --}}
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-primary">
                        <i class="fas fa-align-left fa-lg text-primary me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1 text-primary fw-semibold">Descrição</h6>
                            <p class="mb-0 text-muted small">{{ $serviceRequest->description }}</p>
                        </div>
                        </div>
                    </div>

                    {{-- Data --}}
                    <div class="col-12 col-md-3">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-info">
                        <i class="fas fa-calendar-alt fa-lg text-info me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1 text-info fw-semibold">Data</h6>
                            <p class="mb-0 text-muted small">
                            {{ $serviceRequest->service_date
                                ? \Carbon\Carbon::parse($serviceRequest->service_date)->format('d/m/Y')
                                : 'NÃO INFORMADO' }}
                            </p>
                        </div>
                        </div>
                    </div>

                    {{-- Valor Final --}}
                    @if($serviceRequest->final_price)
                    <div class="col-12 col-md-3">
                        <div class="d-flex align-items-start bg-white p-3 rounded shadow-sm border-start border-4 border-success">
                        <i class="fas fa-dollar-sign fa-lg text-success me-3 mt-1"></i>
                        <div>
                            <h6 class="mb-1 text-success fw-semibold">Valor Final</h6>
                            <p class="mb-0 text-muted small">
                            R$ {{ number_format($serviceRequest->final_price, 2, ',', '.') }}
                            </p>
                        </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Seção: Avaliação --}}
    <section class="mb-5 p-4 shadow-sm rounded" style="background-color: #f9fbfd;">
        <h4 class="fw-bold text-primary border-start border-4 border-primary ps-3 text-uppercase mb-4">
            AVALIAÇÃO
        </h4>
        <div class="card border-0">
            <div class="card-body bg-white">
                <form action="{{ route('service-requests.review', $serviceRequest) }}" method="POST">
                    @csrf

                    {{-- Nota --}}
                    <div class="mb-5" style="margin-top: 20px;">
                        <div class="star-rating d-flex" style="flex-direction: row-reverse;">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star-{{ $i }}" name="rating" value="{{ $i }}" class="star-input">
                                <label for="star-{{ $i }}" class="star-label">
                                    <i class="fas fa-star"></i>
                                </label>
                            @endfor
                        </div>
                    </div>

                    {{-- Comentário --}}
                    <div class="mb-5">
                        <label class="form-label fw-semibold">
                            Comentário <span class="text-muted">(Opcional)</span>
                        </label>
                        <textarea name="comment" class="form-control form-control-lg" rows="4" 
                                  placeholder="Adicione um comentário..."></textarea>
                    </div>

                    {{-- Botões --}}
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route(
                                auth()->user() instanceof \App\Models\Provider 
                                    ? 'service-requests.show' 
                                    : 'custom-user.service-requests.show', 
                                $serviceRequest
                            ) }}"
                           class="btn btn-cancel fw-bold">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</div>
@endsection
