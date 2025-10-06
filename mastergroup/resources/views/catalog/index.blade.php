@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')

@push('page-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog_add.css') }}?v={{ filemtime(public_path('css/market/catalog_add.css')) }}">
@endpush


@section('content')
    @php use Illuminate\Support\Str; @endphp

    <div class="catalog__wrapper">

        <div class="catalog__filter">
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>
            <div class="catalog_filter__element"></div>

            <div class="catalog_filter__element">
                <img src="{{ asset('images/catalog/search.svg') }}" alt="">
                <input type="text" placeholder="Search" disabled>
            </div>

            <div class="catalog_filter__clear">
                <button disabled>clear</button>
            </div>
        </div>

        <div class="catalog__content">
            @forelse($products as $p)
                @php
                    $photoPath = optional($p->primaryImage)->path ?? optional($p->images->first())->path;
                    if ($photoPath) {
                        $img = Str::startsWith($photoPath, ['http://', 'https://'])
                            ? $photoPath
                            : asset('storage/' . ltrim($photoPath, '/'));
                    } else {
                        $img = asset('images/catalog/catalog_placeholder.png');
                    }
                    $detailUrl = route('catalog.api.product', $p); // JSON endpoint
                @endphp

                <!----element------>
                <div class="catalog__element" data-product="{{ $detailUrl }}" data-name="{{ e($p->name) }}">
                    <div class="catalog__element__wrapper">

                        <div class="catalog__element__bin" title="Remove from cart"
                            style="opacity:.4; pointer-events:none;">
                            <img src="{{ asset('images/catalog/bin.svg') }}" alt="">
                        </div>

                        <div class="catalog__element__image js-open-product" style="cursor:pointer">
                            <img src="{{ $img }}" alt="{{ $p->name }}">
                        </div>

                        <div class="catalog__element__name">
                            <p class="js-open-product" style="cursor:pointer">{{ $p->name }}</p>
                        </div>

                        <div class="catalog__element__type">
                            {{ $p->type ? $p->type . ' — ' : '' }}{{ $p->code }}
                        </div>

                        <div class="catalog__element__amount-price">
                            <div class="catalog__element-amount">
                                <button disabled><img src="{{ asset('images/catalog/minus.svg') }}"
                                        alt=""></button>
                                <span>0</span>
                                <button disabled><img src="{{ asset('images/catalog/plus.svg') }}" alt=""></button>
                            </div>
                            <div class="catalog__element-price">
                                {{ number_format((float) $p->price, 2, '.', ' ') }} AZN
                            </div>
                        </div>
                    </div>
                </div>
                <!----element------>
            @empty
                <div style="padding:20px; color:#97a2b6">No products found.</div>
            @endforelse
        </div>

        {{-- пагинация --}}
        <div style="margin-top:16px">
            {{ $products->links() }}
        </div>
    </div>




    {{-- POPUP --}}
    <div id="productModal" class="pm-backdrop" hidden>
        <div class="pm-dialog">
            <button class="pm-close" type="button" aria-label="Close">&times;</button>

<!-- LOADER: перекрывает popop__container во время загрузки -->
<div class="popup__loader" hidden aria-live="polite" aria-busy="true">
  <div class="spinner">
    <div class="ring"></div>
    <div class="txt">Loading…</div>
  </div>
</div>


            <div class="popup__container">
                <div class="popup__images">
                    <!---main image of product----->
                    <div class="popup__images-big">
                        <div class="popup__image">
                            <img src="" alt="">
                        </div>
                    </div>
                    <!---additional images of product----->
                    <div class="popup__images-small">
                        <div class="popup__image"></div>
                        <div class="popup__image"></div>
                        <div class="popup__image"></div>
                        <div class="popup__image"></div>
                    </div>
                </div>

                <div class="popup__content">
                    <div class="popup__name">
                        Name of the product
                        Name of the product
                    </div>
                    <div class="popup__code">
                        Black - UT894 X7
                    </div>
                    <div class="popup__price-amount">
                        <div class="popup__price">
                            CPS 48.00
                        </div>
                        <div class="popup__amount">
                            <div class="catalog__element-amount">
                                <button disabled><img src="{{ asset('images/catalog/minus.svg') }}"
                                        alt=""></button>
                                <span>0</span>
                                <button disabled><img src="{{ asset('images/catalog/plus.svg') }}" alt=""></button>
                            </div>
                        </div>
                    </div>

                    <div class="popup__desc">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quasi tenetur repellendus, pariatur
                        incidunt, facilis adipisci nam dolores distinctio sunt, quas in quis nostrum vitae necessitatibus
                        ipsum debitis! Aspernatur, quae provident!
                    </div>

                </div>
            </div>

        </div>
    </div>


   

@push('page-scripts')
<script>
(function(){
  const modal = document.getElementById('productModal');

  // твои элементы
  const bigBox = document.querySelector('.popup__images-big .popup__image');
  const smallBoxes = document.querySelectorAll('.popup__images-small .popup__image');

  const nameEl = document.querySelector('.popup__name');
  const codeEl = document.querySelector('.popup__code');
  const priceEl = document.querySelector('.popup__price');
  const descEl  = document.querySelector('.popup__desc');

  // НОВОЕ: ссылки на контейнер и лоадер
  const container = document.querySelector('.popup__container');
  const loader    = document.querySelector('.popup__loader');

  function openModal(){ modal.hidden = false; document.body.style.overflow = 'hidden'; }
  function closeModal(){
    modal.hidden = true; document.body.style.overflow = '';
    // очистка визуала
    bigBox.innerHTML = '<img src="" alt="">';
    smallBoxes.forEach(b => { b.innerHTML=''; b.style.display=''; });
    // гарантированно спрячем лоадер и покажем контейнер на следующий открытие
    hideLoader();
  }

  function showLoader(){
    // прячем серые блоки целиком
    container.style.display = 'none';
    loader.hidden = false;
  }
  function hideLoader(){
    loader.hidden = true;
    container.style.display = 'flex'; // вернуть твой display
  }

  modal.querySelector('.pm-close').addEventListener('click', closeModal);
  modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(); });
  document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) closeModal(); });

  function formatPrice(n){
    n = Number(n||0);
    return `CPS ${n.toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2})}`;
  }

  async function loadProduct(url){
    // текстовые поля пока пустые (не показываем — лоадер снизу)
    nameEl.textContent = ''; codeEl.textContent = '';
    priceEl.textContent = ''; descEl.textContent = '';
    bigBox.innerHTML = '<img src="" alt="">';
    smallBoxes.forEach(b => { b.innerHTML=''; b.style.display=''; });

    openModal();
    showLoader();

    try{
      const res = await fetch(url, { headers:{ 'X-Requested-With':'XMLHttpRequest' } });
      if(!res.ok) throw new Error('Failed '+res.status);
      const p = await res.json();

      // Заполняем контент
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
        bigBox.innerHTML = '';
        bigBox.appendChild(a);
      }else{
        bigBox.innerHTML = '<img src="" alt="">';
      }

      smallBoxes.forEach((box, i) => {
        const im = imgs[i] || null;
        if(!im){ box.style.display='none'; box.innerHTML=''; return; }
        box.style.display='';
        box.innerHTML =
          `<a href="${im.url}" data-fancybox="product-${p.id}" data-caption="${(p.name||'')}">
             <img src="${im.url}" alt="${(p.name||'')}">
           </a>`;
      });

    }catch(err){
      // простая ошибка
      nameEl.textContent = 'Error';
      descEl.textContent = 'Could not load product details.';
      console.error(err);
    }finally{
      // ПРЯЧЕМ ЛОАДЕР И ПОКАЗЫВАЕМ КОНТЕНТ
      hideLoader();
    }
  }

  // клики по карточкам
  document.querySelectorAll('.catalog__element').forEach(card=>{
    const url = card.getAttribute('data-product');
    if(!url) return;
    card.querySelectorAll('.js-open-product').forEach(el=>{
      el.addEventListener('click', ()=> loadProduct(url));
    });
  });

})();
</script>
<!-- Fancybox -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endpush



@endsection
