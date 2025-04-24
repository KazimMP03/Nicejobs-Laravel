<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - NiceJobs</title>
    
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/register-custom-user.css') }}">
</head>

<body>
    <div class="register-container">
        <div class="register-header">
            <img src="{{ asset('images/logo.png') }}" alt="NiceJobs" class="logo">
            <h2>Cadastro de Cliente</h2>
            <p class="mb-0">Preencha seus dados para se cadastrar em nossa plataforma</p>
        </div>

        <div class="register-body">
            <form method="POST" action="{{ route('register.custom-user.post') }}" id="clientForm">
                @csrf

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
                        <label for="tax_id" class="form-label" id="tax_id_label">CPF/CNPJ*</label>
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
                        <button type="button" class="password-toggle-icon" onclick="togglePassword('password')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 password-toggle">
                        <label for="password_confirmation" class="form-label">Confirmar Senha*</label>
                        <input type="password" class="form-control" 
                            id="password_confirmation" name="password_confirmation" required>
                        <button type="button" class="password-toggle-icon" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Telefone*</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                            id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 conditional-field" id="birth_date_field" style="display: none;">
                        <label for="birth_date" class="form-label">Data de Nascimento*</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 conditional-field" id="foundation_date_field" style="display: none;">
                        <label for="foundation_date" class="form-label">Data de Fundação*</label>
                        <input type="date" class="form-control @error('foundation_date') is-invalid @enderror" 
                            id="foundation_date" name="foundation_date" value="{{ old('foundation_date') }}">
                        @error('foundation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
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

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2">
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Função para alternar campos condicionais
        function handleUserTypeChange() {
            const type = document.getElementById('user_type').value;
            const birthDateField = document.getElementById('birth_date_field');
            const foundationDateField = document.getElementById('foundation_date_field');
            
            birthDateField.style.display = 'none';
            foundationDateField.style.display = 'none';
            document.getElementById('birth_date').required = false;
            document.getElementById('foundation_date').required = false;
            
            if(type === 'PF') {
                birthDateField.style.display = 'block';
                document.getElementById('birth_date').required = true;
                $('#tax_id').mask('000.000.000-00', {reverse: true});
            } else if(type === 'PJ') {
                foundationDateField.style.display = 'block';
                document.getElementById('foundation_date').required = true;
                $('#tax_id').mask('00.000.000/0000-00', {reverse: true});
            }
        }

        // Atualizar label do CPF/CNPJ
        function updateTaxIdLabel() {
            const userType = document.getElementById('user_type').value;
            const taxIdLabel = document.getElementById('tax_id_label');
            
            if (userType === 'PF') {
                taxIdLabel.textContent = 'CPF*';
            } else if (userType === 'PJ') {
                taxIdLabel.textContent = 'CNPJ*';
            }
        }

        // Máscara para telefone
        $('#phone').mask('(00) 0000-00009').on('blur', function() {
            var phone = $(this).val().replace(/\D/g, '');
            if(phone.length === 11) {
                $(this).mask('(00) 00000-0000');
            } else {
                $(this).mask('(00) 0000-00009');
            }
        });

        // Inicialização
        $(document).ready(function() {
            // Configurar máscaras iniciais
            $('#tax_id').mask('000.000.000-00', {reverse: true});
            $('#phone').mask('(00) 0000-00009');

            // Configurar campos iniciais
            updateTaxIdLabel();
            handleUserTypeChange();

            // Ouvintes de alteração
            document.getElementById('user_type').addEventListener('change', function() {
                updateTaxIdLabel();
                handleUserTypeChange();
            });

            // Restaurar estado após erro de validação
            @if(old('user_type'))
                updateTaxIdLabel();
                handleUserTypeChange();
            @endif
        });

        // Alternar visibilidade da senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
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