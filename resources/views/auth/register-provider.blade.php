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

        <!-- Libraries Stylesheet (mantido local para preservar estilos) -->
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}"
            rel="stylesheet">
        <link
            href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}"
            rel="stylesheet" />

        <!-- Bootstrap Local (para evitar mudança visual) -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('css/auth/register-provider.css') }}"
            rel="stylesheet">
    </head>

    <body>

        <div class="page-wrapper">
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

                                <div
                                    class="col-md-6 form-floating position-relative">
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password"
                                        placeholder="Senha" required>
                                    <label for="password">Senha</label>
                                    <span class="toggle-password"
                                        toggle="#password"
                                        style="position: absolute; top: 50%; right: 2.5rem; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                    @error('password')<div
                                        class="invalid-feedback">{{ $message
                                        }}</div>@enderror
                                </div>

                                <div
                                    class="col-md-6 form-floating position-relative">
                                    <input type="password" class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        placeholder="Confirmar Senha" required>
                                    <label for="password_confirmation">Confirmar
                                        Senha</label>
                                    <span class="toggle-password"
                                        toggle="#password_confirmation"
                                        style="position: absolute; top: 50%; right: 2.5rem; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
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
                                    Li e aceito os
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">Termos de
                                        Serviço</a>
                                    e
                                    <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#privacyModal">Política
                                        de Privacidade</a>
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

        <!-- Modal Termos de Serviço - Prestadores -->
        <div class="modal fade" id="termsModal" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #009CFF;">
                        <h5 class="modal-title text-white fw-bold">Termos de
                            Serviço</h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body"
                        style="max-height: 60vh; overflow-y: auto;">
                        <div class="container py-4">
                            <div class="card shadow-lg">
                                <div class="card-header text-white fw-bold"
                                    style="background-color: #009CFF;">
                                    <h4 class="mb-0">Termos de Serviço do
                                        NiceJob – Prestadores de Serviço</h4>
                                    <small class="text-light">Última
                                        atualização: 01/06/2025</small>
                                </div>
                                <div class="card-body"
                                    style="line-height: 1.6; font-size: 15px;">
                                    <p>Bem-vindo à <strong>NiceJob</strong>, uma
                                        plataforma digital que conecta usuários
                                        a prestadores de serviço de forma
                                        prática, segura e eficiente. Ao se
                                        cadastrar como Prestador de Serviço,
                                        você concorda integralmente com os
                                        termos abaixo.</p>

                                    <p><strong>Leia atentamente este
                                            documento.</strong> Ele constitui um
                                        contrato entre você (“Prestador” ou
                                        “Você”) e a NiceJob.</p>

                                    <h6 class="fw-bold mt-4">1. Objeto</h6>
                                    <p>Estes Termos de Serviço regulam o uso da
                                        plataforma NiceJob por Prestadores de
                                        Serviço, estabelecendo os direitos,
                                        deveres e responsabilidades quanto à
                                        prestação e oferta de serviços.</p>

                                    <h6 class="fw-bold mt-4">2. Cadastro e
                                        Elegibilidade</h6>
                                    <p><strong>2.1. Requisitos:</strong></p>
                                    <ul>
                                        <li>Ser maior de 18 anos ou emancipado
                                            legalmente;</li>
                                        <li>Possuir CPF ou CNPJ válido e
                                            ativo;</li>
                                        <li>Fornecer dados reais, completos e
                                            atualizados.</li>
                                    </ul>
                                    <p><strong>2.2. Veracidade:</strong> Você
                                        declara que todas as informações
                                        fornecidas são verídicas. A falsidade de
                                        qualquer dado pode resultar na suspensão
                                        ou exclusão imediata da conta, sem
                                        necessidade de aviso prévio.</p>
                                    <p><strong>2.3. Aprovação:</strong> A
                                        NiceJob reserva-se o direito de aprovar
                                        ou rejeitar cadastros, de forma
                                        justificada ou não, especialmente para
                                        garantir a qualidade e segurança da
                                        plataforma.</p>

                                    <h6 class="fw-bold mt-4">3. Uso da
                                        Plataforma</h6>
                                    <p><strong>3.1. Atuação:</strong> O
                                        Prestador compromete-se a:</p>
                                    <ul>
                                        <li>Oferecer serviços lícitos e
                                            compatíveis com sua
                                            especialidade;</li>
                                        <li>Cumprir com os prazos e condições
                                            acordadas com os clientes;</li>
                                        <li>Manter conduta ética, respeitosa e
                                            profissional.</li>
                                    </ul>
                                    <p><strong>3.2. Perfil:</strong> O Prestador
                                        poderá criar um perfil com:</p>
                                    <ul>
                                        <li>Foto, descrição dos serviços,
                                            valores médios e raio de
                                            atuação;</li>
                                        <li>Disponibilidade, portfólio e
                                            certificados (opcional).</li>
                                    </ul>
                                    <p><strong>3.3. Avaliações:</strong>
                                        Clientes poderão avaliar a experiência
                                        com o Prestador. A NiceJob:</p>
                                    <ul>
                                        <li>Não edita ou manipula
                                            avaliações;</li>
                                        <li>Pode remover conteúdo ofensivo,
                                            discriminatório ou ilegal.</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">4. Obrigações do
                                        Prestador</h6>
                                    <ul>
                                        <li>Manter seu cadastro atualizado;</li>
                                        <li>Cumprir as normas legais (ex:
                                            direito do consumidor, trabalhistas,
                                            fiscais);</li>
                                        <li>Responder adequadamente às
                                            solicitações dos clientes;</li>
                                        <li>Notificar imediatamente a NiceJob
                                            sobre fraudes, má conduta ou erros
                                            técnicos.</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">5. Remuneração e
                                        Pagamentos</h6>
                                    <p><strong>5.1. Modelo de Receita:</strong>
                                        A NiceJob poderá cobrar:</p>
                                    <ul>
                                        <li>Comissão sobre serviços fechados
                                            pela plataforma;</li>
                                        <li>Planos de assinatura ou destaque de
                                            perfil;</li>
                                        <li>Taxas por uso de funcionalidades
                                            premium.</li>
                                    </ul>
                                    <p><strong>5.2. Pagamentos:</strong> Os
                                        pagamentos entre Prestador e Cliente são
                                        feitos de forma direta ou via gateway de
                                        pagamento seguro indicado pela NiceJob.
                                        A plataforma pode atuar como
                                        intermediadora, garantindo segurança à
                                        transação.</p>

                                    <h6 class="fw-bold mt-4">6. Conduta
                                        Proibida</h6>
                                    <p>É expressamente proibido:</p>
                                    <ul>
                                        <li>Praticar fraude, plágio ou
                                            propaganda enganosa;</li>
                                        <li>Coletar dados de clientes para fins
                                            externos à plataforma;</li>
                                        <li>Publicar conteúdo ofensivo, racista,
                                            sexualmente explícito, etc.;</li>
                                        <li>Agir em nome da NiceJob sem
                                            autorização formal.</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">7. Privacidade e
                                        Proteção de Dados</h6>
                                    <p><strong>7.1. Tratamento de
                                            Dados:</strong> A NiceJob coleta e
                                        processa dados pessoais conforme sua
                                        Política de Privacidade, em conformidade
                                        com a LGPD.</p>
                                    <p><strong>7.2. Responsabilidades do
                                            Prestador:</strong> Você também é
                                        responsável por proteger os dados
                                        pessoais de seus clientes obtidos via
                                        plataforma, sendo vedada a venda,
                                        compartilhamento ou uso indevido dessas
                                        informações.</p>

                                    <h6 class="fw-bold mt-4">8. Propriedade
                                        Intelectual</h6>
                                    <p>Todo o conteúdo da NiceJob (marca,
                                        sistema, layout, textos, scripts) é
                                        protegido por direitos autorais. É
                                        proibido copiar, alterar ou reproduzir
                                        sem autorização prévia por escrito.</p>

                                    <h6 class="fw-bold mt-4">9.
                                        Responsabilidades</h6>
                                    <p><strong>A NiceJob:</strong></p>
                                    <ul>
                                        <li>Não garante a contratação dos
                                            serviços oferecidos;</li>
                                        <li>Atua como facilitadora da conexão
                                            entre cliente e prestador;</li>
                                        <li>Não se responsabiliza por danos
                                            decorrentes da má execução de
                                            serviços prestados ou da relação
                                            entre as partes.</li>
                                    </ul>
                                    <p><strong>O Prestador:</strong></p>
                                    <ul>
                                        <li>Assume total responsabilidade pela
                                            execução dos serviços
                                            ofertados;</li>
                                        <li>Concorda em indenizar a NiceJob por
                                            qualquer prejuízo causado por sua
                                            conduta.</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">10. Cancelamento e
                                        Encerramento</h6>
                                    <ul>
                                        <li>A NiceJob pode suspender ou cancelar
                                            contas que violem estes Termos ou a
                                            legislação vigente;</li>
                                        <li>Também poderá agir em casos de má
                                            conduta reiterada, avaliações
                                            negativas graves ou inatividade
                                            prolongada;</li>
                                        <li>O Prestador pode excluir sua conta a
                                            qualquer momento, mediante
                                            solicitação formal.</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">11. Alterações dos
                                        Termos</h6>
                                    <p>A NiceJob pode alterar estes Termos a
                                        qualquer momento. Em caso de mudanças
                                        significativas, você será notificado. A
                                        continuação do uso da plataforma após
                                        alterações será considerada como
                                        aceitação.</p>

                                    <h6 class="fw-bold mt-4">12. Foro e
                                        Legislação Aplicável</h6>
                                    <p>Estes Termos são regidos pelas leis
                                        brasileiras. Eventuais disputas serão
                                        dirimidas no foro da comarca de
                                        Jundiaí/SP, salvo disposição legal em
                                        contrário.</p>

                                    <p class="mt-4">Dúvidas? Entre em contato:
                                        <a
                                            href="mailto:suporte@nicejob.com.br">suporte@nicejob.com.br</a></p>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Política de Privacidade - Prestadores -->
        <div class="modal fade" id="privacyModal" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header"
                        style="background-color: #009CFF;">
                        <h5 class="modal-title text-white fw-bold">Política de
                            Privacidade</h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body"
                        style="max-height: 60vh; overflow-y: auto;">
                        <div class="container py-4">
                            <div class="card shadow-lg">
                                <div class="card-header text-white fw-bold"
                                    style="background-color: #009CFF;">
                                    <h4 class="mb-0">Política de Privacidade do
                                        NiceJob – Prestadores de Serviço</h4>
                                    <small class="text-light">Última
                                        atualização: 01/06/2025</small>
                                </div>
                                <div class="card-body"
                                    style="line-height: 1.6; font-size: 15px;">
                                    <p>A <strong>NiceJob</strong> valoriza e
                                        respeita a privacidade de seus usuários,
                                        especialmente os Prestadores de Serviço
                                        que confiam na nossa plataforma para
                                        divulgar seus serviços e alcançar novos
                                        clientes.</p>

                                    <p>Esta Política de Privacidade descreve
                                        como coletamos, usamos, armazenamos,
                                        compartilhamos e protegemos os dados
                                        pessoais dos Prestadores, de acordo com
                                        a <strong>Lei nº 13.709/2018 – Lei Geral
                                            de Proteção de Dados Pessoais
                                            (LGPD)</strong>.</p>

                                    <h6 class="fw-bold mt-4">1. Controlador de
                                        Dados</h6>
                                    <p>A NiceJob, inscrita sob CNPJ nº
                                        00.000.000/0001-00, com sede em
                                        Jundiaí/SP, é a controladora dos dados
                                        pessoais coletados dos Prestadores e
                                        responsável pelo seu tratamento nos
                                        termos da legislação vigente.</p>

                                    <h6 class="fw-bold mt-4">2. Dados
                                        Coletados</h6>
                                    <p><strong>2.1. Durante o
                                            cadastro:</strong></p>
                                    <ul>
                                        <li>Nome completo/razão social</li>
                                        <li>CPF ou CNPJ</li>
                                        <li>E-mail</li>
                                        <li>Número de telefone</li>
                                        <li>Tipo de pessoa (PF ou PJ)</li>
                                        <li>Data de nascimento ou fundação</li>
                                        <li>Descrição profissional</li>
                                        <li>Raio de atuação</li>
                                        <li>Disponibilidade de horários</li>
                                        <li>Categoria de serviços atendida</li>
                                        <li>Foto de perfil (opcional)</li>
                                        <li>Senha de acesso (criptografada)</li>
                                    </ul>

                                    <p><strong>2.2. Durante o uso da
                                            plataforma:</strong></p>
                                    <ul>
                                        <li>Dados de acesso (IP, localização
                                            aproximada, data e hora)</li>
                                        <li>Histórico de interações com
                                            clientes</li>
                                        <li>Dados de mensagens trocadas via
                                            sistema de chat</li>
                                        <li>Avaliações recebidas</li>
                                        <li>Documentos enviados (ex:
                                            certificados, portfólio)</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">3. Finalidades do
                                        Tratamento</h6>
                                    <ul>
                                        <li>Criar e manter o perfil do Prestador
                                            na plataforma</li>
                                        <li>Realizar a autenticação e segurança
                                            da conta</li>
                                        <li>Permitir que usuários encontrem,
                                            acessem e entrem em contato com o
                                            Prestador</li>
                                        <li>Facilitar a contratação de serviços
                                            e comunicação entre as partes</li>
                                        <li>Exibir avaliações e feedbacks
                                            públicos</li>
                                        <li>Gerar métricas de desempenho e
                                            estatísticas da plataforma</li>
                                        <li>Cumprir obrigações legais,
                                            regulatórias ou judiciais</li>
                                        <li>Promover melhorias técnicas e
                                            operacionais nos nossos
                                            serviços</li>
                                        <li>Realizar comunicação institucional,
                                            promocional ou informativa
                                            (opt-in)</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">4. Compartilhamento
                                        de Dados</h6>
                                    <p>A NiceJob <strong>não vende</strong> nem
                                        compartilha dados pessoais de
                                        Prestadores com terceiros para fins
                                        comerciais.</p>
                                    <p>Os dados poderão ser compartilhados
                                        apenas nas seguintes hipóteses:</p>
                                    <ul>
                                        <li>Com clientes interessados nos
                                            serviços (dados públicos do perfil e
                                            comunicação direta)</li>
                                        <li>Com parceiros tecnológicos que
                                            prestem serviços de hospedagem,
                                            pagamento ou analytics (com
                                            cláusulas de confidencialidade e
                                            proteção de dados)</li>
                                        <li>Com autoridades públicas, mediante
                                            requisição legal ou judicial</li>
                                        <li>Em caso de fusão, aquisição ou
                                            incorporação da empresa, com devida
                                            comunicação aos titulares</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">5. Retenção dos
                                        Dados</h6>
                                    <p>Os dados dos Prestadores são
                                        armazenados:</p>
                                    <ul>
                                        <li>Enquanto a conta estiver ativa</li>
                                        <li>Por até 5 anos após a exclusão da
                                            conta, para cumprimento de
                                            obrigações legais, fiscais ou defesa
                                            em processos
                                            judiciais/administrativos</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">6. Direitos do
                                        Prestador (Titular dos Dados)</h6>
                                    <p>Nos termos da LGPD, o Prestador pode, a
                                        qualquer momento:</p>
                                    <ul>
                                        <li>Confirmar a existência de
                                            tratamento</li>
                                        <li>Acessar seus dados pessoais</li>
                                        <li>Corrigir dados incompletos ou
                                            desatualizados</li>
                                        <li>Solicitar anonimização, bloqueio ou
                                            eliminação de dados
                                            desnecessários</li>
                                        <li>Portar os dados a outro fornecedor
                                            de serviço</li>
                                        <li>Revogar consentimento para uso de
                                            dados facultativos</li>
                                        <li>Solicitar exclusão da conta e dos
                                            dados (exceto obrigações
                                            legais)</li>
                                    </ul>
                                    <p>Contato para exercício de direitos: <a
                                            href="mailto:privacidade@nicejob.com.br">privacidade@nicejob.com.br</a></p>

                                    <h6 class="fw-bold mt-4">7. Segurança da
                                        Informação</h6>
                                    <p>Adotamos medidas rigorosas para proteger
                                        seus dados, como:</p>
                                    <ul>
                                        <li>Armazenamento criptografado de
                                            senhas</li>
                                        <li>Firewalls, controle de acesso e
                                            monitoramento constante</li>
                                        <li>SSL em todas as comunicações</li>
                                        <li>Backup e redundância de dados</li>
                                    </ul>

                                    <h6 class="fw-bold mt-4">8. Cookies e
                                        Tecnologias de Rastreamento</h6>
                                    <p>Utilizamos cookies para:</p>
                                    <ul>
                                        <li>Garantir o funcionamento da
                                            plataforma</li>
                                        <li>Lembrar preferências e sessões</li>
                                        <li>Medir desempenho e comportamento de
                                            navegação</li>
                                        <li>Exibir conteúdos personalizados</li>
                                    </ul>
                                    <p>Você pode desativar cookies no navegador,
                                        ciente de que isso pode limitar o uso da
                                        plataforma.</p>

                                    <h6 class="fw-bold mt-4">9. Transferência
                                        Internacional de Dados</h6>
                                    <p>Alguns serviços utilizados pela NiceJob
                                        podem armazenar dados em servidores fora
                                        do Brasil. Nesses casos, garantimos o
                                        nível adequado de proteção, conforme
                                        exige a LGPD.</p>

                                    <h6 class="fw-bold mt-4">10. Atualizações
                                        desta Política</h6>
                                    <p>Esta Política pode ser alterada
                                        periodicamente. Em caso de mudanças
                                        relevantes, os usuários serão
                                        notificados por e-mail ou na própria
                                        plataforma.</p>

                                    <h6 class="fw-bold mt-4">11. Aceite</h6>
                                    <p>Ao se cadastrar como Prestador, você
                                        declara estar ciente e de acordo com
                                        todos os termos desta Política de
                                        Privacidade.</p>

                                    <p class="mt-4">Dúvidas? Entre em contato
                                        conosco:</p>
                                    <ul>
                                        <li>📧 <a
                                                href="mailto:privacidade@nicejob.com.br">privacidade@nicejob.com.br</a></li>
                                        <li>🌐 <a
                                                href="https://www.nicejob.com.br"
                                                target="_blank">www.nicejob.com.br</a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript Libraries via CDN -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

        <!-- Bibliotecas locais mantidas por estilo/compatibilidade -->
        <script
            src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
        <script
            src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <!-- Main JS -->
        <script src="{{ asset('js/main.js') }}"></script>
        <script src="{{ asset('js/register-provider.js') }}"></script>
    </body>

</html>