<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Prestador - NiceJobs</title>

    <!-- Favicon (ícone da aba do navegador) -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Link para o css da tela de registro de prestador de serviço -->
    <link rel="stylesheet" href="{{ asset('css/auth/register-provider.css') }}">
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('images/logo.png') }}" alt="NiceJobs" class="logo">
            <h2>Cadastro de Prestador de Serviços</h2>
            <p class="mb-0">Preencha seus dados para se cadastrar em nossa plataforma</p>
        </div>

        <div class="register-body">
            <!-- Indicador de Passos -->
            <div class="step-indicator">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-title">Dados Pessoais</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-title">Dados Profissionais</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-title">Confirmação</div>
                </div>
            </div>

            <form method="POST" action="{{ route('register.provider') }}" enctype="multipart/form-data"
                id="providerForm">
                @csrf

                <!-- Seção 1: Dados Pessoais -->
                <div class="form-section" id="section1">
                    <h4 class="mb-4"><i class="fas fa-user me-2"></i> Dados Pessoais</h4>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">Nome Completo*</label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                id="user_name" name="user_name" value="{{ old('user_name') }}" required>
                            @error('user_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail*</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Senha*</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo de 8 caracteres</small>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmar Senha*</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telefone*</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="user_type" class="form-label">Tipo de Pessoa*</label>
                            <select class="form-select @error('user_type') is-invalid @enderror" id="user_type"
                                name="user_type" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="PF" {{ old('user_type') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                                <option value="PJ" {{ old('user_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica
                                </option>
                            </select>
                            @error('user_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tax_id" class="form-label" id="tax_id_label">CPF/CNPJ*</label>
                            <input type="text" class="form-control @error('tax_id') is-invalid @enderror" id="tax_id"
                                name="tax_id" value="{{ old('tax_id') }}"
                                placeholder="Selecione o tipo de pessoa primeiro" required>
                            @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted" id="tax_id_hint"></small>
                        </div>

                        <div class="col-md-6" id="birth_date_field"
                            style="{{ old('user_type') != 'PF' ? 'display: none;' : '' }}">
                            <label for="birth_date" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="foundation_date_field"
                            style="{{ old('user_type') != 'PJ' ? 'display: none;' : '' }}">
                            <label for="foundation_date" class="form-label">Data de Fundação</label>
                            <input type="date" class="form-control @error('foundation_date') is-invalid @enderror"
                                id="foundation_date" name="foundation_date" value="{{ old('foundation_date') }}">
                            @error('foundation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" disabled>Voltar</button>
                        <button type="button" class="btn btn-primary next-section" data-next="section2">Próximo</button>
                    </div>
                </div>

                <!-- Seção 2: Dados Profissionais -->
                <div class="form-section" id="section2" style="display: none;">
                    <h4 class="mb-4"><i class="fas fa-briefcase me-2"></i> Dados Profissionais</h4>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="provider_description" class="form-label">Sobre você*</label>
                            <textarea class="form-control @error('provider_description') is-invalid @enderror"
                                id="provider_description" name="provider_description" rows="3"
                                required>{{ old('provider_description') }}</textarea>
                            @error('provider_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Conte um pouco sobre você e suas experiências</small>
                        </div>

                        <div class="col-md-6">
                            <label for="service_category" class="form-label">Categoria de Serviço*</label>
                            <select class="form-select @error('service_category') is-invalid @enderror"
                                id="service_category" name="service_category" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="Construção Civil">Construção Civil</option>
                                <option value="Elétrica">Elétrica</option>
                                <option value="Encanamento">Encanamento</option>
                                <option value="Limpeza">Limpeza</option>
                                <option value="Manutenção">Manutenção</option>
                                <option value="Pintura">Pintura</option>
                                <option value="Outros">Outros</option>
                            </select>
                            @error('service_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="work_radius" class="form-label">Raio de Atendimento (km)*</label>
                            <input type="number" class="form-control @error('work_radius') is-invalid @enderror"
                                id="work_radius" name="work_radius" value="{{ old('work_radius') ?? 10 }}" min="1"
                                required>
                            @error('work_radius')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="service_description" class="form-label">Descrição dos Serviços*</label>
                            <textarea class="form-control @error('service_description') is-invalid @enderror"
                                id="service_description" name="service_description" rows="3"
                                required>{{ old('service_description') }}</textarea>
                            @error('service_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Descreva os serviços que você oferece</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Disponibilidade*</label>
                            <div class="availability-container">
                                <!-- Aqui você pode adicionar checkboxes/dias da semana/horários -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="available_weekdays"
                                        name="availability[weekdays]" checked>
                                    <label class="form-check-label" for="available_weekdays">Dias de semana</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="available_weekends"
                                        name="availability[weekends]">
                                    <label class="form-check-label" for="available_weekends">Finais de semana</label>
                                </div>
                                <!-- Mais opções de disponibilidade podem ser adicionadas aqui -->
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary prev-section"
                            data-prev="section1">Voltar</button>
                        <button type="button" class="btn btn-primary next-section" data-next="section3">Próximo</button>
                    </div>
                </div>

                <!-- Seção 3: Confirmação -->
                <div class="form-section" id="section3" style="display: none;">
                    <h4 class="mb-4"><i class="fas fa-check-circle me-2"></i> Confirmação</h4>

                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Revise seus dados</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Dados Pessoais</h6>
                                    <p id="review-name"></p>
                                    <p id="review-email"></p>
                                    <p id="review-tax-id"></p>
                                    <p id="review-phone"></p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dados Profissionais</h6>
                                    <p id="review-service-category"></p>
                                    <p id="review-work-radius"></p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h6>Sobre você</h6>
                                <p id="review-description" class="text-muted"></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms"
                            name="terms" required>
                        <label class="form-check-label" for="terms">
                            Li e aceito os <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Termos de
                                Serviço</a> e <a href="#">Política de Privacidade</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-section"
                            data-prev="section2">Voltar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Confirmar Cadastro
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Termos -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termos de Serviço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Conteúdo dos termos -->
                    <p>Texto dos termos de serviço aqui...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/register-custom-user.js') }}"></script>
</body>

</html>