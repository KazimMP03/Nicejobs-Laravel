<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - NiceJobs</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
        }

        body {
            background-color: var(--secondary-color);
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            max-width: 1000px;
            width: 100%;
            margin: auto;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .register-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-body {
            background: white;
            padding: 2rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .logo {
            max-height: 80px;
            margin-bottom: 1.5rem;
        }

        .conditional-field {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .conditional-field.active {
            display: block;
            opacity: 1;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('images/logo.png') }}" alt="NiceJobs" class="logo">
            <h2>Cadastro de Cliente</h2>
            <p class="mb-0">Preencha seus dados para se cadastrar em nossa plataforma</p>
        </div>

        <div class="register-body">
            <form method="POST" action="{{ route('register.custom-user') }}" enctype="multipart/form-data" id="clientForm">
                @csrf

                <div class="row g-4">
                    <!-- Dados Pessoais -->
                    <div class="col-md-6">
                        <label for="user_name" class="form-label">Nome Completo*</label>
                        <input type="text" class="form-control @error('user_name') is-invalid @enderror" 
                            id="user_name" name="user_name" value="{{ old('user_name') }}" required>
                        @error('user_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="user_type" class="form-label">Tipo de Pessoa*</label>
                        <select class="form-select @error('user_type') is-invalid @enderror" 
                            id="user_type" name="user_type" required>
                            <option value="" disabled selected>Selecione...</option>
                            <option value="PF" {{ old('user_type') == 'PF' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="PJ" {{ old('user_type') == 'PJ' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tax_id" class="form-label">CPF/CNPJ*</label>
                        <input type="text" class="form-control @error('tax_id') is-invalid @enderror" 
                            id="tax_id" name="tax_id" value="{{ old('tax_id') }}" required>
                        @error('tax_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail*</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                            id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 password-toggle">
                        <label for="password" class="form-label">Senha*</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                            id="password" name="password" required>
                        <i class="fas fa-eye-slash password-toggle-icon" onclick="togglePassword('password')"></i>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 password-toggle">
                        <label for="password_confirmation" class="form-label">Confirmar Senha*</label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" required>
                        <i class="fas fa-eye-slash password-toggle-icon" onclick="togglePassword('password_confirmation')"></i>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telefone*</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Campos Condicionais -->
                    <div class="col-md-6 conditional-field" id="birth_date_field">
                        <label for="birth_date" class="form-label">Data de Nascimento*</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 conditional-field" id="foundation_date_field">
                        <label for="foundation_date" class="form-label">Data de Fundação*</label>
                        <input type="date" class="form-control @error('foundation_date') is-invalid @enderror" 
                            id="foundation_date" name="foundation_date" value="{{ old('foundation_date') }}">
                        @error('foundation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Termos -->
                    <div class="col-12 mt-4">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Concordo com os <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Termos de Serviço</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botão de Envio -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Finalizar Cadastro
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
                    <p>Conteúdo dos termos de serviço aqui...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Controle de campos condicionais
        const userType = document.getElementById('user_type');
        const taxIdField = document.getElementById('tax_id');
        
        function handleUserTypeChange() {
            const type = userType.value;
            
            // Mostrar/ocultar campos de data
            document.getElementById('birth_date_field').classList.toggle('active', type === 'PF');
            document.getElementById('foundation_date_field').classList.toggle('active', type === 'PJ');
            
            // Aplicar máscara dinâmica
            if(type === 'PF') {
                $('#tax_id').mask('000.000.000-00', {reverse: true});
                taxIdField.setAttribute('placeholder', '000.000.000-00');
            } else {
                $('#tax_id').mask('00.000.000/0000-00', {reverse: true});
                taxIdField.setAttribute('placeholder', '00.000.000/0000-00');
            }
        }

        // Máscara para telefone
        $('#phone').mask('(00) 0000-00009').on('keyup', function(e) {
            const value = this.value.replace(/\D/g, '');
            this.value = value.length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        }).trigger('keyup');

        // Event listeners
        userType.addEventListener('change', handleUserTypeChange);
        
        // Dispara o evento inicial
        if(userType.value) handleUserTypeChange();
        else $('#tax_id').mask('000.000.000-00', {reverse: true}); // Default para PF

        // Toggle de visibilidade da senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentElement.querySelector('.password-toggle-icon');
            
            if(field.type === 'password') {
                field.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                field.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
    </script>
</body>
</html>