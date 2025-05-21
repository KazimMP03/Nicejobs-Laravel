<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <title>NiceJob - Cadastro de Prestador</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords"
            content="serviços, contratação, autônomos, plataforma, NiceJob, prestadores">
        <meta name="description"
            content="Plataforma para contratar prestadores de serviço com segurança, avaliações e agilidade. Encontre o profissional ideal no NiceJob.">

        <!-- Favicon -->
        <link rel="icon" type="image/png"
            href="{{ asset('images/logo_favicon.png') }}">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
            rel="stylesheet">
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
            rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}"
            rel="stylesheet">
        <link
            href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}"
            rel="stylesheet" />

        <!-- Customized Bootstrap Stylesheet -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('css/auth/register-provider.css') }}"
            rel="stylesheet">
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary"
                style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <div class="page-wrapper hidden-until-loaded">
            <div class="register-container">

                <!-- Header -->
                <div
                    class="d-flex align-items-center justify-content-between my-4 mx-4">
                    <a href class>
                        <img src="{{ asset('images/logo_big_rect_round.png') }}"
                            alt="NiceJob Logo" style="height: 45px;">
                    </a>
                    <h3 class="mb-0 fw-bold text-secondary">Cadastro de
                        Prestador</h3>
                </div>
                <!-- Header End -->

                <div class="register-body">

                    <!-- Indicador de Passos -->
                    <div class="step-indicator mb-5">
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
                    <!-- Indicador de Passos Fim -->

                    <!-- Formulário -->
                    <form method="POST"
                        action="{{ route('register.provider') }}"
                        enctype="multipart/form-data"
                        id="providerForm">
                        @csrf

                        <!-- Status padrão -->
                        <input type="hidden" name="status" value="1">

                        <!-- Seção 1: Dados Pessoais -->
                        <div class="form-section" id="section1">
                            <h4 class="mb-4" style="color: #009CFF;"><i
                                    class="fas fa-user me-2"
                                    style="color: #009CFF;"></i> Dados
                                Pessoais</h4>

                            <div class="row g-3">
                                <div class="col-md-6 form-floating">
                                    <input type="text"
                                        class="form-control @error('user_name') is-invalid @enderror"
                                        id="user_name" name="user_name"
                                        placeholder="Nome Completo"
                                        value="{{ old('user_name') }}" required>
                                    <label for="user_name">Nome Completo</label>
                                    @error('user_name')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        id="email"
                                        name="email" placeholder="E-mail"
                                        value="{{ old('email') }}" required>
                                    <label for="email">E-mail</label>
                                    @error('email')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password"
                                        placeholder="Senha" required>
                                    <label for="password">Senha</label>
                                    @error('password')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="password" class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirmar Senha" required>
                                    <label for="password_confirmation">Confirmar
                                        Senha</label>
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="text"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        id="phone"
                                        name="phone" placeholder="Telefone"
                                        value="{{ old('phone') }}" required>
                                    <label for="phone">Telefone</label>
                                    @error('phone')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <select
                                        class="form-select @error('user_type') is-invalid @enderror"
                                        id="user_type"
                                        name="user_type"
                                        aria-label="Tipo de Pessoa" required>
                                        <option value disabled {{
                                            old('user_type') ? '' : 'selected'
                                            }}>Selecione...
                                        </option>
                                        <option value="PF" {{ old('user_type')
                                            == 'PF' ? 'selected' : '' }}>Pessoa
                                            Física
                                        </option>
                                        <option value="PJ" {{ old('user_type')
                                            == 'PJ' ? 'selected' : '' }}>Pessoa
                                            Jurídica
                                        </option>
                                    </select>
                                    <label for="user_type">Tipo de
                                        Pessoa</label>
                                    @error('user_type')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating">
                                    <input type="text"
                                        class="form-control @error('tax_id') is-invalid @enderror"
                                        id="tax_id" name="tax_id"
                                        placeholder="CPF ou CNPJ"
                                        value="{{ old('tax_id') }}"
                                        required>
                                    <label for="tax_id">CPF/CNPJ</label>
                                    @error('tax_id')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating"
                                    id="birth_date_field"
                                    style="{{ old('user_type') != 'PF' ? 'display: none;' : '' }}">
                                    <input type="date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        id="birth_date" name="birth_date"
                                        placeholder="Data de Nascimento"
                                        value="{{ old('birth_date') }}">
                                    <label for="birth_date">Data de
                                        Nascimento</label>
                                    @error('birth_date')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div class="col-md-6 form-floating"
                                    id="foundation_date_field"
                                    style="{{ old('user_type') != 'PJ' ? 'display: none;' : '' }}">
                                    <input type="date"
                                        class="form-control @error('foundation_date') is-invalid @enderror"
                                        id="foundation_date"
                                        name="foundation_date"
                                        placeholder="Data de Fundação"
                                        value="{{ old('foundation_date') }}">
                                    <label for="foundation_date">Data de
                                        Fundação</label>
                                    @error('foundation_date')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>
                            </div>

                            <p class="mt-4 mb-0" style="font-size: 15px;">
                                *Todos os campos são de <span
                                    style="color: #009CFF;">preenchimento
                                    obrigatório</span>.
                            </p>

                            <div class="form-divider"></div>

                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('login') }}"
                                    class="btn btn-outline-secondary"
                                    style="text-decoration: none;">Voltar</a>
                                <button type="button"
                                    class="btn btn-primary next-section"
                                    data-next="section2">Próximo</button>
                            </div>
                        </div>
                        <!-- Fim Seção 1: Dados Pessoais -->

                        <!-- Seção 2: Dados Profissionais -->
                        <div class="form-section" id="section2"
                            style="display: none;">
                            <h4 class="mb-4" style="color: #009CFF;"><i
                                    class="fas fa-briefcase me-2"
                                    style="color: #009CFF;"></i> Dados
                                Profissionais</h4>
                            <div class="row g-3">

                                <div class="col-12 mt-3">
                                    <label for="provider_description"
                                        class="form-label">Sobre Você:</label>
                                    <textarea
                                        class="form-control @error('provider_description') is-invalid @enderror"
                                        id="provider_description"
                                        name="provider_description" rows="4"
                                        required>{{ old('provider_description') }}</textarea>
                                    @error('provider_description')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>
                                    @enderror
                                    <small class="text-muted ms-1 d-block mt-1">
                                        Conte um pouco sobre você e suas
                                        experiências profissionais (seja
                                        criativo!).
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <div
                                        class="d-flex align-items-center gap-2">
                                        <label for="work_radius"
                                            class="form-label mb-0"
                                            style="white-space: nowrap;">
                                            Raio de Atendimento (km):
                                        </label>
                                        <input type="number"
                                            class="form-control @error('work_radius') is-invalid @enderror"
                                            id="work_radius" name="work_radius"
                                            value="{{ old('work_radius') ?? 10 }}"
                                            min="1" required
                                            style="max-width: 100px;">
                                    </div>
                                    @error('work_radius')<div
                                        class="invalid-feedback d-block">{{
                                        $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <label for="availability"
                                        class="form-label me-2 mb-0">Disponibilidade:</label>
                                    <div class="w-50">
                                        <select id="availability"
                                            name="availability"
                                            class="form-select @error('availability') is-invalid @enderror"
                                            required>
                                            <option value="weekdays"
                                                {{ old('availability',
                                                'weekdays') == 'weekdays' ?
                                                'selected' : '' }}>Dias
                                                Úteis</option>
                                            <option value="weekends"
                                                {{ old('availability') ==
                                                'weekends' ? 'selected' : ''
                                                }}>Finais de Semana
                                            </option>
                                            <option value="both" {{
                                                old('availability') == 'both' ?
                                                'selected' : '' }}>Todos
                                                os Dias
                                            </option>
                                        </select>
                                        @error('availability')<div
                                            class="invalid-feedback">{{ $message
                                            }}</div>@enderror
                                    </div>
                                </div>

                            </div>

                            <p class="mt-4 mb-0" style="font-size: 15px;">
                                *Todos os campos são de <span
                                    style="color: #009CFF;">preenchimento
                                    obrigatório</span>.
                            </p>

                            <div class="form-divider"></div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button"
                                    class="btn btn-outline-secondary prev-section"
                                    data-prev="section1">Voltar</button>
                                <button type="button"
                                    class="btn btn-primary next-section"
                                    data-next="section3">Próximo</button>
                            </div>
                        </div>
                        <!-- Fim Seção 2: Dados Profissionais -->

                        <!-- Seção 3: Confirmação  -->
                        <div class="form-section" id="section3"
                            style="display: none;">
                            <h4 class="mb-4" style="color: #009CFF;"><i
                                    class="fas fa-check-circle me-2"
                                    style="color: #009CFF;"></i>
                                Confirmação</h4>
                            <div class="card mb-4">

                                <div class="card-body">
                                    <h5 class="card-title"
                                        style="font-weight: bold; font-size: 1.5rem; color: #6c757d;">
                                        Revise Seus Dados
                                    </h5>
                                    <div class="row">

                                        <div class="col-md-6 mt-3">
                                            <h6
                                                style="text-decoration: underline; color: #009CFF;">Dados
                                                Pessoais</h6>
                                            <p id="review-name"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                            <p id="review-email"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                            <p id="review-tax-id"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                            <p id="review-phone"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                        </div>

                                        <div class="col-md-6 mt-3">
                                            <h6
                                                style="text-decoration: underline; color: #009CFF;">Dados
                                                Profissionais</h6>
                                            <p id="review-work-radius"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                            <p id="review-availability"
                                                class="text-muted"
                                                style="font-size: 14px; margin-bottom: 4px;"></p>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <h6
                                            style="text-decoration: underline; color: #009CFF;">Sobre
                                            você</h6>
                                        <p id="review-description"
                                            class="text-muted"
                                            style="font-size: 14px; margin-bottom: 4px;"></p>
                                    </div>

                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input
                                    class="form-check-input @error('terms') is-invalid @enderror"
                                    type="checkbox"
                                    id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    Li e aceito os <a href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#termsModal">Termos de
                                        Serviço</a> e <a href="#">Política de
                                        Privacidade</a>
                                </label>
                                @error('terms')<div class="invalid-feedback">{{
                                    $message }}</div>@enderror
                            </div>

                            <div class="form-divider"></div>

                            <div class="d-flex justify-content-between mt-5">
                                <button type="button"
                                    class="btn btn-outline-secondary prev-section"
                                    data-prev="section2">Voltar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Confirmar
                                    Cadastro
                                </button>
                            </div>
                        </div>
                        <!-- Fim Seção 3: Confirmação  -->
                    </form>
                    <!-- Formulário Fim-->
                </div>
            </div>
        </div>

        <!-- Modal de Termos -->
        <div class="modal fade" id="termsModal" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Termos de Serviço</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Conteúdo dos termos -->
                        <p>Texto dos termos de serviço aqui...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim Modal de Termos -->

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/chart/chart.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script
            src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/register-provider.js') }}"></script>
    </body>

</html>