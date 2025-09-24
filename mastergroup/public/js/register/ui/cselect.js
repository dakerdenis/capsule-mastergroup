/* =========================================================
   public/js/register/ui/cselect.js
   Кастомный Select (ARIA-friendly)
   Особенности:
   - Привязка ОДИН раз на корень [data-cselect] (флаг data-cselect-bound)
   - Можно вызывать initCustomSelects(document|root) сколько угодно раз
   ========================================================= */

(function (w) {
  function initCustomSelect(root) {
    if (!root || root.dataset.cselectBound === '1') return;

    const native  = root.querySelector('.cselect-native');
    const toggle  = root.querySelector('.cselect-toggle');
    const list    = root.querySelector('.cselect-list');
    const valueEl = root.querySelector('.cselect-value');
    const items   = Array.from(root.querySelectorAll('.is-option'));
    if (!native || !toggle || !list || !valueEl || !items.length) {
      root.dataset.cselectBound = '1'; // чтобы больше не пытаться
      return;
    }

    // Начальное значение
    if (native.value) {
      const item = items.find(i => i.dataset.value === native.value);
      if (item) {
        valueEl.textContent = item.textContent;
        item.setAttribute('aria-selected', 'true');
      }
    } else {
      valueEl.textContent = valueEl.textContent || (items[0]?.textContent || '');
    }

    const open  = () => { list.hidden = false; toggle.setAttribute('aria-expanded','true'); list.focus(); };
    const close = () => { list.hidden = true;  toggle.setAttribute('aria-expanded','false'); items.forEach(i=>i.classList.remove('is-active')); };

    function selectItem(item) {
      items.forEach(i => i.removeAttribute('aria-selected'));
      item.setAttribute('aria-selected','true');
      valueEl.textContent = item.textContent;
      native.value = item.dataset.value || '';
      native.dispatchEvent(new Event('change', { bubbles: true }));
      close();
    }

    let activeIndex = -1;
    function setActive(i) {
      items.forEach(it => it.classList.remove('is-active'));
      activeIndex = i;
      if (items[i]) items[i].classList.add('is-active');
    }

    // Открытие/закрытие
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      list.hidden ? open() : close();
    });

    // Поддержка Enter/Space для открытия
    toggle.addEventListener('keydown', e => {
      if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
        e.preventDefault(); open(); setActive(0);
      }
      if (e.key === 'Escape') { e.preventDefault(); close(); }
    });

    // Выбор пунктов
    items.forEach((item, idx) => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        selectItem(item);
      });
      item.addEventListener('mousemove', () => setActive(idx));
    });

    // Клавиатура в списке
    list.addEventListener('keydown', e => {
      if (e.key === 'Escape')    { e.preventDefault(); close(); toggle.focus(); }
      if (e.key === 'ArrowDown') { e.preventDefault(); setActive(Math.min(activeIndex+1, items.length-1)); ensureVisible(); }
      if (e.key === 'ArrowUp')   { e.preventDefault(); setActive(Math.max(activeIndex-1, 0)); ensureVisible(); }
      if (e.key === 'Enter')     { e.preventDefault(); if (items[activeIndex]) selectItem(items[activeIndex]); }
    });

    function ensureVisible() {
      const act = items[activeIndex]; if (!act) return;
      const r = act.getBoundingClientRect(), R = list.getBoundingClientRect();
      if (r.bottom > R.bottom) list.scrollTop += (r.bottom - R.bottom);
      if (r.top < R.top)       list.scrollTop -= (R.top - r.top);
    }

    // Закрытие кликом вне
    document.addEventListener('click', (e) => {
      if (!root.contains(e.target)) close();
    });

    // Проставляем флаг, чтобы не биндить повторно
    root.dataset.cselectBound = '1';
  }

  w.initCustomSelects = function (root = document) {
    root.querySelectorAll('[data-cselect]').forEach(initCustomSelect);
  };
})(window);
