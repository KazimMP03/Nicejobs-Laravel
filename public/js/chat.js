document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const audioBtn = document.getElementById('audio-btn');
    const attachBtn = document.getElementById('attach-btn');
    const attachMenu = document.getElementById('attachment-menu');

    const imageInput = document.getElementById('hidden-image-input');
    const cameraInput = document.getElementById('hidden-camera-input');
    const fileInput = document.getElementById('hidden-file-input');

    // Alternar entre botão enviar e microfone
    function toggleSendAudio() {
        if (messageInput.value.trim().length > 0) {
            sendBtn.style.display = 'inline-block';
            audioBtn.style.display = 'none';
        } else {
            sendBtn.style.display = 'none';
            audioBtn.style.display = 'inline-block';
        }
    }

    toggleSendAudio();
    messageInput.addEventListener('input', toggleSendAudio);

    // Abrir e fechar menu de anexo
    attachBtn.addEventListener('click', () => {
        attachMenu.classList.toggle('d-none');
    });

    // Fechar menu ao clicar fora
    document.addEventListener('click', (e) => {
        if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
            attachMenu.classList.add('d-none');
        }
    });

    // Triggers dos inputs invisíveis
    window.triggerFileInput = function (type) {
        attachMenu.classList.add('d-none');
        if (type === 'image') {
            imageInput.click();
        } else if (type === 'file') {
            fileInput.click();
        } else if (type === 'camera') {
            cameraInput.value = ''; // resetar
            cameraInput.click();
        }
    };

    // Função para tratar arquivo e mostrar modal
    function handleFileInput(input, type) {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            // Definir tipo no formulário
            document.getElementById('modal-type').value = type;
            document.getElementById('file-name').innerText = file.name;
            document.getElementById('file-size').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            // Preview visual
            const previewContainer = document.getElementById('file-preview');
            previewContainer.innerHTML = '';
            previewContainer.classList.add('d-none');

            if (type === 'image' && file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'img-fluid rounded';
                img.style.maxHeight = '200px';
                previewContainer.appendChild(img);
                previewContainer.classList.remove('d-none');
            } else if (type === 'image' && file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                video.className = 'img-fluid rounded';
                video.style.maxHeight = '200px';
                previewContainer.appendChild(video);
                previewContainer.classList.remove('d-none');
            }

            // Substituir input anterior no modal (garantir envio correto)
            const form = document.getElementById('preview-form');
            const oldInput = form.querySelector('input[name="file"]');
            if (oldInput) oldInput.remove();

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            const inputHidden = document.createElement('input');
            inputHidden.type = 'file';
            inputHidden.name = 'file';
            inputHidden.files = dataTransfer.files;
            inputHidden.classList.add('d-none');

            form.insertBefore(inputHidden, form.querySelector('.modal-body'));

            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
    }

    handleFileInput(imageInput, 'image');
    handleFileInput(cameraInput, 'image');
    handleFileInput(fileInput, 'file');
});
