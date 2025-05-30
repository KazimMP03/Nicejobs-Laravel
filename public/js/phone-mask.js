document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');

    // Função de máscara para telefone (com e sem 9º dígito)
    function applyPhoneMask(value) {
        value = value.replace(/\D/g, '');

        if (value.length > 11) value = value.substring(0, 11);

        if (value.length > 10) {
            return value
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{5})(\d)/, '$1-$2');
        } else if (value.length > 6) {
            return value
                .replace(/(\d{2})(\d)/, '($1) $2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        } else if (value.length > 2) {
            return value.replace(/(\d{2})(\d)/, '($1) $2');
        }

        return value;
    }

    function validatePhone() {
        const phoneValue = phoneInput.value.replace(/\D/g, '');
        if (phoneValue.length === 0) {
            phoneInput.classList.remove('is-invalid');
            return true;
        }

        const isValid = phoneValue.length === 10 || phoneValue.length === 11;
        phoneInput.classList.toggle('is-invalid', !isValid);
        return isValid;
    }

    phoneInput.addEventListener('input', function (e) {
        const cursor = e.target.selectionStart;
        const oldLength = e.target.value.length;

        e.target.value = applyPhoneMask(e.target.value);

        const newLength = e.target.value.length;
        const diff = newLength - oldLength;
        e.target.setSelectionRange(cursor + diff, cursor + diff);
    });

    phoneInput.addEventListener('blur', validatePhone);

    // Inicialização
    phoneInput.value = applyPhoneMask(phoneInput.value);
});
