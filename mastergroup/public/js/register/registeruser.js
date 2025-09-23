// public/js/pages/register_user.js
document.addEventListener('DOMContentLoaded', () => {
  // инициализация ui-модулей
  window.initFileUploads?.();
  window.initCustomSelects?.();
  // Pikaday подключен отдельно, он сам навесится на birthdate (или инициализируй здесь)

  const form    = document.getElementById('regForm');
  if (!form) return;

  const step1   = form.querySelector('[data-step="1"]');
  const step2   = form.querySelector('[data-step="2"]');
  const nextBtn = step1.querySelector('.form_next');
  const back2   = step2.querySelector('.form_back');

  // поля шага 1
  const uploadBlocks = step1.querySelectorAll('.file-upload'); // их 2
  const nameInput    = step1.querySelector('input[placeholder="Name and Surname *"]');
  const birthInput   = step1.querySelector('input[name="birthdate"]');
  const genderSelect = step1.querySelector('select[name="gender"]');
  const instaInput   = step1.querySelector('input[placeholder="Instagram account"]'); // опционально

  // ——— валидатор шага 1
  function isStep1Valid() {
    // 2 файла: достаточно проверить, что у каждого input есть файл
    const filesOk = Array.from(uploadBlocks).every(b => {
      const inp = b.querySelector('.file-input');
      return inp && inp.files && inp.files.length > 0;
    });

    const nameOk  = !!nameInput.value.trim();
    const birthOk = !!birthInput.value.trim(); // формат контролит пикедэй
    const genderOk= !!genderSelect.value;

    return filesOk && nameOk && birthOk && genderOk;
  }

  // ——— включение/выключение NEXT
  function updateNextState() {
    const ok = isStep1Valid();
    nextBtn.classList.toggle('is-disabled', !ok);
    nextBtn.setAttribute('aria-disabled', String(!ok));
  }

  // триггеры пересчёта
  uploadBlocks.forEach(b => {
    const inp = b.querySelector('.file-input');
    inp?.addEventListener('change', updateNextState);
  });
  [nameInput, birthInput, genderSelect, instaInput].forEach(el => {
    el && el.addEventListener('input', updateNextState);
    el && el.addEventListener('change', updateNextState);
  });

  // первичный рассчёт
  updateNextState();

  // ——— переход на шаг 2 (без submit)
  nextBtn.addEventListener('click', () => {
    if (nextBtn.classList.contains('is-disabled')) return;

    step1.classList.add('is-hidden');
    step2.classList.remove('is-hidden');
    // по желанию: скролл к началу формы
    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  // ——— назад на шаг 1
  back2.addEventListener('click', () => {
    step2.classList.add('is-hidden');
    step1.classList.remove('is-hidden');
    step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
    // вернуть состояние next по актуальным данным
    updateNextState();
  });

  // ——— финальный submit (валидация шага 2 — при необходимости)
  form.addEventListener('submit', (e) => {
    // пример минимальной проверки второго шага:
    const requiredStep2 = step2.querySelectorAll('input[required], select[required]');
    let ok = true;
    requiredStep2.forEach(el => { if (!el.value.trim()) ok = false; });
    if (!ok) {
      e.preventDefault();
      // подсветки/скролл к первому ошибочному — по желанию
    }
  });
});
