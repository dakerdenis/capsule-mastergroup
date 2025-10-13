(function () {
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  async function post(url, payload) {
    const res = await fetch(url, {
      method: 'POST',
      headers: {'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':CSRF,'Content-Type':'application/x-www-form-urlencoded'},
      body: new URLSearchParams(payload),
      credentials: 'same-origin'
    });
    if (!res.ok) throw new Error('HTTP '+res.status);
    return await res.json();
  }

  async function getSummary() {
    const res = await fetch('/cart/summary', {credentials:'same-origin'});
    if (!res.ok) return;
    const data = await res.json();
    setHeaderCount(data.total_items||0);
    syncCards(data.cart||{});
  }

  function setHeaderCount(n){
    const el = document.getElementById('cartCount');
    if (el) el.textContent = String(n);
  }

  function card(el){ return el.closest('.catalog__element'); }
  function pid(card){ return card?.getAttribute('data-product-id'); }
  function qtyEl(card){ return card?.querySelector('.qty'); }

  function setQtyUI(cardEl, qty){
    const q = qtyEl(cardEl);
    if (!q) return;
    q.textContent = String(qty);
    const minus = cardEl.querySelector('.btn-minus');
    const plus  = cardEl.querySelector('.btn-plus');
    if (minus) minus.disabled = qty <= 0;
    if (plus)  plus.disabled  = qty >= 10;
  }

  function syncCards(cartMap){
    document.querySelectorAll('.catalog__element').forEach(c=>{
      const id = pid(c);
      const qty = cartMap[id]?.qty ?? 0;
      setQtyUI(c, qty);
    });
  }

  async function onPlus(btn){
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/add', {product_id:id});
    setQtyUI(c, data.qty||0);
    setHeaderCount(data.total_items||0);
  }

  async function onMinus(btn){
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/decrement', {product_id:id});
    setQtyUI(c, data.qty||0);
    setHeaderCount(data.total_items||0);
  }

  async function onRemove(btn){
    const c = card(btn); const id = pid(c); if (!id) return;
    const data = await post('/cart/remove', {product_id:id});
    setQtyUI(c, 0);
    setHeaderCount(data.total_items||0);
  }

  document.addEventListener('click', (e)=>{
    const t = e.target.closest('button');
    if (!t) return;
    if (t.classList.contains('btn-plus'))  { e.preventDefault(); onPlus(t).catch(()=>{}); }
    if (t.classList.contains('btn-minus')) { e.preventDefault(); onMinus(t).catch(()=>{}); }
    if (t.classList.contains('btn-remove')){ e.preventDefault(); onPlus(t).catch(()=>{}); }
  });

  document.addEventListener('DOMContentLoaded', getSummary);
})();
