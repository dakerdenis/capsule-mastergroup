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
            <form method="GET" action="{{ route('catalog.index') }}" style="display:contents">
                {{-- 1. сортировка --}}
                <div class="catalog_filter__element">
                    <select name="sort" class="select js-custom-select">
                        <option value="new" @selected(($sort ?? '') === 'new')>Date: New → Old</option>
                        <option value="old" @selected(($sort ?? '') === 'old')>Date: Old → New</option>
                        <option value="price_asc" @selected(($sort ?? '') === 'price_asc')>Price: Low → High</option>
                        <option value="price_desc" @selected(($sort ?? '') === 'price_desc')>Price: High → Low</option>
                    </select>
                </div>

                {{-- 2. категория --}}
                <div class="catalog_filter__element">
                    <select name="category_id" class="select js-custom-select">
                        <option value="">All categories</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(($catId ?? null) == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. тип --}}
                <div class="catalog_filter__element">
                    <select name="type" class="select js-custom-select">
                        <option value="">All types</option>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" @selected(($type ?? '') === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- поиск --}}
                <div class="catalog_filter__element catalog_filter__element-search">
                    <img src="{{ asset('images/catalog/search.svg') }}" alt="">
                    <input type="text" name="q" value="{{ $q ?? '' }}"
                        placeholder="Search by name, code, slug">
                </div>

                {{-- кнопка применения / можно авто-submit через JS --}}
                @php
                    $hasFilters =
                        ($q ?? '') !== '' || ($catId ?? null) || ($type ?? '') !== '' || ($sort ?? 'new') !== 'new';
                @endphp

                <div class="catalog_filter__clear" style="display:flex; gap:12px;">
                    <button class="btn-apply" type="submit">Search</button>

                    <a href="{{ route('catalog.index') }}" class="btn-clear"
                        @unless ($hasFilters) style="opacity:.6; pointer-events:none" @endunless>
                        Clear
                    </a>
                </div>


            </form>
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
                <div class="catalog__element" data-product-id="{{ $p->id }}" data-product="{{ $detailUrl }}"
                    data-name="{{ e($p->name) }}">
                    <div class="catalog__element__wrapper">

                        <div class="catalog__element__bin" title="Remove from cart">
                            <button class="btn-remove" type="button">
                                <img src="{{ asset('images/catalog/bin.svg') }}" alt="">
                            </button>
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
                                <button class="btn-minus" type="button"><img src="{{ asset('images/catalog/minus.svg') }}"
                                        alt=""></button>
                                <span class="qty">0</span>
                                <button class="btn-plus" type="button"><img src="{{ asset('images/catalog/plus.svg') }}"
                                        alt=""></button>
                            </div>
                            <div class="catalog__element-price">
                                {{ number_format((float) $p->price, 0, '.', ' ') }} CPS
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
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    </div>









    @push('page-scripts')
        <!-- Fancybox -->
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
        <script>
            (function() {
                document.querySelectorAll('select.js-custom-select').forEach(function(sel) {
                    // обёртка
                    const wrap = document.createElement('div');
                    wrap.className = 'cs';
                    sel.parentElement.appendChild(wrap);
                    wrap.appendChild(sel); // переносим селект внутрь

                    // кнопка-триггер
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'cs-trigger';
                    btn.setAttribute('aria-haspopup', 'listbox');
                    btn.setAttribute('aria-expanded', 'false');
                    btn.innerHTML = `<span class="cs-label"></span>
      <svg class="cs-arrow" viewBox="0 0 24 24" fill="none">
        <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>`;
                    wrap.appendChild(btn);

                    // меню
                    const menu = document.createElement('div');
                    menu.className = 'cs-menu';
                    menu.setAttribute('role', 'listbox');
                    wrap.appendChild(menu);

                    const label = btn.querySelector('.cs-label');

                    // заполнение пунктов
                    Array.from(sel.options).forEach(opt => {
                        const it = document.createElement('button');
                        it.type = 'button';
                        it.className = 'cs-item';
                        it.textContent = opt.text;
                        it.dataset.value = opt.value;

                        if (opt.selected) {
                            it.classList.add('is-selected');
                            label.textContent = opt.text;
                        }
                        it.addEventListener('click', () => {
                            // выбрать
                            Array.from(menu.children).forEach(x => x.classList.remove(
                                'is-selected'));
                            it.classList.add('is-selected');
                            sel.value = opt.value;
                            label.textContent = opt.text;

                            // закрыть и триггернуть change (автосабмит, если нужно)
                            menu.classList.remove('is-open');
                            wrap.classList.remove('is-open');
                            btn.setAttribute('aria-expanded', 'false');
                            sel.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        });
                        menu.appendChild(it);
                    });

                    // если в селекте не было selected — ставим первый
                    if (!label.textContent) label.textContent = sel.options[sel.selectedIndex]?.text || sel.options[
                        0]?.text || '';

                    // открыть/закрыть
                    btn.addEventListener('click', () => {
                        const open = !menu.classList.contains('is-open');
                        document.querySelectorAll('.cs-menu.is-open').forEach(m => {
                            m.classList.remove('is-open');
                            m.parentElement.classList.remove('is-open');
                        });
                        if (open) {
                            menu.classList.add('is-open');
                            wrap.classList.add('is-open');
                            btn.setAttribute('aria-expanded', 'true');
                        } else {
                            btn.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // клик вне — закрыть
                    document.addEventListener('click', (e) => {
                        if (!wrap.contains(e.target)) {
                            menu.classList.remove('is-open');
                            wrap.classList.remove('is-open');
                            btn.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // клавиатура: Esc закрыть
                    wrap.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            menu.classList.remove('is-open');
                            wrap.classList.remove('is-open');
                            btn.setAttribute('aria-expanded', 'false');
                            btn.focus();
                        }
                    });

                    // автосабмит при изменении (как раньше)
                    sel.addEventListener('change', () => {
                        sel.form && sel.form.submit();
                    });
                });
            })();
        </script>
    @endpush


    @include('partials.product_modal')
@endsection
