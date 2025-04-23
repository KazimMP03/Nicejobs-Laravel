/**
 * Formata o CEP enquanto o usuário digita no formato 99999-999
 * @param {HTMLInputElement} campo - Elemento input do CEP que está sendo formatado
 */
function formatarCEP(campo) {
    // Remove todos os caracteres não numéricos do valor atual do campo
    let cep = campo.value.replace(/\D/g, '');

    // Aplica a máscara de formatação (99999-999) quando há mais de 5 dígitos
    if (cep.length > 5) {
        cep = cep.substring(0, 5) + '-' + cep.substring(5, 8);
    }

    // Atualiza o valor do campo com o CEP formatado
    campo.value = cep;

    // Se o CEP foi completamente apagado, limpa os demais campos de endereço
    if (cep.length === 0) {
        limparCamposEndereco();
    }
}

/**
 * Limpa todos os campos relacionados ao endereço
 */
function limparCamposEndereco() {
    // Obtém e limpa cada campo do formulário
    document.getElementById('logradouro').value = '';
    document.getElementById('bairro').value = '';
    document.getElementById('cidade').value = '';
    document.getElementById('estado').value = '';
    document.getElementById('complemento').value = '';
}

/**
 * Busca informações de endereço usando a API ViaCEP
 */
function buscarEnderecoViaCEP() {
    // Obtém o elemento input do CEP
    const cepInput = document.getElementById('cep');
    // Remove formatação para obter apenas números
    const cep = cepInput.value.replace(/\D/g, '');

    // Verifica se o CEP possui 8 dígitos (tamanho válido)
    if (cep.length !== 8) {
        return; // Sai da função se o CEP não for válido
    }

    // Adiciona classe para feedback visual de carregamento (opcional)
    cepInput.classList.add('loading');

    // Faz a requisição para a API ViaCEP
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json()) // Converte a resposta para JSON
        .then(data => {
            // Verifica se a API retornou erro (CEP não encontrado)
            if (data.erro) {
                alert('CEP não encontrado');
                return;
            }

            // Preenche os campos do formulário com os dados da API
            // Usa operador lógico OR para evitar valores undefined/null
            document.getElementById('logradouro').value = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro || '';
            document.getElementById('cidade').value = data.localidade || '';
            document.getElementById('estado').value = data.uf || '';

            // Coloca o foco no campo número para facilitar a digitação
            document.getElementById('numero').focus();
        })
        .catch(error => {
            // Tratamento de erro da requisição
            console.error('Erro ao buscar CEP:', error);
            alert('Erro ao buscar CEP. Tente novamente.');
        })
        .finally(() => {
            // Remove o feedback visual de carregamento
            cepInput.classList.remove('loading');
        });
}

/**
 * Validação do campo estado para aceitar apenas 2 letras maiúsculas
 * Adiciona um listener para o evento input no campo estado
 */
document.getElementById('estado').addEventListener('input', function () {
    // Converte para maiúsculas, remove caracteres não alfabéticos e limita a 2 caracteres
    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '').substring(0, 2);
});