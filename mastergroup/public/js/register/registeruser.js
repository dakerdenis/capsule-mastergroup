/* =========================================================
   public/js/register/registeruser.js
   Регистрация пользователя — логика формы (2 шага)
   Чистый JS. Без зависимостей.
   Структура:
   1) Хелперы и конфиг
   2) Инициализация UI
   3) Шаг 1: валидация и переход
   4) Шаг 2: валидация и отправка
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
  /* =========================
     1) ХЕЛПЕРЫ И КОНФИГ
     ========================= */
  const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;   // простой валидатор email
  const phoneRe = /^\+[0-9]{8,15}$/;              // международный формат E.164

  const qs  = (sel, root = document) => root.querySelector(sel);
  const qsa = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const setBtnDisabled = (btn, disabled) => {
    if (!btn) return;
    btn.classList.toggle('is-disabled', !!disabled);
    btn.toggleAttribute('disabled', !!disabled);
    btn.setAttribute('aria-disabled', String(!!disabled));
  };

  /* =========================
     2) ИНИЦИАЛИЗАЦИЯ UI
     ========================= */
  // upload + кастомные селекты (гендер/страна)
  window.initFileUploads?.();
  window.initCustomSelects?.(document); // первичная инициализация для всех [data-cselect] (включая скрытые)

  const form  = qs('#regForm');
  if (!form) return;

  const step1 = qs('[data-step="1"]', form);
  const step2 = qs('[data-step="2"]', form);

  // Кнопки управления
  const nextBtn = qs('.form_next', step1);
  const back2   = qs('.form_back', step2); // может отсутствовать
  const submitBtn = qs('.form_submit', step2);

  /* =========================
     3) ШАГ 1
     ========================= */
  // Поля шага 1
  const uploadBlocks = qsa('.file-upload', step1); // 2 блока
  const nameInput    = qs('input[placeholder="Name and Surname *"]', step1);
  const birthInput   = qs('input[name="birthdate"]', step1);
  const genderSelect = qs('select[name="gender"]', step1);
  const instaInput   = qs('input[placeholder="Instagram account"]', step1); // опционально

  const blockHasFile = (b) => {
    if (!b) return false;
    if (b.classList?.contains('has-file')) return true;
    const input = qs('.file-input', b);
    if (input?.files?.length) return true;
    const preview = qs('.file-preview', b);
    return preview && preview.hidden === false;
  };

  const isStep1Valid = () => {
    const filesOk  = uploadBlocks.every(blockHasFile);
    const nameOk   = !!nameInput?.value.trim();
    const birthOk  = !!birthInput?.value.trim();
    const genderOk = !!genderSelect?.value;
    return filesOk && nameOk && birthOk && genderOk;
  };

  const updateNextState = () => setBtnDisabled(nextBtn, !isStep1Valid());

  // Слушатели для пересчёта состояния "NEXT"
  uploadBlocks.forEach(b => {
    const inp = qs('.file-input', b);
    inp?.addEventListener('change', updateNextState);
    b.addEventListener('fileupload:change', updateNextState);
  });
  [nameInput, birthInput, genderSelect, instaInput].forEach(el => {
    el?.addEventListener('input', updateNextState);
    el?.addEventListener('change', updateNextState);
  });

  updateNextState();

  // Переход на шаг 2
  nextBtn?.addEventListener('click', () => {
    if (nextBtn.classList.contains('is-disabled')) return;

    step1.classList.add('is-hidden');
    step2.classList.remove('is-hidden');
    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });

    // Доинициализировать кастомные селекты внутри шага 2 (если еще не были привязаны)
    window.initCustomSelects?.(step2);

    updateSubmitState();
  });

  // Назад со 2-го шага (если есть кнопка)
  back2?.addEventListener('click', () => {
    step2.classList.add('is-hidden');
    step1.classList.remove('is-hidden');
    step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
    updateNextState();
  });

  /* =========================
     4) ШАГ 2
     ========================= */
  // Поля шага 2
  const email2  = qs('input[name="email"]', step2);
  const phone   = qs('input[name="phone"]', step2);
  const country = qs('select[name="country"]', step2);
  const pass1   = qs('#regPassword', step2);
  const pass2   = qs('#regPassword2', step2);
  const agreeChk = qs('#agree', step2);

  // Блоки для подсветки
  const email2Blk  = qs('#email2Blk', step2);
  const phoneBlk   = qs('#phoneBlk', step2);
  const countryBlk = qs('#countryBlk', step2);
  const pass1Blk   = qs('#pass1Blk', step2);
  const pass2Blk   = qs('#pass2Blk', step2);

  // Маска телефона: оставляем '+' в начале и цифры
  phone?.addEventListener('input', () => {
    let v = phone.value.replace(/[^\d+]/g, '');
    // удаляем все '+', затем добавляем один в начало
    v = v.replace(/\+/g, '');
    v = '+' + v.replace(/\D/g, '').slice(0, 15);
    phone.value = v;
  });

  // Глаза для паролей
  qsa('.form-block--with-eye .input-eye', step2).forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.previousElementSibling;
      if (!input) return;
      const isShown = input.type === 'text';
      input.type = isShown ? 'password' : 'text';
      btn.classList.toggle('is-on', !isShown);
      btn.setAttribute('aria-pressed', String(!isShown));
    });
  });

  // Валидация шага 2
  const isStep2Valid = () => {
    const okEmail   = emailRe.test(email2?.value.trim() || '');
    const okPhone   = phoneRe.test(phone?.value.trim() || '');
    const okCountry = !!country?.value;
    const pwd1 = pass1?.value || '';
    const pwd2 = pass2?.value || '';
    const okPwd = pwd1.length > 0 && pwd2.length > 0 && pwd1 === pwd2;
    const agreed = !!agreeChk?.checked;

    email2Blk?.classList.toggle('has-error', !okEmail && !!(email2?.value.trim()));
    phoneBlk?.classList.toggle('has-error', !okPhone && !!(phone?.value.trim()));
    countryBlk?.classList.toggle('has-error', !okCountry);
    pass1Blk?.classList.toggle('has-error', !(pwd1.length > 0));
    pass2Blk?.classList.toggle('has-error', !(pwd2.length > 0) || (pwd1 && pwd2 && pwd1 !== pwd2));

    return okEmail && okPhone && okCountry && okPwd && agreed;
  };

  const updateSubmitState = () => setBtnDisabled(submitBtn, !isStep2Valid());

  // Слушатели для пересчёта
  [email2, phone, country, pass1, pass2].forEach(el => {
    el?.addEventListener('input', updateSubmitState);
    el?.addEventListener('change', updateSubmitState);
  });
  agreeChk?.addEventListener('change', updateSubmitState);

  // Страховка на submit
  form.addEventListener('submit', (e) => {
    if (!isStep2Valid()) {
      e.preventDefault();
      updateSubmitState();
    }
  });
});
