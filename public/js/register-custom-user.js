document.addEventListener('DOMContentLoaded', function () {
    const userTypeSelect = document.getElementById('user_type');
    const taxIdInput = document.getElementById('tax_id');
    const phoneInput = document.getElementById('phone');

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

    // Alterna campos com base no tipo de pessoa
    userTypeSelect.addEventListener('change', () => {
        const isPF = userTypeSelect.value === 'PF';
        document.getElementById('birth_date_field').style.display = isPF ? 'block' : 'none';
        document.getElementById('foundation_date_field').style.display = isPF ? 'none' : 'block';
        taxIdInput.value = '';
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

            section.querySelectorAll('input[required], select[required]').forEach(input => {
                if (!input.value) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            // Validação extra para telefone
const phoneValue = phoneInput.value.replace(/\D/g, '');
if (!phoneValue || !(phoneValue.length === 10 || phoneValue.length === 11)) {
    phoneInput.classList.add('is-invalid');
    valid = false;
} else {
    phoneInput.classList.remove('is-invalid');
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
        document.getElementById('review-name').textContent = `Nome: ${document.getElementById('user_name').value}`;
        document.getElementById('review-email').textContent = `E-mail: ${document.getElementById('email').value}`;
        document.getElementById('review-phone').textContent = `Telefone: ${phoneInput.value}`;
        const taxId = taxIdInput.value;
        const isPF = userTypeSelect.value === 'PF';
        document.getElementById('review-tax-id').textContent = isPF ? `CPF: ${taxId}` : `CNPJ: ${taxId}`;
    }

    // Inicialização
    if (userTypeSelect.value) userTypeSelect.dispatchEvent(new Event('change'));
    phoneInput.value = applyPhoneMask(phoneInput.value);

});
