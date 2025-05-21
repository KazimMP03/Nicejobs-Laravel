document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const audioBtn = document.getElementById('audio-btn');
    const attachBtn = document.getElementById('attach-btn');
    const attachMenu = document.getElementById('attachment-menu');

    const imageInput = document.getElementById('hidden-image-input');
    const fileInput = document.getElementById('hidden-file-input');

    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPicker = document.getElementById('emoji-picker');
    const formActionUrl = document.getElementById('chat-form').getAttribute('action');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    // Alternar botões enviar/áudio
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

    // Abrir/fechar menu de anexos
    attachBtn.addEventListener('click', () => {
        attachMenu.classList.toggle('d-none');
    });

    document.addEventListener('click', (e) => {
        if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
            attachMenu.classList.add('d-none');
        }
    });

    // Triggers para inputs invisíveis
    window.triggerFileInput = function (type) {
        attachMenu.classList.add('d-none');
        if (type === 'image') {
            imageInput.click();
        } else if (type === 'file') {
            fileInput.click();
        }
    };

    // Função de pré-visualização
    function handleFileInput(input, type) {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;

            document.getElementById('modal-type').value = type;
            document.getElementById('file-name').innerText = file.name;
            document.getElementById('file-size').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

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

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });
    }

    handleFileInput(imageInput, 'image');
    handleFileInput(fileInput, 'file');

    // === Emoji Picker ===
    emojiBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
    });

    emojiPicker.addEventListener('emoji-click', (event) => {
        messageInput.value += event.detail.unicode;
        messageInput.focus();
        emojiPicker.style.display = 'none';
        messageInput.dispatchEvent(new Event('input'));
    });

    document.addEventListener('click', (e) => {
        if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });

    // === Gravação de Áudio ===
    let mediaRecorder;
    let audioChunks = [];

    audioBtn.addEventListener('click', async () => {
        if (mediaRecorder && mediaRecorder.state === "recording") {
            mediaRecorder.stop();
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.start();
            audioChunks = [];
            audioBtn.classList.add('recording');

            mediaRecorder.addEventListener("dataavailable", event => {
                audioChunks.push(event.data);
            });

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

                fetch(formActionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
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

    // === Modal de Imagem ===
    window.openImage = function (url) {
        const modalImg = document.getElementById('modal-image');
        modalImg.src = url;

        const modal = new bootstrap.Modal(document.getElementById('imageViewModal'));
        modal.show();
    };

    // === Captura pela Webcam ===
    let stream = null;

    window.openCamera = async function () {
        const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
        modal.show();

        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('captured-image-preview');
        const form = document.getElementById('camera-form');

        preview.classList.add('d-none');
        video.classList.remove('d-none');
        form.classList.add('d-none');

        document.getElementById('capture-btn').classList.remove('d-none');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            alert('Não foi possível acessar a câmera.');
            console.error(err);
        }
    };

    window.stopCamera = function () {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    };

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

        fetch(imageData)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], `captured_${Date.now()}.png`, { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                const input = document.getElementById('captured-file-input');
                input.files = dataTransfer.files;
            });

        video.classList.add('d-none');
        form.classList.remove('d-none');
        document.getElementById('capture-btn').classList.add('d-none');

        stopCamera();
    });

    window.retakePhoto = function () {
        openCamera();
    };
});
