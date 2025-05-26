 // Controle das seções do formulário
    document.querySelectorAll('.next-section').forEach(button => {
    button.addEventListener('click', function () {
        const currentSection = this.closest('.form-section');
        const nextSectionId = this.getAttribute('data-next');

        // Valida os campos antes de avançar
        let isValid = true;
        const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');

        // Validação de nascimento/fundação conforme tipo de pessoa
const userType = document.getElementById('user_type').value;

const birthDateInput = document.getElementById('birth_date');
const foundationDateInput = document.getElementById('foundation_date');

// Se PF, birth_date é obrigatório
if (userType === 'PF' && (!birthDateInput.value || birthDateInput.value.trim() === '')) {
    birthDateInput.classList.add('is-invalid');
    isValid = false;
} else {
    birthDateInput.classList.remove('is-invalid');
}

// Se PJ, foundation_date é obrigatório
if (userType === 'PJ' && (!foundationDateInput.value || foundationDateInput.value.trim() === '')) {
    foundationDateInput.classList.add('is-invalid');
    isValid = false;
} else {
    foundationDateInput.classList.remove('is-invalid');
}


        inputs.forEach(input => {
            if (!input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validação específica para CPF/CNPJ
        const taxIdInput = document.getElementById('tax_id');
        const isPF = document.getElementById('user_type').value === 'PF';
        const taxIdValue = taxIdInput.value.replace(/\D/g, '');

        if (taxIdInput.required &&
            ((isPF && taxIdValue.length !== 11) || (!isPF && taxIdValue.length !== 14))) {
            taxIdInput.classList.add('is-invalid');
            isValid = false;
        }

        if (isValid) {
            currentSection.style.display = 'none';
            document.getElementById(nextSectionId).style.display = 'block';

            // Atualiza o indicador de passos
            updateStepIndicator(nextSectionId);

            // Se for a última seção, atualiza a revisão
            if (nextSectionId === 'section3') {
                updateReviewSection();
            }
        } else {
            // Rola até o primeiro erro
            const firstInvalid = currentSection.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});

document.querySelectorAll('.prev-section').forEach(button => {
    button.addEventListener('click', function () {
        const currentSection = this.closest('.form-section');
        const prevSectionId = this.getAttribute('data-prev');

        currentSection.style.display = 'none';
        document.getElementById(prevSectionId).style.display = 'block';

        // Atualiza o indicador de passos
        updateStepIndicator(prevSectionId);
    });
});

function updateStepIndicator(sectionId) {
    document.querySelectorAll('.step').forEach(step => {
        step.classList.remove('active');
    });

    if (sectionId === 'section1') {
        document.querySelector('.step:nth-child(1)').classList.add('active');
    } else if (sectionId === 'section2') {
        document.querySelector('.step:nth-child(2)').classList.add('active');
    } else if (sectionId === 'section3') {
        document.querySelector('.step:nth-child(3)').classList.add('active');
    }
}

function updateReviewSection() {
    // Dados pessoais
    document.getElementById('review-name').textContent = `Nome: ${document.getElementById('user_name').value}`;
    document.getElementById('review-email').textContent = `E-mail: ${document.getElementById('email').value}`;

    const taxId = document.getElementById('tax_id').value;
    const isPF = document.getElementById('user_type').value === 'PF';
    document.getElementById('review-tax-id').textContent = isPF
        ? `CPF: ${taxId}`
        : `CNPJ: ${taxId}`;

    document.getElementById('review-phone').textContent = `Telefone: ${document.getElementById('phone').value}`;

    // Dados profissionais
    document.getElementById('review-work-radius').textContent =
        `Raio de atendimento: ${document.getElementById('work_radius').value} km`;

    const availabilityMap = {
        weekdays: 'Dias de semana',
        weekends: 'Finais de semana',
        both: 'Ambos'
    };
    const availabilityValue = document.querySelector('input[name="availability"]:checked')?.value || '';
    document.getElementById('review-availability').textContent =
        `Disponibilidade: ${availabilityMap[availabilityValue] || 'Não informado'}`;

    // Sobre você
    document.getElementById('review-description').textContent =
        document.getElementById('provider_description').value;
}


// Controle do tipo de pessoa (PF/PJ)
document.getElementById('user_type').addEventListener('change', function () {
    const isPF = this.value === 'PF';
    const taxIdLabel = document.querySelector('label[for="tax_id"]');
    const taxIdInput = document.getElementById('tax_id');
    const taxIdHint = document.getElementById('tax_id_hint');

    // Atualiza campos de data
    document.getElementById('birth_date_field').style.display = isPF ? 'block' : 'none';
    document.getElementById('foundation_date_field').style.display = isPF ? 'none' : 'block';

    // Atualiza o campo de documento
    if (isPF) {
        taxIdLabel.textContent = 'CPF';
        taxIdInput.placeholder = '000.000.000-00';
        taxIdHint.textContent = 'Informe seu CPF (11 dígitos)';
        document.getElementById('birth_date').required = true;
        document.getElementById('foundation_date').required = false;
    } else {
        taxIdLabel.textContent = 'CNPJ';
        taxIdInput.placeholder = '00.000.000/0000-00';
        taxIdHint.textContent = 'Informe seu CNPJ (14 dígitos)';
        document.getElementById('birth_date').required = false;
        document.getElementById('foundation_date').required = true;
    }

    // Limpa e redefine o campo
    taxIdInput.value = '';
    taxIdInput.classList.remove('is-invalid');
});

document.addEventListener('DOMContentLoaded', function() {
    /**
     * =============================================
     * ELEMENTOS DO FORMULÁRIO
     * =============================================
     */
    const userTypeSelect = document.getElementById('user_type');
    const taxIdInput = document.getElementById('tax_id');
    const taxIdLabel = document.querySelector('label[for="tax_id"]');
    const taxIdHint = document.getElementById('tax_id_hint');
    const phoneInput = document.getElementById('phone');

    /**
     * =============================================
     * FUNÇÕES DE MÁSCARA E FORMATAÇÃO
     * =============================================
     */

    /**
     * Aplica máscara de CPF (000.000.000-00)
     * @param {string} value - Valor a ser formatado
     * @returns {string} Valor formatado
     */
    function applyCPFMask(value) {
        value = value.replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);
        
        return value
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }

    /**
     * Aplica máscara de CNPJ (00.000.000/0000-00)
     * @param {string} value - Valor a ser formatado
     * @returns {string} Valor formatado
     */
    function applyCNPJMask(value) {
        value = value.replace(/\D/g, '');
        if (value.length > 14) value = value.substring(0, 14);
        
        return value
            .replace(/(\d{2})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1/$2')
            .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
    }

    /**
     * Aplica máscara de telefone, aceitando dois formatos:
     * (XX) XXXX-XXXX (8 dígitos) ou (XX) XXXXX-XXXX (9 dígitos)
     * @param {string} value - Valor a ser formatado
     * @returns {string} Valor formatado
     */
    function applyPhoneMask(value) {
        value = value.replace(/\D/g, '');
        
        // Limita a 11 dígitos (DDD + 8 ou 9 dígitos)
        if (value.length > 11) value = value.substring(0, 11);
        
        // Formatação para números com 9º dígito (11 dígitos no total)
        if (value.length > 10) {
            return value
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{5})(\d)/, '$1-$2');
        } 
        // Formatação para números sem 9º dígito (10 dígitos no total)
        else if (value.length > 6) {
            return value
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        } 
        // Formatação parcial para DDD apenas
        else if (value.length > 2) {
            return value.replace(/(\d{2})(\d)/, '($1) $2');
        }
        // Retorna o valor sem formatação se for muito curto
        return value;
    }

    /**
     * Valida o telefone formatado
     * @returns {boolean} True se válido, False se inválido
     */
    function validatePhone() {
    const phoneValue = phoneInput.value.replace(/\D/g, '');

    // ⚠️ Se o campo estiver vazio, não aplica is-invalid
    if (phoneValue.length === 0) {
        phoneInput.classList.remove('is-invalid');
        return true;
    }

    // Deve ter 10 ou 11 dígitos incluindo DDD
    const isValid = phoneValue.length === 10 || phoneValue.length === 11;

    if (!isValid) {
        phoneInput.classList.add('is-invalid');
        return false;
    }

    phoneInput.classList.remove('is-invalid');
    return true;
}

    /**
     * =============================================
     * FUNÇÕES DE ATUALIZAÇÃO DE CAMPOS
     * =============================================
     */

    /**
     * Atualiza o campo de documento conforme tipo selecionado (PF/PJ)
     */
    function updateTaxIdField() {
        const isPF = userTypeSelect.value === 'PF';
        const currentValue = taxIdInput.value.replace(/\D/g, '');

        if (isPF) {
            taxIdLabel.textContent = 'CPF';
            taxIdInput.placeholder = '000.000.000-00';
            taxIdHint.textContent = 'Informe seu CPF (11 dígitos)';
            taxIdInput.value = applyCPFMask(currentValue);
        } else {
            taxIdLabel.textContent = 'CNPJ';
            taxIdInput.placeholder = '00.000.000/0000-00';
            taxIdHint.textContent = 'Informe seu CNPJ (14 dígitos)';
            taxIdInput.value = applyCNPJMask(currentValue);
        }
    }

    /**
     * =============================================
     * EVENT LISTENERS
     * =============================================
     */

    // Aplica máscara de documento enquanto digita
    taxIdInput.addEventListener('input', function(e) {
        const isPF = userTypeSelect.value === 'PF';
        const cursorPosition = e.target.selectionStart;
        const originalLength = e.target.value.length;
        
        if (isPF) {
            e.target.value = applyCPFMask(e.target.value);
        } else {
            e.target.value = applyCNPJMask(e.target.value);
        }
        
        // Mantém a posição do cursor
        const newLength = e.target.value.length;
        const lengthDiff = newLength - originalLength;
        e.target.setSelectionRange(cursorPosition + lengthDiff, cursorPosition + lengthDiff);
    });

    // Aplica máscara de telefone enquanto digita
    phoneInput.addEventListener('input', function(e) {
        const cursorPosition = e.target.selectionStart;
        const originalLength = e.target.value.length;
        
        e.target.value = applyPhoneMask(e.target.value);
        
        // Mantém a posição do cursor
        const newLength = e.target.value.length;
        const lengthDiff = newLength - originalLength;
        e.target.setSelectionRange(cursorPosition + lengthDiff, cursorPosition + lengthDiff);
        
        // Validação em tempo real
        validatePhone();
    });

    // Valida telefone ao sair do campo
    phoneInput.addEventListener('blur', validatePhone);

    // Atualiza quando muda o tipo de pessoa
    userTypeSelect.addEventListener('change', updateTaxIdField);

    
    /**
     * =============================================
     * INICIALIZAÇÃO
     * =============================================
     */

    // Configuração inicial dos campos
    updateTaxIdField();
    phoneInput.value = applyPhoneMask(phoneInput.value);
    validatePhone();

    // [Restante do seu código JavaScript...]
    // Seus outros event listeners e funções podem vir aqui
});