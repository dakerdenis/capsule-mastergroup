// public/js/register/ui/fileUpload.js
(function (w) {
  const MAX_SIZE = 5 * 1024 * 1024; // 5

  function ensureErrorEl(block) {
    // ИЩЕМ и СОЗДАЁМ ТОЛЬКО ВНУТРИ .file-upload
    let err = block.querySelector('.file-error');
    if (!err) {
      err = document.createElement('div');
      err.className = 'file-error';
      err.setAttribute('hidden', '');
      // добавляем в конец текущего блока .file-upload
      block.appendChild(err);
    }
    return err;
  }

  function showError(block, text) {
    const err = ensureErrorEl(block);
    if (text) {
      err.textContent = text;
      err.removeAttribute('hidden');
    } else {
      err.textContent = '';
      err.setAttribute('hidden', '');
    }
  }

  function clearPreview(block) {
    const preview    = block.querySelector('.file-preview');
    const previewImg = block.querySelector('.preview-img');
    const dropZone   = block.querySelector('.file-dropzone');
    if (preview && previewImg && dropZone) {
      preview.hidden = true;
      previewImg.src = '';
      dropZone.style.display = 'block';
    }
    block.classList.remove('has-file');
    block.dispatchEvent(new CustomEvent('fileupload:change', { bubbles: true }));
  }

  function validateSize(block, file) {
    if (!file) return true;
    if (file.size > MAX_SIZE) {
      const input = block.querySelector('.file-input');
      if (input) input.value = '';
      clearPreview(block);
      showError(block, 'The file is too large. Max size is 5 MB.');
      return false;
    }
    // валидный файл — СНИМАЕМ ошибку
    showError(block, '');
    return true;
  }

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
      if (!e.dataTransfer.files.length) return;
      const f = e.dataTransfer.files[0];
      if (!f || !f.type.startsWith('image/')) return;
      if (!validateSize(block, f)) return;

      const dt = new DataTransfer();
      dt.items.add(f);
      input.files = dt.files;
      input.dispatchEvent(new Event('change', { bubbles: true }));
      showPreview(f);
    });

    input.addEventListener('change', () => {
      const f = input.files && input.files[0];
      if (!f || !f.type.startsWith('image/')) return;
      if (!validateSize(block, f)) return;
      showPreview(f);
    });

    function showPreview(file) {
      const reader = new FileReader();
      reader.onload = (ev) => {
        previewImg.src = ev.target.result;
        preview.hidden = false;
        dropZone.style.display = 'none';
        block.classList.add('has-file');
        block.dispatchEvent(new CustomEvent('fileupload:change', { bubbles: true }));
      };
      reader.readAsDataURL(file);
    }

    removeBtn.addEventListener('click', () => {
      if (input) input.value = '';
      clearPreview(block);
      showError(block, ''); // очистить текст ошибки
      input && input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  }

  w.initFileUploads = function (root = document) {
    root.querySelectorAll('.file-upload').forEach(bind);
  };
})(window);
