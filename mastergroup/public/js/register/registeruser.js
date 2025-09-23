// public/js/pages/register_user.js
document.addEventListener('DOMContentLoaded', () => {
  // UI инициализация
  window.initFileUploads?.();
  window.initCustomSelects?.();
  // Pikaday подключается отдельно

  const form  = document.getElementById('regForm');
  if (!form) return;

  const step1   = form.querySelector('[data-step="1"]');
  const step2   = form.querySelector('[data-step="2"]');
  const nextBtn = step1.querySelector('.form_next');
  const back2   = step2.querySelector('.form_back'); // может не быть

  // ---- ШАГ 1
  const uploadBlocks = step1.querySelectorAll('.file-upload'); // 2 блока
  const nameInput    = step1.querySelector('input[placeholder="Name and Surname *"]');
  const birthInput   = step1.querySelector('input[name="birthdate"]');
  const genderSelect = step1.querySelector('select[name="gender"]');
  const instaInput   = step1.querySelector('input[placeholder="Instagram account"]'); // опционально
  function blockHasFile(b) {
    if (b.classList && b.classList.contains('has-file')) return true;   // если fileUpload.js его ставит
    const input = b.querySelector('.file-input');
    if (input && input.files && input.files.length > 0) return true;    // выбор через диалог / dnd
    const preview = b.querySelector('.file-preview');
    if (preview && preview.hidden === false) return true;               // видно превью — считаем заполненным
    return false;
  }
  function isStep1Valid() {
    const filesOk  = Array.from(uploadBlocks).every(blockHasFile);
    const nameOk   = !!nameInput.value.trim();
    const birthOk  = !!birthInput.value.trim();
    const genderOk = !!genderSelect.value;
    return filesOk && nameOk && birthOk && genderOk;
  }

  function updateNextState() {
    const ok = isStep1Valid();
    nextBtn.classList.toggle('is-disabled', !ok);
    nextBtn.setAttribute('aria-disabled', String(!ok));
  }

  uploadBlocks.forEach(b => {
    const inp = b.querySelector('.file-input');
    inp?.addEventListener('change', updateNextState);
    // если в fileUpload.js диспатчишь кастомное событие — ловим его:
    b.addEventListener('fileupload:change', updateNextState);
  });
  [nameInput, birthInput, genderSelect, instaInput].forEach(el => {
    el && el.addEventListener('input', updateNextState);
    el && el.addEventListener('change', updateNextState);
  });

  updateNextState();

  // Переход на шаг 2 (без submit) — ОДИН обработчик
  nextBtn.addEventListener('click', () => {
    if (nextBtn.classList.contains('is-disabled')) return;
    step1.classList.add('is-hidden');
    step2.classList.remove('is-hidden');
    step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
    updateSubmitState(); // пересчёт кнопки register
  });

  // Назад со 2-го шага (если кнопки нет — тихо пропускаем)
  if (back2) {
    back2.addEventListener('click', () => {
      step2.classList.add('is-hidden');
      step1.classList.remove('is-hidden');
      step1.scrollIntoView({ behavior: 'smooth', block: 'start' });
      updateNextState();
    });
  }

  // ---- ШАГ 2
// ---- ШАГ 2
const submitBtn = step2.querySelector('.form_submit');
const agreeChk  = step2.querySelector('#agree');

// поля
const email2    = step2.querySelector('input[name="email"]');
const phone     = step2.querySelector('input[name="phone"]');
const country   = step2.querySelector('select[name="country"]');
const pass1     = step2.querySelector('#regPassword');
const pass2     = step2.querySelector('#regPassword2');

// блоки (для подсветки ошибок при желании)
const email2Blk = step2.querySelector('#email2Blk');
const phoneBlk  = step2.querySelector('#phoneBlk');
const countryBlk= step2.querySelector('#countryBlk');
const pass1Blk  = step2.querySelector('#pass1Blk');
const pass2Blk  = step2.querySelector('#pass2Blk');

// email — базовая RFC-приближенная проверка
const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// телефон: международный вид E.164 — + и 8..15 цифр
const phoneRe = /^\+[0-9]{8,15}$/;

// Маска/очистка телефона на вводе: оставляем + и цифры, один ведущий +
phone.addEventListener('input', () => {
  let v = phone.value.replace(/[^\d+]/g, '');
  // гарантируем один плюс в начале
  v = v.replace(/\+/g, '');
  if (!v.startsWith('')) v = v; // no-op, просто чтобы было ясно
  phone.value = '+' + v.replace(/[^\d]/g, '').slice(0, 15);
});

// Глаз для обоих паролей
step2.querySelectorAll('#pass1Blk .input-eye, #pass2Blk .input-eye').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = btn.previousElementSibling;
    const isShown = input.type === 'text';
    input.type = isShown ? 'password' : 'text';
    btn.classList.toggle('is-on', !isShown);
    btn.setAttribute('aria-pressed', String(!isShown));
  });
});

// Валидация шага 2
function isStep2Valid() {
  const okEmail   = emailRe.test(email2.value.trim());
  const okPhone   = phoneRe.test(phone.value.trim());
  const okCountry = !!country.value;
  const pwd1      = pass1.value;
  const pwd2      = pass2.value;
  const okPwd     = pwd1.length > 0 && pwd2.length > 0 && pwd1 === pwd2;
  const agreed    = !!agreeChk?.checked;

  // Подсветка (если хочешь визуально, раскомментируй строки ниже)
  email2Blk?.classList.toggle('has-error', !okEmail && email2.value.trim() !== '');
  phoneBlk?.classList.toggle('has-error', !okPhone && phone.value.trim() !== '');
  countryBlk?.classList.toggle('has-error', !okCountry);
  pass1Blk?.classList.toggle('has-error', !(pwd1.length > 0));
  pass2Blk?.classList.toggle('has-error', !(pwd2.length > 0) || (pwd1 && pwd2 && pwd1 !== pwd2));
  // agree можно тоже подсветить, если нужно:
  // document.getElementById('agreeBlk')?.classList.toggle('has-error', !agreed);

  return okEmail && okPhone && okCountry && okPwd && agreed;
}

function updateSubmitState() {
  const ok = isStep2Valid();
  submitBtn.classList.toggle('is-disabled', !ok);
  submitBtn.toggleAttribute('disabled', !ok);
  submitBtn.setAttribute('aria-disabled', String(!ok));
}

// слушатели
[email2, phone, country, pass1, pass2].forEach(el => {
  el.addEventListener('input',  updateSubmitState);
  el.addEventListener('change', updateSubmitState);
});
agreeChk?.addEventListener('change', updateSubmitState);

// когда перескакиваем на шаг 2 — сразу пересчитать
nextBtn.addEventListener('click', () => {
  if (nextBtn.classList.contains('is-disabled')) return;
  step1.classList.add('is-hidden');
  step2.classList.remove('is-hidden');
  step2.scrollIntoView({ behavior: 'smooth', block: 'start' });
  updateSubmitState();
});

// страховка на submit
form.addEventListener('submit', (e) => {
  if (!isStep2Valid()) {
    e.preventDefault();
    updateSubmitState();
  }
});





});
