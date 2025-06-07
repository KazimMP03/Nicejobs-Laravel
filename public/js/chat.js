// public/js/chat.js

document.addEventListener('DOMContentLoaded', function () {
    // === Elementos principais ===
    const chatForm     = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn      = document.getElementById('send-btn');
    const audioBtn     = document.getElementById('audio-btn');
    const attachBtn    = document.getElementById('attach-btn');
    const attachMenu   = document.getElementById('attachment-menu');
    const imageInput   = document.getElementById('hidden-image-input');
    const fileInput    = document.getElementById('hidden-file-input');
    const messageType  = document.getElementById('message-type');
    const emojiBtn     = document.getElementById('emoji-btn');
    const emojiPicker  = document.getElementById('emoji-picker');

    // === Alterna entre botão enviar e áudio ===
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

    // === Menu de anexos ===
    attachBtn.addEventListener('click', () => {
        attachMenu.classList.toggle('d-none');
    });
    document.addEventListener('click', e => {
        if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
            attachMenu.classList.add('d-none');
        }
    });

    // === Abre file pickers ===
    window.triggerFileInput = function(type) {
        attachMenu.classList.add('d-none');
        if (type === 'image') {
            imageInput.click();
        } else {
            fileInput.click();
        }
    };

    // === Auto-submit ao selecionar arquivo ===
    imageInput.addEventListener('change', () => {
        if (!imageInput.files.length) return;
        messageType.value = 'image';
        // move o input para dentro do form antes de enviar
        chatForm.appendChild(imageInput);
        chatForm.submit();
    });

    fileInput.addEventListener('change', () => {
        if (!fileInput.files.length) return;
        messageType.value = 'file';
        chatForm.appendChild(fileInput);
        chatForm.submit();
    });

    // === Emoji picker ===
    emojiPicker.style.display = 'none';
    emojiBtn.addEventListener('click', e => {
        e.stopPropagation();
        emojiPicker.style.display = (emojiPicker.style.display === 'none') ? 'block' : 'none';
    });
    emojiPicker.addEventListener('emoji-click', event => {
        messageInput.value += event.detail.unicode;
        messageInput.focus();
        emojiPicker.style.display = 'none';
        messageInput.dispatchEvent(new Event('input'));
    });
    document.addEventListener('click', e => {
        if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
            emojiPicker.style.display = 'none';
        }
    });

    // === Gravação de áudio ===
    let mediaRecorder, audioChunks = [];
    audioBtn.addEventListener('click', async () => {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();
            audioChunks = [];
            audioBtn.classList.add('recording');
            mediaRecorder.addEventListener('dataavailable', e => audioChunks.push(e.data));
            mediaRecorder.addEventListener('stop', () => {
                audioBtn.classList.remove('recording');
                const blob = new Blob(audioChunks, { type: 'audio/webm' });
                const file = new File([blob], `audio_${Date.now()}.webm`, { type: 'audio/webm' });
                const formData = new FormData();
                formData.append('type', 'audio');
                formData.append('message', '');
                formData.append('file', file);
                fetch(chatForm.getAttribute('action'), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                    body: formData
                }).then(() => window.location.reload())
                  .catch(() => alert('Erro ao enviar áudio.'));
            });
        } catch {
            alert('Permissão de microfone negada.');
        }
    });

    // === Modal de imagem ===
    window.openImage = url => {
        document.getElementById('modal-image').src = url;
        new bootstrap.Modal(document.getElementById('imageViewModal')).show();
    };

    // === Câmera ===
    let stream = null;
    window.openCamera = async () => {
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
        } catch {
            alert('Não foi possível acessar a câmera.');
        }
    };
    window.stopCamera = () => {
        if (stream) stream.getTracks().forEach(t => t.stop());
        stream = null;
    };
    document.getElementById('capture-btn').addEventListener('click', () => {
        const video = document.getElementById('camera-stream');
        const preview = document.getElementById('captured-image-preview');
        const imgEl = document.getElementById('captured-image');
        const form = document.getElementById('camera-form');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const dataUrl = canvas.toDataURL('image/png');
        imgEl.src = dataUrl;
        preview.classList.remove('d-none');
        fetch(dataUrl).then(r => r.blob()).then(blob => {
            const file = new File([blob], `captured_${Date.now()}.png`, { type: 'image/png' });
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('captured-file-input').files = dt.files;
        });
        video.classList.add('d-none');
        form.classList.remove('d-none');
        document.getElementById('capture-btn').classList.add('d-none');
        stopCamera();
    });
    window.retakePhoto = openCamera;

    // === Scroll final ===
    const chatBox = document.getElementById('chat-box');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
});
