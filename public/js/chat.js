// Aguarda o carregamento completo do DOM antes de executar o script
document.addEventListener('DOMContentLoaded', function () {

    // === Definição dos elementos principais do chat ===
    const messageInput = document.getElementById('message-input');      // Campo de texto da mensagem
    const sendBtn = document.getElementById('send-btn');                // Botão de enviar texto
    const audioBtn = document.getElementById('audio-btn');              // Botão de gravar áudio
    const attachBtn = document.getElementById('attach-btn');            // Botão de abrir menu de anexos
    const attachMenu = document.getElementById('attachment-menu');      // Menu de anexos

    const imageInput = document.getElementById('hidden-image-input');   // Input oculto para fotos e vídeos
    const fileInput = document.getElementById('hidden-file-input');     // Input oculto para documentos

    const emojiBtn = document.getElementById('emoji-btn');              // Botão de abrir emoji picker
    const emojiPicker = document.getElementById('emoji-picker');        // Elemento emoji picker
    const formActionUrl = document.getElementById('chat-form').getAttribute('action'); // URL de envio das mensagens
    const csrfToken = document.querySelector('input[name="_token"]').value; // Token CSRF para segurança

    // === Alternância entre botão de enviar (texto) e botão de áudio ===
    function toggleSendAudio() {
        if (messageInput.value.trim().length > 0) {
            sendBtn.style.display = 'inline-block';
            audioBtn.style.display = 'none';
        } else {
            sendBtn.style.display = 'none';
            audioBtn.style.display = 'inline-block';
        }
    }

    // Executa uma vez ao carregar a página
    toggleSendAudio();
    // E também toda vez que o usuário digita algo
    messageInput.addEventListener('input', toggleSendAudio);

    // === Abrir/fechar o menu de anexos ===
    attachBtn.addEventListener('click', () => {
        attachMenu.classList.toggle('d-none'); // Alterna visibilidade
    });

    // Fecha o menu se clicar fora dele
    document.addEventListener('click', (e) => {
        if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
            attachMenu.classList.add('d-none');
        }
    });

    // === Função para acionar os inputs ocultos de imagem ou arquivo ===
    window.triggerFileInput = function (type) {
        attachMenu.classList.add('d-none');
        if (type === 'image') {
            imageInput.click();
        } else if (type === 'file') {
            fileInput.click();
        }
    };

    // === Função de pré-visualização de arquivos no modal ===
    function handleFileInput(input, type) {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            // Preenche informações no modal
            document.getElementById('modal-type').value = type;
            document.getElementById('file-name').innerText = file.name;
            document.getElementById('file-size').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

            const previewContainer = document.getElementById('file-preview');
            previewContainer.innerHTML = '';
            previewContainer.classList.add('d-none');

            // Mostra preview se for imagem ou vídeo
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

            // Substitui o input de arquivo antigo por um novo
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

            // Abre o modal de pré-visualização
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
    }

    // Ativa pré-visualização para imagens e arquivos
    handleFileInput(imageInput, 'image');
    handleFileInput(fileInput, 'file');

    // === Emoji Picker ===
    // Abrir e fechar emoji picker
    emojiBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
    });

    // Inserir emoji no input
    emojiPicker.addEventListener('emoji-click', (event) => {
        messageInput.value += event.detail.unicode;
        messageInput.focus();
        emojiPicker.style.display = 'none';
        messageInput.dispatchEvent(new Event('input')); // Atualiza o botão de enviar
    });

    // Fecha o emoji picker se clicar fora
    document.addEventListener('click', (e) => {
        if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });

    // === Gravação de Áudio ===
    let mediaRecorder;
    let audioChunks = [];

    audioBtn.addEventListener('click', async () => {
        // Se já está gravando, para a gravação
        if (mediaRecorder && mediaRecorder.state === "recording") {
            mediaRecorder.stop();
            return;
        }

        try {
            // Solicita permissão do microfone
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.start();
            audioChunks = [];
            audioBtn.classList.add('recording'); // Adiciona efeito visual no botão

            // Captura os dados do áudio
            mediaRecorder.addEventListener("dataavailable", event => {
                audioChunks.push(event.data);
            });

            // Ao parar, monta o arquivo e envia
            mediaRecorder.addEventListener("stop", () => {
                audioBtn.classList.remove('recording');

                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                const audioFile = new File([audioBlob], "audio_" + Date.now() + ".webm", {
                    type: 'audio/webm'
                });

                const formData = new FormData();
                formData.append('type', 'audio');
                formData.append('message', '');
                formData.append('file', audioFile);

                // Envia via fetch
                fetch(formActionUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                }).then(() => {
                    window.location.reload();
                }).catch(err => {
                    console.error("Erro ao enviar áudio:", err);
                    alert("Erro ao enviar áudio.");
                });
            });

        } catch (err) {
            alert("Permissão de microfone negada.");
            console.error(err);
        }
    });

    // === Modal de visualização de imagem ===
    window.openImage = function (url) {
        const modalImg = document.getElementById('modal-image');
        modalImg.src = url;

        const modal = new bootstrap.Modal(document.getElementById('imageViewModal'));
        modal.show();
    };

    // === Captura de Foto pela Câmera ===
    let stream = null;

    window.openCamera = async function () {
        const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
        modal.show();

        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('captured-image-preview');
        const form = document.getElementById('camera-form');

        // Reseta estados anteriores
        preview.classList.add('d-none');
        video.classList.remove('d-none');
        form.classList.add('d-none');

        document.getElementById('capture-btn').classList.remove('d-none');

        try {
            // Solicita acesso à câmera
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            alert('Não foi possível acessar a câmera.');
            console.error(err);
        }
    };

    // Função para parar a câmera
    window.stopCamera = function () {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    };

    // Ao clicar em capturar
    document.getElementById('capture-btn').addEventListener('click', () => {
        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('captured-image-preview');
        const capturedImg = document.getElementById('captured-image');
        const form = document.getElementById('camera-form');

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        const imageData = canvas.toDataURL('image/png');
        capturedImg.src = imageData;
        preview.classList.remove('d-none');

        // Cria um arquivo da imagem capturada
        fetch(imageData)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], `captured_${Date.now()}.png`, { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const input = document.getElementById('captured-file-input');
                input.files = dataTransfer.files;
            });

        // Alterna visibilidade
        video.classList.add('d-none');
        form.classList.remove('d-none');
        document.getElementById('capture-btn').classList.add('d-none');

        stopCamera();
    });

    // Refazer foto
    window.retakePhoto = function () {
        openCamera();
    };

});
