document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const audioBtn = document.getElementById('audio-btn');
    const attachBtn = document.getElementById('attach-btn');
    const attachMenu = document.getElementById('attachment-menu');

    const imageInput = document.getElementById('hidden-image-input');
    const cameraInput = document.getElementById('hidden-camera-input');
    const fileInput = document.getElementById('hidden-file-input');

    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPicker = document.getElementById('emoji-picker');

    // Alternar entre botão de enviar e microfone, dependendo do texto digitado
    function toggleSendAudio() {
        if (messageInput.value.trim().length > 0) {
            sendBtn.style.display = 'inline-block';
            audioBtn.style.display = 'none';
        } else {
            sendBtn.style.display = 'none';
            audioBtn.style.display = 'inline-block';
        }
    }

    toggleSendAudio(); // estado inicial
    messageInput.addEventListener('input', toggleSendAudio);

    // Abrir ou fechar o menu de anexos
    attachBtn.addEventListener('click', () => {
        attachMenu.classList.toggle('d-none');
    });

    // Fechar menu se clicar fora
    document.addEventListener('click', (e) => {
        if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
            attachMenu.classList.add('d-none');
        }
    });

    // Função para disparar os inputs ocultos ao clicar nos botões do menu
    window.triggerFileInput = function (type) {
        attachMenu.classList.add('d-none');

        if (type === 'image') {
            imageInput.click();
        } else if (type === 'file') {
            fileInput.click();
        } else if (type === 'camera') {
            cameraInput.value = ''; // reseta input
            cameraInput.click();
        }
    };

    // Função para exibir o modal de pré-visualização de arquivos
    function handleFileInput(input, type) {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            // Define o tipo no formulário do modal
            document.getElementById('modal-type').value = type;
            document.getElementById('file-name').innerText = file.name;
            document.getElementById('file-size').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            // Container de pré-visualização
            const previewContainer = document.getElementById('file-preview');
            previewContainer.innerHTML = '';
            previewContainer.classList.add('d-none');

            // Imagem
            if (type === 'image' && file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'img-fluid rounded';
                img.style.maxHeight = '200px';
                previewContainer.appendChild(img);
                previewContainer.classList.remove('d-none');
            }
            // Vídeo
            else if (type === 'image' && file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.className = 'img-fluid rounded';
                video.style.maxHeight = '200px';
                previewContainer.appendChild(video);
                previewContainer.classList.remove('d-none');
            }

            // Remove input anterior para evitar conflitos
            const form = document.getElementById('preview-form');
            const oldInput = form.querySelector('input[name="file"]');
            if (oldInput) oldInput.remove();

            // Clona o arquivo para o input do modal
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            const inputHidden = document.createElement('input');
            inputHidden.type = 'file';
            inputHidden.name = 'file';
            inputHidden.files = dataTransfer.files;
            inputHidden.classList.add('d-none');

            form.insertBefore(inputHidden, form.querySelector('.modal-body'));

            // Exibe o modal
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
    }

    // Aplica a função a todos os tipos de input de arquivo
    handleFileInput(imageInput, 'image');
    handleFileInput(cameraInput, 'image');
    handleFileInput(fileInput, 'file');

    // === Emoji Picker ===

    // Exibe ou oculta o painel de emojis
    emojiBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
    });

    // Insere o emoji no campo de texto
    emojiPicker.addEventListener('emoji-click', (event) => {
        messageInput.value += event.detail.unicode;
        messageInput.focus();
        emojiPicker.style.display = 'none';
        messageInput.dispatchEvent(new Event('input')); // atualiza o botão
    });

    // Fecha o painel de emojis ao clicar fora
    document.addEventListener('click', (e) => {
        if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });
});
