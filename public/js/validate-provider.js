// validate-provider.js

// Valida e-mail
function validateEmail() {
    const emailInput = document.getElementById('email');
    const emailValue = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(emailValue)) {
        emailInput.classList.add('is-invalid');
        return false;
    }

    emailInput.classList.remove('is-invalid');
    return true;
}

// Valida telefone
function validatePhone() {
    const phoneInput = document.getElementById('phone');
    const phoneValue = phoneInput.value.replace(/\D/g, '');

    if (phoneValue.length === 0) {
        phoneInput.classList.remove('is-invalid');
        return true;
    }

    const isValid = phoneValue.length === 10 || phoneValue.length === 11;

    if (!isValid) {
        phoneInput.classList.add('is-invalid');
        return false;
    }

    phoneInput.classList.remove('is-invalid');
    return true;
}
