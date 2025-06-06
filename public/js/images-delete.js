// public/js/delete-image.js

/**
 * Função que monta e submete um <form> invisível para enviar DELETE,
 * removendo uma mídia do portfólio sem aninhar formulários no HTML.
 *
 * - Usa a variável global `deleteImageRoute`, definida no Blade,
 *   e o token CSRF extraído da <meta name="csrf-token">.
 */
function deleteImage(path) {
    if (!confirm('Deseja realmente excluir este arquivo?')) {
        return;
    }

    // Captura o token CSRF
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!tokenMeta) {
        console.error('CSRF token not found');
        return;
    }
    const token = tokenMeta.getAttribute('content');

    // Cria um <form> invisível
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = deleteImageRoute; // variável global definida no Blade
    form.style.display = 'none';

    // Input _token
    const inputToken = document.createElement('input');
    inputToken.name = '_token';
    inputToken.value = token;
    form.appendChild(inputToken);

    // Input _method = DELETE
    const inputMethod = document.createElement('input');
    inputMethod.name = '_method';
    inputMethod.value = 'DELETE';
    form.appendChild(inputMethod);

    // Input media_path
    const inputPath = document.createElement('input');
    inputPath.name = 'media_path';
    inputPath.value = path;
    form.appendChild(inputPath);

    // Anexa ao <body> e submete
    document.body.appendChild(form);
    form.submit();
}
