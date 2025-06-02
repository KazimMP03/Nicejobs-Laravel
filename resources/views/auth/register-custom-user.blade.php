<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <title>NiceJob - Cadastro de Cliente</title>
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
        <link rel="stylesheet"
            href="{{ asset('css/auth/register-provider.css') }}">
    </head>

    <body>

        <div class="page-wrapper">
            <div class="register-container">
                <div
                    class="d-flex align-items-center justify-content-between my-4 mx-4">
                    <a href class>
                        <img src="{{ asset('images/logo_big_rect_round.png') }}"
                            alt="NiceJob Logo" style="height: 45px;">
                    </a>
                    <h3 class="mb-0 fw-bold text-secondary">Cadastro de
                        Cliente</h3>
                </div>

                <div class="register-body">
                    <!-- Indicador de Passos -->
                    <div class="step-indicator mb-5">
                        <div class="step active">
                            <div class="step-number">1</div>
                            <div class="step-title">Dados Pessoais</div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-title">Confirmação</div>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('register.custom-user.post') }}"
                        enctype="multipart/form-data"
                        id="clientForm">
                        @csrf
                        <input type="hidden" name="status" value="1">

                        <!-- Seção 1 -->
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
                                    class="col-md-6 position-relative form-floating">
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
                                    class="col-md-6 position-relative form-floating">
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

                        <!-- Seção 2 -->
                        <div class="form-section" id="section2"
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
                                    <div class="mt-3">
                                        <h6
                                            style="text-decoration: underline; color: #009CFF;">Dados
                                            Pessoais</h6>
                                        <p id="review-name" class="text-muted"
                                            style="font-size: 14px; margin-bottom: 4px;">
                                        </p>
                                        <p id="review-email" class="text-muted"
                                            style="font-size: 14px; margin-bottom: 4px;"></p>
                                        <p id="review-tax-id" class="text-muted"
                                            style="font-size: 14px; margin-bottom: 4px;"></p>
                                        <p id="review-phone" class="text-muted"
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
                                    data-prev="section1">Voltar</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Confirmar
                                    Cadastro
                                </button>
                            </div>
                        </div>
                    </form>
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
                            <button type="button"
                                class="btn-close btn-close-white"
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
                                            NiceJob – Cliente</h4>
                                        <small class="text-light">Última
                                            atualização: 01/06/2025</small>
                                    </div>
                                    <div class="card-body"
                                        style="line-height: 1.6; font-size: 15px;">

                                        <h6 class="fw-bold mt-4">1. ACEITAÇÃO
                                            DOS TERMOS</h6>
                                        <p>Ao criar uma conta como Cliente na
                                            plataforma <strong>NiceJob</strong>
                                            ("Plataforma"), você concorda
                                            integralmente com estes Termos de
                                            Serviço ("Termos"), que regem o uso
                                            de todos os recursos
                                            disponibilizados ao usuário
                                            contratante de serviços ("Cliente")
                                            na Plataforma. Se você não concordar
                                            com estes Termos, não deverá
                                            utilizar a Plataforma.</p>

                                        <h6 class="fw-bold mt-4">2. OBJETO</h6>
                                        <p>A NiceJob é uma plataforma digital
                                            que intermedia a conexão entre
                                            Clientes e prestadores de serviços
                                            independentes cadastrados na
                                            plataforma ("Prestadores"). A
                                            NiceJob não é parte nas relações
                                            contratuais firmadas entre Clientes
                                            e Prestadores, atuando
                                            exclusivamente como facilitadora
                                            tecnológica.</p>

                                        <h6 class="fw-bold mt-4">3.
                                            ELEGIBILIDADE E CADASTRO</h6>
                                        <p>Para criar uma conta como Cliente, o
                                            usuário deve:</p>
                                        <ul>
                                            <li>Ter no mínimo 18 (dezoito) anos
                                                ou ser legalmente capaz;</li>
                                            <li>Fornecer informações
                                                verdadeiras, completas e
                                                atualizadas;</li>
                                            <li>Criar uma senha segura e
                                                mantê-la confidencial.</li>
                                        </ul>
                                        <p>A NiceJob reserva-se o direito de
                                            suspender ou excluir contas que
                                            contenham dados falsos, incompletos
                                            ou que violem estes Termos.</p>

                                        <h6 class="fw-bold mt-4">4. USO DA
                                            PLATAFORMA</h6>
                                        <p>O Cliente compromete-se a:</p>
                                        <ul>
                                            <li>Utilizar a Plataforma de forma
                                                lícita e ética;</li>
                                            <li>Não solicitar serviços ilícitos,
                                                ofensivos ou que infrinjam
                                                normas legais;</li>
                                            <li>Avaliar os Prestadores de forma
                                                justa e respeitosa após o
                                                término do serviço;</li>
                                            <li>Não realizar acordos fora da
                                                Plataforma com Prestadores
                                                cadastrados, de forma a burlar
                                                os termos da NiceJob.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">5. CONDIÇÕES DE
                                            PAGAMENTO</h6>
                                        <p>A NiceJob pode intermediar pagamentos
                                            entre Clientes e Prestadores através
                                            de sistemas de pagamento seguro. A
                                            Plataforma pode reter taxas sobre
                                            transações, as quais serão
                                            claramente indicadas antes da
                                            contratação. O Cliente é responsável
                                            por verificar todas as informações
                                            antes de confirmar qualquer
                                            pagamento.</p>

                                        <h6 class="fw-bold mt-4">6.
                                            RESPONSABILIDADE DOS
                                            PRESTADORES</h6>
                                        <p>A qualidade, pontualidade e
                                            cumprimento dos serviços são de
                                            responsabilidade exclusiva do
                                            Prestador contratado. A NiceJob não
                                            garante a execução ou resultado dos
                                            serviços. Quaisquer disputas,
                                            inadimplementos ou danos causados
                                            pelo Prestador devem ser resolvidos
                                            diretamente entre as partes, sem
                                            prejuízo de eventual mediação
                                            voluntária oferecida pela
                                            Plataforma.</p>

                                        <h6 class="fw-bold mt-4">7. LIMITAÇÃO DE
                                            RESPONSABILIDADE</h6>
                                        <p>A NiceJob não se responsabiliza
                                            por:</p>
                                        <ul>
                                            <li>Prejuízos decorrentes de falhas
                                                ou indisponibilidades técnicas
                                                da Plataforma;</li>
                                            <li>Danos diretos, indiretos, morais
                                                ou materiais oriundos da conduta
                                                de Prestadores;</li>
                                            <li>Acuracidade ou veracidade de
                                                informações fornecidas pelos
                                                Prestadores.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">8. CONTEÚDO
                                            GERADO PELOS USUÁRIOS</h6>
                                        <p>O Cliente poderá inserir avaliações,
                                            comentários e imagens dos serviços
                                            recebidos. Tais conteúdos
                                            deverão:</p>
                                        <ul>
                                            <li>Ser verdadeiros, não conter
                                                linguagem ofensiva ou
                                                preconceituosa;</li>
                                            <li>Não infringir direitos autorais,
                                                de imagem ou propriedade
                                                intelectual;</li>
                                            <li>Ser passíveis de uso pela
                                                NiceJob para fins de marketing e
                                                melhoria da Plataforma.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">9. PROPRIEDADE
                                            INTELECTUAL</h6>
                                        <p>Todos os direitos sobre a marca
                                            NiceJob, o código-fonte, interfaces,
                                            design, banco de dados e demais
                                            componentes da Plataforma são de
                                            titularidade exclusiva da NiceJob ou
                                            de seus licenciadores.</p>

                                        <h6 class="fw-bold mt-4">10.
                                            ENCERRAMENTO DE CONTA</h6>
                                        <p>O Cliente pode solicitar o
                                            encerramento de sua conta a qualquer
                                            momento. A NiceJob poderá desativar
                                            ou suspender contas que:</p>
                                        <ul>
                                            <li>Infrinjam estes Termos;</li>
                                            <li>Apresentem atividade fraudulenta
                                                ou suspeita;</li>
                                            <li>Causarem danos à Plataforma ou a
                                                outros usuários.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">11.
                                            MODIFICAÇÕES NOS TERMOS</h6>
                                        <p>A NiceJob poderá alterar estes Termos
                                            a qualquer tempo, mediante aviso na
                                            própria Plataforma ou por e-mail. O
                                            uso contínuo da Plataforma após tais
                                            alterações será interpretado como
                                            aceitação tácita das mudanças.</p>

                                        <h6 class="fw-bold mt-4">12. LEI
                                            APLICÁVEL E FORO</h6>
                                        <p>Estes Termos são regidos pelas leis
                                            da República Federativa do Brasil.
                                            Fica eleito o foro da comarca de
                                            Jundiaí/SP como o competente para
                                            dirimir quaisquer controvérsias
                                            decorrentes destes Termos, com
                                            renúncia a qualquer outro, por mais
                                            privilegiado que seja.</p>

                                        <h6 class="fw-bold mt-4">13. DISPOSIÇÕES
                                            GERAIS</h6>
                                        <p>Caso qualquer cláusula destes Termos
                                            seja considerada inválida ou
                                            inexequível, tal cláusula será
                                            removida sem afetar as demais
                                            disposições. A não aplicação de
                                            alguma cláusula não implica em
                                            renúncia, e todas as cláusulas
                                            continuarão válidas e eficazes.</p>

                                        <p class="mt-4">Dúvidas? Fale conosco:
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
                            <h5 class="modal-title text-white fw-bold">Política
                                de
                                Privacidade</h5>
                            <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body"
                            style="max-height: 60vh; overflow-y: auto;">
                            <div class="container py-4">
                                <div class="card shadow-lg">
                                    <div class="card-header text-white fw-bold"
                                        style="background-color: #009CFF;">
                                        <h4 class="mb-0">Política de Privacidade
                                            do
                                            NiceJob – Cliente</h4>
                                        <small class="text-light">Última
                                            atualização: 01/06/2025</small>
                                    </div>
                                    <div class="card-body"
                                        style="line-height: 1.6; font-size: 15px;">

                                        <p>A presente Política de Privacidade
                                            tem por finalidade demonstrar o
                                            compromisso do
                                            <strong>NiceJob</strong> com a
                                            privacidade, segurança e
                                            transparência no tratamento das
                                            informações dos usuários que
                                            utilizam a plataforma na qualidade
                                            de <strong>CLIENTES</strong>, nos
                                            termos da Lei nº 13.709/2018 (Lei
                                            Geral de Proteção de Dados Pessoais
                                            – LGPD), bem como demais legislações
                                            aplicáveis.</p>

                                        <h6 class="fw-bold mt-4">1. DISPOSIÇÕES
                                            GERAIS</h6>
                                        <ul>
                                            <li>Esta Política esclarece como são
                                                coletados, utilizados,
                                                armazenados, tratados e
                                                compartilhados os dados pessoais
                                                fornecidos voluntariamente pelos
                                                usuários CLIENTES no ato de
                                                cadastro e durante a utilização
                                                da plataforma.</li>
                                            <li>O aceite desta Política é
                                                indispensável para o uso da
                                                plataforma. Ao se cadastrar e
                                                utilizar os serviços, o CLIENTE
                                                declara ter lido e compreendido
                                                integralmente as disposições
                                                aqui constantes.</li>
                                            <li>Comprometemo-nos a manter a
                                                integridade, confidencialidade e
                                                disponibilidade dos dados
                                                pessoais tratados, empregando
                                                medidas técnicas e
                                                administrativas de
                                                segurança.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">2. DADOS
                                            PESSOAIS COLETADOS</h6>
                                        <p>Durante o cadastro e uso da
                                            plataforma, os seguintes dados
                                            poderão ser coletados:</p>
                                        <ul>
                                            <li>Nome completo;</li>
                                            <li>Tipo de pessoa (PF ou PJ);</li>
                                            <li>CPF ou CNPJ;</li>
                                            <li>Data de nascimento ou
                                                fundação;</li>
                                            <li>E-mail;</li>
                                            <li>Telefone;</li>
                                            <li>Endereço residencial ou
                                                comercial;</li>
                                            <li>Histórico de serviços
                                                contratados;</li>
                                            <li>Mensagens trocadas com
                                                prestadores;</li>
                                            <li>Avaliações e comentários;</li>
                                            <li>Cookies, IP, geolocalização,
                                                device ID;</li>
                                            <li>Dados técnicos de login e
                                                autenticação (logs).</li>
                                        </ul>
                                        <p>Também poderemos utilizar cookies e
                                            tecnologias similares para aprimorar
                                            sua experiência e gerar
                                            estatísticas, sempre respeitando
                                            suas preferências.</p>

                                        <h6 class="fw-bold mt-4">3. FINALIDADES
                                            DO TRATAMENTO DOS DADOS</h6>
                                        <p>Seus dados poderão ser utilizados
                                            para:</p>
                                        <ul>
                                            <li>Identificação e autenticação do
                                                CLIENTE na plataforma;</li>
                                            <li>Execução de funcionalidades como
                                                busca, contato e contratação de
                                                prestadores;</li>
                                            <li>Atendimento e suporte ao
                                                usuário;</li>
                                            <li>Prevenção a fraudes e
                                                cumprimento de obrigações legais
                                                e regulatórias;</li>
                                            <li>Melhoria da usabilidade e
                                                personalização da
                                                experiência;</li>
                                            <li>Envio de comunicações
                                                institucionais, promocionais ou
                                                informativas (com
                                                consentimento);</li>
                                            <li>Realização de análises
                                                estatísticas e estudos
                                                internos.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">4.
                                            COMPARTILHAMENTO DE DADOS</h6>
                                        <p>Seus dados poderão ser compartilhados
                                            com:</p>
                                        <ul>
                                            <li>Prestadores cadastrados, para
                                                viabilizar a execução dos
                                                serviços contratados;</li>
                                            <li>Operadoras de pagamento e
                                                instituições financeiras, para
                                                processar transações;</li>
                                            <li>Parceiros tecnológicos
                                                contratados com cláusulas de
                                                confidencialidade;</li>
                                            <li>Autoridades públicas, quando
                                                legalmente exigido.</li>
                                        </ul>
                                        <p><strong>Não comercializamos os dados
                                                dos usuários.</strong></p>

                                        <h6 class="fw-bold mt-4">5.
                                            ARMAZENAMENTO E SEGURANÇA DOS
                                            DADOS</h6>
                                        <ul>
                                            <li>Dados armazenados em servidores
                                                seguros próprios ou
                                                terceirizados contratados;</li>
                                            <li>Criptografia, controle de
                                                acesso, backups periódicos e
                                                monitoramento contínuo são
                                                utilizados para proteção;</li>
                                            <li>Os dados permanecem armazenados
                                                enquanto forem necessários para
                                                as finalidades descritas, salvo
                                                obrigações legais de
                                                retenção.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">6. DIREITOS DO
                                            TITULAR DOS DADOS</h6>
                                        <p>O CLIENTE pode exercer os seguintes
                                            direitos previstos na LGPD:</p>
                                        <ul>
                                            <li>Confirmar a existência de
                                                tratamento de dados;</li>
                                            <li>Acessar, corrigir ou atualizar
                                                dados pessoais;</li>
                                            <li>Solicitar a anonimização,
                                                bloqueio ou exclusão de
                                                dados;</li>
                                            <li>Portar os dados a outro
                                                fornecedor;</li>
                                            <li>Revogar consentimento (quando
                                                aplicável);</li>
                                            <li>Solicitar a exclusão dos dados,
                                                respeitadas obrigações
                                                legais.</li>
                                        </ul>
                                        <p>Para isso, entre em contato com nosso
                                            DPO pelo e-mail: <a
                                                href="mailto:privacidade@nicejob.com.br">privacidade@nicejob.com.br</a></p>

                                        <h6 class="fw-bold mt-4">7.
                                            RESPONSABILIDADES DO CLIENTE</h6>
                                        <p>O CLIENTE deve:</p>
                                        <ul>
                                            <li>Fornecer dados verdadeiros e
                                                atualizados;</li>
                                            <li>Manter seus dados cadastrais
                                                atualizados;</li>
                                            <li>Guardar sua senha de acesso com
                                                sigilo;</li>
                                            <li>Não compartilhar a conta com
                                                terceiros;</li>
                                            <li>Utilizar a plataforma de forma
                                                ética e conforme os Termos de
                                                Serviço.</li>
                                        </ul>

                                        <h6 class="fw-bold mt-4">8. DISPOSIÇÕES
                                            FINAIS</h6>
                                        <ul>
                                            <li>Esta Política poderá ser
                                                atualizada a qualquer tempo
                                                mediante aviso na
                                                plataforma;</li>
                                            <li>É responsabilidade do CLIENTE
                                                revisar periodicamente esta
                                                Política;</li>
                                            <li>O uso contínuo da plataforma
                                                implica aceitação dos termos
                                                revisados.</li>
                                        </ul>
                                        <p>Em caso de dúvidas, fale conosco: <a
                                                href="mailto:privacidade@nicejob.com.br">privacidade@nicejob.com.br</a></p>

                                        <hr>
                                        <p class="mb-0"><strong>NICEJOB
                                                TECNOLOGIA LTDA</strong></p>
                                        <p class="mb-0">Rua Exemplo, nº 123 –
                                            São Paulo/SP – CNPJ
                                            00.000.000/0001-00</p>
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
            <script src="{{ asset('js/register-custom-user.js') }}"></script>
        </body>

    </html>