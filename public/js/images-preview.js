// public/js/images-preview.js

document.addEventListener('DOMContentLoaded', function () {
    const filesInput = document.getElementById('files');
    const previewContainer = document.getElementById('preview-container');

    filesInput.addEventListener('change', function () {
        const files = Array.from(this.files);

        // Se não houver arquivos, limpa e oculta
        if (files.length === 0) {
            previewContainer.innerHTML = '';
            previewContainer.classList.add('d-none');
            return;
        }

        // Se exceder 9 arquivos, exibe alerta, limpa e oculta
        if (files.length > 9) {
            alert('Você pode selecionar no máximo 9 arquivos.');
            this.value = '';
            previewContainer.innerHTML = '';
            previewContainer.classList.add('d-none');
            return;
        }

        // Entre 1 e 9 arquivos: exibe o container
        previewContainer.classList.remove('d-none');
        previewContainer.innerHTML = '';

        files.forEach(file => {
            const fileType = file.type.split('/')[0];

            const wrapper = document.createElement('div');
            wrapper.classList.add(
                'bg-white',
                'border',
                'rounded',
                'd-flex',
                'align-items-center',
                'justify-content-center'
            );
            wrapper.style.width = '140px';
            wrapper.style.height = '140px';
            wrapper.style.overflow = 'hidden';

            if (fileType === 'image') {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                wrapper.appendChild(img);
            } else if (fileType === 'video') {
                const icon = document.createElement('i');
                icon.classList.add('fas', 'fa-video', 'fa-2x', 'text-secondary');
                wrapper.appendChild(icon);
            }

            previewContainer.appendChild(wrapper);
        });
    });
});
