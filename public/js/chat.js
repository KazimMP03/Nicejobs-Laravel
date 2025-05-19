document.addEventListener('DOMContentLoaded', function () {
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const audioBtn = document.getElementById('audio-btn');

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
});
