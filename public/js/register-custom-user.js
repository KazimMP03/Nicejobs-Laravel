document.addEventListener('DOMContentLoaded', function () {
    const userTypeSelect = document.getElementById('user_type');
    const taxIdInput = document.getElementById('tax_id');
    const phoneInput = document.getElementById('phone');
    const nameInput = document.getElementById('user_name'); // ✅ Novo

    // Máscaras
    function applyCPFMask(value) {
        value = value.replace(/\D/g, '').slice(0, 11);
        return value.replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }

    function applyCNPJMask(value) {
        value = value.replace(/\D/g, '').slice(0, 14);
        return value.replace(/(\d{2})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d)/, '$1/$2')
                    .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
    }

    function applyPhoneMask(value) {
        value = value.replace(/\D/g, '').slice(0, 11);
        return value.length > 10
            ? value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')
            : value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    }

    function validatePhone() {
        const phone = phoneInput.value.replace(/\D/g, '');
        if (phone.length === 0) {
            phoneInput.classList.remove('is-invalid');
            return;
        }
        phoneInput.classList.toggle('is-invalid', !(phone.length === 10 || phone.length === 11));
    }

    userTypeSelect.addEventListener('change', () => {
    const isPF = userTypeSelect.value === 'PF';
    document.getElementById('birth_date_field').style.display = isPF ? 'block' : 'none';
    document.getElementById('foundation_date_field').style.display = isPF ? 'none' : 'block';
    

    // Atualiza o texto da label do CPF/CNPJ dinamicamente
    const taxIdLabel = document.querySelector("label[for='tax_id']");
    if (taxIdLabel) {
        taxIdLabel.textContent = isPF ? 'CPF' : 'CNPJ';
    }
});

    taxIdInput.addEventListener('input', (e) => {
        const isPF = userTypeSelect.value === 'PF';
        e.target.value = isPF ? applyCPFMask(e.target.value) : applyCNPJMask(e.target.value);
    });

    phoneInput.addEventListener('input', (e) => {
        e.target.value = applyPhoneMask(e.target.value);
        validatePhone();
    });

    phoneInput.addEventListener('blur', validatePhone);

    // Controle de seções
    document.querySelectorAll('.next-section').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.closest('.form-section');
            const nextId = btn.dataset.next;
            let valid = true;

            // Campos
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const userTypeInput = document.getElementById('user_type');
            const birthDateInput = document.getElementById('birth_date');
            const foundationDateInput = document.getElementById('foundation_date');

            // Limpa validações anteriores
            section.querySelectorAll('input, select').forEach(input => {
                input.classList.remove('is-invalid');
            });

            // ✅ Validação de nome
            if (!nameInput.value.trim() || nameInput.value.trim().length < 3) {
                nameInput.classList.add('is-invalid');
                valid = false;
            }

            // E-mail válido
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                valid = false;
            }

            // Senha mínima
            if (passwordInput.value.length < 8) {
                passwordInput.classList.add('is-invalid');
                valid = false;
            }

            // Confirmação de senha
            if (confirmPasswordInput.value !== passwordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                valid = false;
            }

            // Telefone
            const phoneValue = phoneInput.value.replace(/\D/g, '');
            if (!(phoneValue.length === 10 || phoneValue.length === 11)) {
                phoneInput.classList.add('is-invalid');
                valid = false;
            }

            // Tipo de pessoa
            if (!userTypeInput.value) {
                userTypeInput.classList.add('is-invalid');
                valid = false;
            }

            // CPF ou CNPJ
            const isPF = userTypeInput.value === 'PF';
            const taxIdValue = taxIdInput.value.replace(/\D/g, '');
            if ((isPF && taxIdValue.length !== 11) || (!isPF && taxIdValue.length !== 14)) {
                taxIdInput.classList.add('is-invalid');
                valid = false;
            }

            // Data de nascimento ou fundação
            const dateValue = isPF ? birthDateInput.value : foundationDateInput.value;
            if (!dateValue) {
                (isPF ? birthDateInput : foundationDateInput).classList.add('is-invalid');
                valid = false;
            }

            if (valid) {
                section.style.display = 'none';
                document.getElementById(nextId).style.display = 'block';
                updateStepIndicator(nextId);
                if (nextId === 'section2') updateReview();
            }
        });
    });

    document.querySelectorAll('.prev-section').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.closest('.form-section');
            const prevId = btn.dataset.prev;
            section.style.display = 'none';
            document.getElementById(prevId).style.display = 'block';
            updateStepIndicator(prevId);
        });
    });

    function updateStepIndicator(id) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        if (id === 'section1') document.querySelector('.step:nth-child(1)').classList.add('active');
        if (id === 'section2') document.querySelector('.step:nth-child(2)').classList.add('active');
    }

    function updateReview() {
        const userName = nameInput.value;
    const email = document.getElementById('email').value;
    const phone = phoneInput.value;
    const taxId = taxIdInput.value;
    const isPF = userTypeSelect.value === 'PF';
    const birthDate = document.getElementById('birth_date').value;
    const foundationDate = document.getElementById('foundation_date').value;

    // Função para formatar data do formato YYYY-MM-DD para DD/MM/YYYY
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    }

    // Preenche os campos na revisão
    document.getElementById('review-name').textContent = `Nome: ${userName}`;
    document.getElementById('review-email').textContent = `E-mail: ${email}`;
    document.getElementById('review-phone').textContent = `Telefone: ${phone}`;
    document.getElementById('review-tax-id').textContent = isPF ? `CPF: ${taxId}` : `CNPJ: ${taxId}`;

    // Insere a data de nascimento ou fundação
    const reviewContainer = document.getElementById('review-tax-id').parentElement;
    const existingDate = document.getElementById('review-date');
    if (existingDate) existingDate.remove();

    const dateP = document.createElement('p');
    dateP.id = 'review-date';
    dateP.className = 'text-muted';
    dateP.style = 'font-size: 14px; margin-bottom: 4px;';
    dateP.textContent = isPF
        ? `Data de Nascimento: ${formatDate(birthDate)}`
        : `Data de Fundação: ${formatDate(foundationDate)}`;

    reviewContainer.appendChild(dateP);
    }

    // Inicialização
    if (userTypeSelect.value) userTypeSelect.dispatchEvent(new Event('change'));
    phoneInput.value = applyPhoneMask(phoneInput.value);
});

// Mostra/esconde senha
document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const input = document.querySelector(this.getAttribute('toggle'));
        const icon = this.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
