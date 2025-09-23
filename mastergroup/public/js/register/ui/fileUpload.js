// public/js/ui/fileUpload.js
(function (w) {
  function bind(block) {
    const input      = block.querySelector('.file-input');
    const dropZone   = block.querySelector('.file-dropzone');
    const preview    = block.querySelector('.file-preview');
    const previewImg = block.querySelector('.preview-img');
    const removeBtn  = block.querySelector('.file-remove');

    if (!input || !dropZone || !preview || !previewImg || !removeBtn) return;

    dropZone.addEventListener('click', (e) => {
      if (e.target.classList.contains('choose') || e.target.closest('.file-dropzone')) {
        input.click();
      }
    });

    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = '#0a0'; });
    dropZone.addEventListener('dragleave',     () => { dropZone.style.borderColor = '#34c81e'; });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.style.borderColor = '#34c81e';
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        showPreview(input.files[0]);
      }
    });

    input.addEventListener('change', () => {
      if (input.files.length) showPreview(input.files[0]);
    });

    function showPreview(file) {
      if (!file || !file.type.startsWith('image/')) return;
      const reader = new FileReader();
      reader.onload = (ev) => {
        previewImg.src = ev.target.result;
        preview.hidden = false;
        dropZone.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }

    removeBtn.addEventListener('click', () => {
      input.value = '';
      preview.hidden = true;
      previewImg.src = '';
      dropZone.style.display = 'block';
    });
  }

  w.initFileUploads = function (root = document) {
    root.querySelectorAll('.file-upload').forEach(bind);
  };
})(window);
