(function(){
  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const elPage = document.getElementById('cartPage');
  const USER_CPS = parseInt(elPage?.dataset.userCps || '0', 10) || 0;

  const els = {
    list: document.getElementById('cartItems'),
    selectedWrap: document.getElementById('selectedItems'),
    cpsUser: document.getElementById('cpsUser'),
    cpsSel: document.getElementById('cpsSelected'),
    cpsLeft: document.getElementById('cpsLeft'),
    place: document.getElementById('btnPlaceOrder'),
    headerCount: document.getElementById('cartCount'),
    modal: document.getElementById('confirmModal'),
    mConfirm: document.getElementById('mConfirm'),

    // NEW: модалка подтверждения заказа
    orderModal: document.getElementById('confirmOrderModal'),
    oConfirm: document.getElementById('oConfirm'),

        // SUCCESS modal
    sModal: document.getElementById('orderSuccessModal'),
    sOk: document.getElementById('sOk'),
    sOrderNumber: document.getElementById('orderNumberText'),
  };

  const modalState = { pid: null, row: null, lastFocus: null };

  function fmt(n){ return (Math.round(n*100)/100).toLocaleString(undefined,{maximumFractionDigits:2}); }

  async function getJSON(url){
    const r = await fetch(url, {credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}});
    if(!r.ok) throw new Error('HTTP '+r.status);
    return await r.json();
  }

  async function post(url, payload){
    const r = await fetch(url, {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},
      body: new URLSearchParams(payload),
      credentials:'same-origin'
    });
    if (!r.ok) {
      let msg = 'HTTP '+r.status;
      try { const d = await r.json(); if (d?.message) msg = d.message; } catch(_){}
      throw new Error(msg);
    }
    return await r.json();
  }

  function renderList(items){
    els.list.innerHTML = '';
    if(!items.length){
      els.list.innerHTML = `<div style="padding:16px;color:#97a2b6">Your cart is empty.</div>`;
      return;
    }

    for(const it of items){
      const row = document.createElement('div');
      row.className = 'cart__element';
      row.dataset.pid = String(it.id);
      row.innerHTML = `
        <div class="cart__element-image">
          <img src="${it.image}" alt="">
        </div>
        <div class="cart__element-desc">
          <div class="cart__element-desc-container">
            <div class="cart__element-name">${escapeHtml(it.name)}</div>
            <div class="cart__element-code">${escapeHtml((it.type?it.type+' - ':'') + (it.code||''))}</div>
          </div>
          <div class="cart__element__quantity">
            <button class="btn-minus"${it.qty<=0?' disabled':''}>
              <img src="/images/catalog/minus.svg" alt="">
            </button>
            <span class="qty">${it.qty}</span>
            <button class="btn-plus"${it.qty>=10?' disabled':''}>
              <img src="/images/catalog/plus.svg" alt="">
            </button>
          </div>
        </div>
        <div class="cart__element-price">CPS ${fmt(it.price)}</div>
        <div class="cart__element-select">
          <button class="remove_item btn-remove" title="Remove">
            <img src="/images/common/mdi_trash.png" alt="">
          </button>
          <button class="selected_element${it.selected?' is-selected':''}" title="Select item"></button>
        </div>
      `;
      els.list.appendChild(row);
    }
  }

  function renderSelected(items){
    els.selectedWrap.innerHTML = '';
    const selected = items.filter(x=>x.selected && x.qty>0);
    if(!selected.length){
      els.selectedWrap.innerHTML = `<div style="padding:8px 0; color:#97a2b6">No items selected.</div>`;
      return;
    }
    for(const it of selected){
      const box = document.createElement('div');
      box.className = 'selected_bin-element';
      box.innerHTML = `
        <div class="selected_bin-element-image"><img src="${it.image}" alt=""></div>
        <div class="selected_bin-element-amount">
          <span>CPS ${fmt(it.price)}</span>
          <p> x ${it.qty}</p>
        </div>
      `;
      els.selectedWrap.appendChild(box);
    }
  }

  function updateTotals(selectedSum, totalItems){
    els.cpsUser.textContent = `${fmt(USER_CPS)} CPS`;
    els.cpsSel.textContent  = `${fmt(selectedSum)} CPS`;
    const left = USER_CPS - selectedSum;
    els.cpsLeft.textContent = `${fmt(left)} CPS`;
    els.place.disabled = selectedSum <= 0;
    if (els.headerCount) els.headerCount.textContent = String(totalItems||0);
  }

  function escapeHtml(s){ return (s??'').replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

  async function load(){
    const data = await getJSON('/cart/items');
    renderList(data.items||[]);
    renderSelected(data.items||[]);
    updateTotals(data.selected_sum||0, data.total_items||0);
  }

  async function plus(pid, row){
    const d = await post('/cart/add', {product_id:pid});
    const qtyEl = row.querySelector('.qty');
    qtyEl.textContent = String(d.qty||0);
    row.querySelector('.btn-minus').disabled = (d.qty||0) <= 0;
    row.querySelector('.btn-plus').disabled  = (d.qty||0) >= 10;
    await reloadSelectedAndTotals();
  }

  async function minus(pid, row){
    const d = await post('/cart/decrement', {product_id:pid});
    const qtyEl = row.querySelector('.qty');
    const newQty = d.qty||0;
    qtyEl.textContent = String(newQty);
    row.querySelector('.btn-minus').disabled = newQty <= 0;
    row.querySelector('.btn-plus').disabled  = newQty >= 10;
    if (newQty === 0) row.remove();
    await reloadSelectedAndTotals();
  }

  async function removeNow(pid, row){
    await post('/cart/remove', {product_id:pid});
    row?.remove();
    await reloadSelectedAndTotals();
  }

  async function toggleSelect(pid, btn, toSelected){
    await post('/cart/select', {product_id:pid, selected: toSelected ? 1 : 0});
    btn.classList.toggle('is-selected', !!toSelected);
    await reloadSelectedAndTotals();
  }

  async function reloadSelectedAndTotals(){
    const data = await getJSON('/cart/items');
    renderSelected(data.items||[]);
    updateTotals(data.selected_sum||0, data.total_items||0);
  }

  // ===== Modal (Remove) =====
  function openModal(pid, row, triggerBtn){
    if(!els.modal) return;
    modalState.pid = pid;
    modalState.row = row;
    modalState.lastFocus = triggerBtn || document.activeElement;
    els.modal.classList.add('is-open');
    els.modal.setAttribute('aria-hidden','false');
    els.mConfirm?.focus();
    document.addEventListener('keydown', onEscClose);
  }
  function closeModal(){
    if(!els.modal) return;
    els.modal.classList.remove('is-open');
    els.modal.setAttribute('aria-hidden','true');
    document.removeEventListener('keydown', onEscClose);
    if (modalState.lastFocus && typeof modalState.lastFocus.focus === 'function') {
      modalState.lastFocus.focus();
    }
    modalState.pid = null; modalState.row = null; modalState.lastFocus = null;
  }
  function onEscClose(e){ if (e.key === 'Escape') closeModal(); }

  document.addEventListener('click', (e)=>{
    const closeBtn = e.target.closest('[data-m-close]');
    if (closeBtn) { e.preventDefault(); closeModal(); }
  });
  els.mConfirm?.addEventListener('click', (e)=>{
    e.preventDefault();
    const {pid,row} = modalState;
    closeModal();
    if (pid) removeNow(pid, row).catch(()=>{});
  });

  // ===== Modal (Order CONFIRM) — NEW =====
  function openOrderModal(triggerBtn){
    if(!els.orderModal) { placeOrder().catch(()=>{}); return; }
    els.orderModal.classList.add('is-open');
    els.orderModal.setAttribute('aria-hidden','false');
    els.orderModal.dataset.lastFocus = triggerBtn ? '1' : '';
    els.oConfirm?.focus();
    document.addEventListener('keydown', onEscCloseOrder);
  }
  function closeOrderModal(){
    if(!els.orderModal) return;
    els.orderModal.classList.remove('is-open');
    els.orderModal.setAttribute('aria-hidden','true');
    document.removeEventListener('keydown', onEscCloseOrder);
    // фокус обратно на кнопку Place Order
    els.place?.focus();
  }
  function onEscCloseOrder(e){ if (e.key === 'Escape') closeOrderModal(); }

  document.addEventListener('click', (e)=>{
    const closeBtn = e.target.closest('[data-o-close]');
    if (closeBtn) { e.preventDefault(); closeOrderModal(); }
  });
  els.oConfirm?.addEventListener('click', async (e)=>{
    e.preventDefault();
    els.oConfirm.disabled = true;
    try {
      await placeOrder();
    } catch(_) {}
    els.oConfirm.disabled = false;
    closeOrderModal();
  });
  // ===== Modal (Order SUCCESS) =====
  function openSuccessModal(orderNumber){
    if(!els.sModal) return;
    if (els.sOrderNumber) els.sOrderNumber.textContent = orderNumber ? '#' + String(orderNumber) : '';
    els.sModal.classList.add('is-open');
    els.sModal.setAttribute('aria-hidden','false');
    els.sOk?.focus();
    document.addEventListener('keydown', onEscCloseSuccess);
  }
  function closeSuccessModal(){
    if(!els.sModal) return;
    els.sModal.classList.remove('is-open');
    els.sModal.setAttribute('aria-hidden','true');
    document.removeEventListener('keydown', onEscCloseSuccess);
    els.place?.focus();
  }
  function onEscCloseSuccess(e){ if (e.key === 'Escape') closeSuccessModal(); }

  document.addEventListener('click', (e)=>{
    const closeBtn = e.target.closest('[data-s-close]');
    if (closeBtn) { e.preventDefault(); closeSuccessModal(); }
  });
  els.sOk?.addEventListener('click', (e)=>{ e.preventDefault(); closeSuccessModal(); });

  // ===== Page events =====
  document.addEventListener('click', (e)=>{
    const row = e.target.closest('.cart__element');
    if (row) {
      const pid = row.dataset.pid;
      const plusBtn = e.target.closest('.btn-plus');
      const minusBtn = e.target.closest('.btn-minus');
      const rmBtn   = e.target.closest('.btn-remove');
      const selBtn  = e.target.closest('.selected_element');

      if (plusBtn) { e.preventDefault(); plus(pid, row).catch(()=>{}); }
      if (minusBtn){ e.preventDefault(); minus(pid, row).catch(()=>{}); }
      if (rmBtn)   { e.preventDefault(); openModal(pid, row, rmBtn); }
      if (selBtn)  {
        e.preventDefault();
        const toSel = !selBtn.classList.contains('is-selected');
        toggleSelect(pid, selBtn, toSel).catch(()=>{});
      }
    }
  });

  document.addEventListener('DOMContentLoaded', load);

  // ===== Place order (через модалку) =====
  // ===== Place order (через модалку) =====
  async function placeOrder(){
    if (els.place) els.place.disabled = true;

    const res = await fetch('/orders/place', {
      method: 'POST',
      headers: {
        'X-Requested-With':'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF || '',
        'Content-Type':'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({}),
      credentials: 'same-origin'
    });

    const data = await res.json().catch(()=>({}));

    if (res.ok && data.ok) {
      try {
        const topbarCps = document.querySelector('.topbar__bonuses p');
        if (topbarCps && typeof data.new_cps === 'number') topbarCps.textContent = String(data.new_cps);
      } catch(e){}
      await load();
      // <-- показать success
      openSuccessModal(data.order_number || '');
    }

    if (els.place) els.place.disabled = false;
  }


  // клик по кнопке: открываем модалку подтверждения
  document.addEventListener('DOMContentLoaded', () => {
    const btn = els.place;
    if (btn) btn.addEventListener('click', (e) => {
      e.preventDefault();
      if (btn.disabled) return;
      openOrderModal(btn);
    });
  });

})();
