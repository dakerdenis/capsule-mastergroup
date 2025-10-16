// public/js/cart.js
(function () {
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  function setCartCount(n) {
    const cnt = Math.max(0, Number(n) || 0);
    document.querySelectorAll('.js-cart-count').forEach(el => {
      el.textContent = String(cnt);
    });
    // кросс-вкладочная синхронизация (необязательно, но удобно)
    try { localStorage.setItem('cartCount', String(cnt)); } catch (e) {}
  }

  async function post(url, payload) {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF,
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(payload),
      credentials: 'same-origin'
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    return await res.json();
  }

  async function getSummary() {
    const res = await fetch('/cart/summary', { credentials: 'same-origin' });
    if (!res.ok) return;
    const data = await res.json();
    setCartCount(data.total_items || 0);
    syncCards(data.cart || {});
  }

  function card(el) { return el.closest('.catalog__element'); }
  function pid(card) { return card?.getAttribute('data-product-id'); }
  function qtyEl(card) { return card?.querySelector('.qty'); }

  function setQtyUI(cardEl, qty) {
    const q = qtyEl(cardEl);
    if (!q) return;
    const val = Math.max(0, Number(qty) || 0);
    q.textContent = String(val);
    const minus = cardEl.querySelector('.btn-minus');
    const plus  = cardEl.querySelector('.btn-plus');
    if (minus) minus.disabled = val <= 0;
    if (plus)  plus.disabled  = val >= 10;
  }

  function syncCards(cartMap) {
    document.querySelectorAll('.catalog__element').forEach(c => {
      const id = pid(c);
      const qty = cartMap[id]?.qty ?? 0;
      setQtyUI(c, qty);
    });
  }

  async function onPlus(btn) {
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/add', { product_id: id });
    setQtyUI(c, data.qty || 0);
    setCartCount(data.total_items || 0);
  }

  async function onMinus(btn) {
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/decrement', { product_id: id });
    setQtyUI(c, data.qty || 0);
    setCartCount(data.total_items || 0);
  }

  async function onRemove(btn) {
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/remove', { product_id: id });
    setQtyUI(c, 0);
    setCartCount(data.total_items || 0);
  }

  document.addEventListener('click', (e) => {
    const t = e.target.closest('button');
    if (!t) return;
    if (t.classList.contains('btn-plus'))   { e.preventDefault(); onPlus(t).catch(() => {}); }
    if (t.classList.contains('btn-minus'))  { e.preventDefault(); onMinus(t).catch(() => {}); }
    if (t.classList.contains('btn-remove')) { e.preventDefault(); onRemove(t).catch(() => {}); }
  });

  // Инициализируем состояние при загрузке страницы
  document.addEventListener('DOMContentLoaded', getSummary);

  // Обновление счётчиков, если корзина поменялась в другой вкладке
  window.addEventListener('storage', (e) => {
    if (e.key === 'cartCount') setCartCount(e.newValue);
  });

  // Доп. хук: можно из других скриптов дернуть событие
  window.addEventListener('cart:count', (e) => {
    if (e.detail && 'count' in e.detail) setCartCount(e.detail.count);
  });
})();


(function(){
  function parseQty(el){
    const n = parseInt((el?.textContent || '').trim(), 10);
    return Number.isFinite(n) ? n : 0;
  }
  function updateBinState(card){
    const qtyEl = card.querySelector('.qty');
    const qty = parseQty(qtyEl);
    card.classList.toggle('is-in-cart', qty > 0);
  }

  document.querySelectorAll('.catalog__element').forEach(card => {
    // начальная инициализация
    updateBinState(card);

    // реагируем на изменения количества (cart.js обновляет .qty)
    const qtyEl = card.querySelector('.qty');
    if (qtyEl){
      const mo = new MutationObserver(() => updateBinState(card));
      mo.observe(qtyEl, { childList:true, characterData:true, subtree:true });
    }
  });

  // если хочешь мгновенно переставлять состояние после глобальных обновлений корзины,
  // можно слушать наш собственный хук (оставлен в cart.js):
  window.addEventListener('cart:count', () => {
    document.querySelectorAll('.catalog__element').forEach(updateBinState);
  });
})();