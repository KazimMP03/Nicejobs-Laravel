{{-- resources/views/chat/partials/modals.blade.php --}}

<!-- Modal de Visualização de Imagem -->
<div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <img id="modal-image" src="" class="w-100" alt="Visualização da imagem">
      </div>
    </div>
  </div>
</div>

<!-- Modal da Câmera -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-body text-center">
        <!-- Preview da imagem capturada -->
        <div id="captured-image-preview" class="d-none">
          <img id="captured-image" src="" class="img-fluid rounded" alt="Preview">
        </div>

        <!-- Stream da câmera -->
        <video id="camera-stream" autoplay playsinline class="w-100 rounded"></video>

        <!-- Formulário para envio da foto -->
        <form id="camera-form" class="d-none" action="{{ route('chat.message.store', $chat) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="type" value="image">
          <input type="file" id="captured-file-input" name="file" class="d-none" accept="image/*">
          <input type="text" name="message" class="form-control mb-2" placeholder="Adicionar legenda (opcional)">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Enviar</button>
            <button type="button" class="btn btn-secondary w-100" onclick="retakePhoto()">Refazer</button>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button id="capture-btn" class="btn btn-danger rounded-circle" style="width: 60px; height: 60px;">
          <i class="fas fa-camera"></i>
        </button>
      </div>
    </div>
  </div>
</div>
