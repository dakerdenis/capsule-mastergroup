{{-- Fancybox CSS (один раз на странице ок) --}}
@push('page-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <style>
        .pm-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(2px);
        }

        /* ← ДОБАВЬ ЭТО */
        .pm-backdrop[hidden] {
            display: none !important;
        }

        .pm-dialog {
            width: 1237px;
            height: 736px;
            position: relative;
            background-color: #353535b5;

            backdrop-filter: blur(100px);
            border-radius: 10px;
        }

        .pm-close {
            position: absolute;
            right: 10px;
            top: 10px;
            width: 28px;
            height: 28px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #D9D9D9;
            color: #000000;
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
            z-index: 200;
        }

        .popup__container {
            width: 100%;
            height: 100%;
            position: absolute;
            padding: 40px;
            display: flex;
            justify-content: space-between;
        }

        .popup__images {
            width: 623px;
            display: flex;
            justify-content: space-between;

            height: 100%;
        }

        .popup__images-big {
            width: 452px;
            height: 647px;
            background-color: #D9D9D9;
            border-radius: 8px;
        }

        .popup__images-big .popup__image {
            width: 100%;
            height: 100%;
        }

        .popup__images-small {
            display: flex;
            flex-direction: column;

        }

        .popup__image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .popup__images-small .popup__image {
            width: 154px;
            height: 154px;
            background-color: #D9D9D9;
            border-radius: 8px;
            margin-bottom: 10px;
        }


        .popup__content {
            width: 460px;
            height: 100%;
        }

        .popup__name {
            color: #D9D9D9;
            width: 383px;
            height: 80px;

            font-weight: 300;
            font-style: Light;
            font-size: 32px;

            line-height: 40px;
            letter-spacing: 0.15px;
            text-transform: uppercase;

        }

        .popup__code {
            margin-top: 15px;
            font-weight: 400;
            font-size: 20px;
            line-height: 16px;
            letter-spacing: 0.4px;
            margin-bottom: 28px;
            color: #D9D9D9;
        }

        .popup__price-amount {
            width: 383px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup__price {
            color: #34C81E;
            font-weight: 500;
            font-size: 20px;

            line-height: 120%;
            letter-spacing: 0.15px;
            text-align: right;

        }



        .popup__desc {
            color: #D9D9D9;
            font-weight: 400;

            font-size: 20px;

            line-height: 32.97px;
            letter-spacing: 0px;
            margin-top: 100px;
        }

        .popup__amount .catalog__element-amount {
            background-color: #FBFBFB;
        }

        .popup__loader {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
            backdrop-filter: blur(2px);
        }

        .popup__loader[hidden] {
            display: none !important
        }

        .spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px
        }

        .spinner .ring {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, .25);
            border-top-color: #34C81E;
            animation: spin 0.8s linear infinite;
        }

        .spinner .txt {
            color: #cfe0ff;
            letter-spacing: .3px;
            font-size: 14px
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @media only screen and (max-width: 1480px) {
            .pm-dialog {
                width: 1085px;
                height: 480px;
            }

            .popup__container {
                padding: 20px;
            }

            .popup__images-big {
                height: 440px;
                width: 465px;
            }

            .popup__images {
                width: 580px;
            }

            .popup__images-small .popup__image {
                width: 100px;
                height: 100px;
            }

            .popup__content {
                width: 440px;
            }

            .popup__code {
                margin-top: 10px;
                font-weight: 400;
                font-size: 17px;
                line-height: 20px;
                letter-spacing: 0.4px;
                margin-bottom: 16px;
                color: #D9D9D9;
            }

            .popup__desc {
                margin-top: 50px;
                font-size: 16px;
                line-height: 27.97px;
            }
        }


        @media only screen and (max-width: 1000px) {
            .pm-dialog {
                width: 95%;
                height: 100%;
                max-height: 90vh;

            }

            .popup__container {
                flex-direction: column;
            }

            .popup__images {
                width: 100%;
                height: 50%;
            }

            .popup__images-big {
                width: 75%;
                height: 100%;
            }

            .popup__images-small {
                width: 20%;
                height: 100%;
            }

            .popup__images-small .popup__image {
                width: 100%;
                height: calc(25% - 20px);
            }

            .popup__content {
                width: 100%;
                height: 48%;
            }

            .popup__container {
                padding-top: 50px;
            }

            .popup__name {
                color: #D9D9D9;
                width: 100%;
                height: 70px;
                font-weight: 300;
                font-style: Light;
                font-size: 22px;
                line-height: 35px;
                letter-spacing: 0.15px;
                text-transform: uppercase;
            }

            .popup__code {
                margin-top: 7px;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
            }

            .popup__price-amount {
                width: 100%;
            }

            .popup__desc {
                margin-top: 15px;
            }

            .popup__amount .catalog__element-amount {
                width: 150px;
                height: 40px;
            }

            .popup__amount .catalog__element-amount button {
                width: 50px;
            }

            .popup__amount .catalog__element-amount span {
                font-size: 20px;
            }
        }
    </style>
@endpush

{{-- MODAL --}}
<div id="productModal" class="pm-backdrop" hidden>
    <div class="pm-dialog">
        <button class="pm-close" type="button" aria-label="Close">&times;</button>

        <div class="popup__loader" hidden aria-live="polite" aria-busy="true">
            <div class="spinner">
                <div class="ring"></div>
                <div class="txt">Loading…</div>
            </div>
        </div>

        {{-- ТВОЙ НЕ МЕНЯЕМЫЙ ЛЕЙАУТ --}}
        <div class="popup__container">
            <div class="popup__images">
                <div class="popup__images-big">
                    <div class="popup__image"><img src="" alt=""></div>
                </div>
                <div class="popup__images-small">
                    <div class="popup__image"></div>
                    <div class="popup__image"></div>
                    <div class="popup__image"></div>
                    <div class="popup__image"></div>
                </div>
            </div>

            <div class="popup__content">
                <div class="popup__name">Name of the product</div>
                <div class="popup__code">Black - UT894 X7</div>
                <div class="popup__price-amount">
                    <div class="popup__price">CPS 0</div>
                    <div class="popup__amount">
                        <div class="catalog__element-amount" data-role="modal-controls">
                            <button class="btn-minus" type="button" disabled>
                                <img src="{{ asset('images/catalog/minus.svg') }}" alt="">
                            </button>
                            <span class="qty">0</span>
                            <button class="btn-plus" type="button" disabled>
                                <img src="{{ asset('images/catalog/plus.svg') }}" alt="">
                            </button>
                        </div>
                    </div>
                </div>

                <div class="popup__desc">—</div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
(function(){
  const modal = document.getElementById('productModal');
  if(!modal) return;

  const bigBox     = document.querySelector('.popup__images-big .popup__image');
  const smallBoxes = document.querySelectorAll('.popup__images-small .popup__image');

  const nameEl  = document.querySelector('.popup__name');
  const codeEl  = document.querySelector('.popup__code');
  const priceEl = document.querySelector('.popup__price');
  const descEl  = document.querySelector('.popup__desc');

  const container = document.querySelector('.popup__container');
  const loader    = document.querySelector('.popup__loader');

  // Кнопки в модалке
  const controls  = document.querySelector('[data-role="modal-controls"]');
  const btnMinus  = controls.querySelector('.btn-minus');
  const btnPlus   = controls.querySelector('.btn-plus');
  const qtyEl     = controls.querySelector('.qty');

  const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const headerCount = document.getElementById('cartCount');

  function openModal(){ modal.hidden = false; document.body.style.overflow = 'hidden'; }
  function closeModal(){
    modal.hidden = true; document.body.style.overflow = '';
    modal.removeAttribute('data-pid');
    bigBox.innerHTML = '<img src="" alt="">';
    smallBoxes.forEach(b => { b.innerHTML=''; b.style.display=''; });
    setQtyUI(0); disableCtrls(true);
    hideLoader();
  }
  function showLoader(){ container.style.display='none'; loader.hidden=false; }
  function hideLoader(){ loader.hidden=true; container.style.display='flex'; }

  modal.querySelector('.pm-close').addEventListener('click', closeModal);
  modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) closeModal(); });

  function formatPrice(n){ n = Number(n||0); return `CPS ${n.toLocaleString(undefined,{maximumFractionDigits:0})}`; }

  // ===== Cart helpers (как в catalog/cart.js) =====
  async function post(url, payload){
    const r = await fetch(url, {
      method:'POST',
      headers:{'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':CSRF,'Content-Type':'application/x-www-form-urlencoded'},
      body:new URLSearchParams(payload),
      credentials:'same-origin'
    });
    if(!r.ok){
      let msg = 'HTTP '+r.status;
      try{ const d = await r.json(); if(d?.message) msg = d.message; }catch(_){}
      throw new Error(msg);
    }
    return await r.json();
  }
  async function getSummary(){
    const r = await fetch('/cart/summary', {credentials:'same-origin', headers:{'X-Requested-With':'XMLHttpRequest'}});
    if(!r.ok) return { total_items:0, cart:{} };
    return await r.json();
  }
  function setHeaderCount(n){ if(headerCount) headerCount.textContent = String(n||0); }
  function setQtyUI(qty){
    qtyEl.textContent = String(qty);
    btnMinus.disabled = qty <= 0;
    btnPlus.disabled  = qty >= 10;
  }
  function disableCtrls(state){
    btnMinus.disabled = state;
    btnPlus.disabled  = state;
  }

  async function syncQtyFromServer(pid){
    const sum = await getSummary();
    const map = sum.cart || {};
    const q   = map[String(pid)]?.qty ?? 0;
    setQtyUI(q);
    setHeaderCount(sum.total_items || 0);
  }

  async function addOne(pid){
    const d = await post('/cart/add', {product_id:pid});
    setQtyUI(d.qty||0);
    setHeaderCount(d.total_items||0);
  }
  async function decOne(pid){
    const d = await post('/cart/decrement', {product_id:pid});
    setQtyUI(d.qty||0);
    setHeaderCount(d.total_items||0);
  }

  // ===== Загрузка продукта в модалку =====
  async function loadProduct(url){
    nameEl.textContent=''; codeEl.textContent=''; priceEl.textContent=''; descEl.textContent='';
    bigBox.innerHTML='<img src="" alt="">'; smallBoxes.forEach(b=>{ b.innerHTML=''; b.style.display=''; });

    openModal(); showLoader(); disableCtrls(true);

    try{
      const res = await fetch(url, { headers:{ 'X-Requested-With':'XMLHttpRequest' }});
      if(!res.ok) throw new Error('Failed '+res.status);
      const p = await res.json();

      modal.setAttribute('data-pid', String(p.id));

      nameEl.textContent  = p.name || 'Product';
      codeEl.textContent  = (p.type ? `${p.type} — ` : '') + (p.code || '—');
      priceEl.textContent = formatPrice(p.price);
      descEl.textContent  = p.description || '—';

      const imgs = Array.isArray(p.images) ? p.images : [];
      const primary = p.primary && p.primary.url ? p.primary : (imgs[0] || null);

      if(primary){
        const a = document.createElement('a');
        a.href = primary.url;
        a.setAttribute('data-fancybox', `product-${p.id}`);
        a.setAttribute('data-caption', p.name || '');
        a.innerHTML = `<img src="${primary.url}" alt="${(p.name||'')}">`;
        bigBox.innerHTML = ''; bigBox.appendChild(a);
      }

      smallBoxes.forEach((box, i)=>{
        const im = imgs[i] || null;
        if(!im){ box.style.display='none'; box.innerHTML=''; return; }
        box.style.display='';
        box.innerHTML = `<a href="${im.url}" data-fancybox="product-${p.id}" data-caption="${(p.name||'')}">
                           <img src="${im.url}" alt="${(p.name||'')}">
                         </a>`;
      });

      // Подтянуть количество из корзины и разблокировать кнопки
      await syncQtyFromServer(p.id);
      disableCtrls(false);

    }catch(e){
      nameEl.textContent='Error';
      descEl.textContent='Could not load product details.';
      console.error(e);
    }finally{
      hideLoader();
    }
  }

  // Делегат для открытия модалки по клику на карточке
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-open-product');
    if(!btn) return;
    const card = btn.closest('.catalog__element');
    if(!card) return;
    const url = card.getAttribute('data-product');
    if(!url) return;
    e.preventDefault();
    loadProduct(url);
  });

  // Обработчики плюс/минус в модалке
  btnPlus.addEventListener('click', async (e)=>{
    e.preventDefault();
    const pid = modal.getAttribute('data-pid');
    if(!pid) return;
    try{ await addOne(pid); }catch(_){}
  });
  btnMinus.addEventListener('click', async (e)=>{
    e.preventDefault();
    const pid = modal.getAttribute('data-pid');
    if(!pid) return;
    try{ await decOne(pid); }catch(_){}
  });

})();
</script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush

