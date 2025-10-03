@extends('layouts.app')
@section('title', $title ?? 'Catalogue')
@section('page_title', 'Catalogue')

@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/market/catalog_add.css') }}?v={{ filemtime(public_path('css/market/catalog_add.css')) }}">

    <style>
        .pm-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            backdrop-filter: blur(2px);
        }

        /* ← ДОБАВЬ ЭТО */
        .pm-backdrop[hidden] {
            display: none !important;
        }

        .pm-dialog {
            width: min(980px, 92vw);
            background: #0f1115;
            color: #e9eefb;
            border: 1px solid #252b3a;
            border-radius: 14px;
            box-shadow: 0 18px 60px rgba(0, 0, 0, .5);
            position: relative;
            overflow: hidden;
        }

        .pm-close {
            position: absolute;
            right: 10px;
            top: 10px;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid #252b3a;
            background: #141825;
            color: #e9eefb;
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
        }

        .pm-body {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 16px;
            padding: 16px
        }

        @media (max-width: 900px) {
            .pm-body {
                grid-template-columns: 1fr
            }
        }

        .pm-photo {
            border: 1px solid #252b3a;
            border-radius: 12px;
            background: #0b0e14;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 4/3;
            overflow: hidden
        }

        .pm-photo img {
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .pm-thumbs {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap
        }

        .pm-thumb {
            width: 84px;
            height: 64px;
            border-radius: 8px;
            border: 1px solid #252b3a;
            overflow: hidden;
            cursor: pointer
        }

        .pm-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .pm-thumb.is-active {
            outline: 2px solid rgba(91, 140, 255, .7)
        }

        .pm-right h3 {
            margin: 0 0 6px;
            font-size: 20px
        }

        .pm-meta {
            display: grid;
            gap: 6px;
            margin-bottom: 10px;
            color: #97a2b6
        }

        .pm-price {
            font-size: 20px;
            font-weight: 800;
            margin: 8px 0 10px
        }

        .pm-desc {
            white-space: pre-wrap;
            color: #cfd6e6;
            line-height: 1.5;
            max-height: 220px;
            overflow: auto;
            border: 1px solid #252b3a;
            border-radius: 10px;
            padding: 10px;
            background: #121521
        }

        .muted {
            color: #97a2b6
        }
    </style>
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

            <div class="pm-body">
                <div class="pm-left">
                    <div class="pm-photo">
                        <img id="pm-main" src="" alt="">
                    </div>
                    <div class="pm-thumbs" id="pm-thumbs"></div>
                </div>

                <div class="pm-right">
                    <h3 id="pm-title">Loading…</h3>
                    <div class="pm-meta">
                        <div><span class="muted">Code:</span> <span id="pm-code"></span></div>
                        <div><span class="muted">Type:</span> <span id="pm-type"></span></div>
                        <div><span class="muted">Category:</span> <span id="pm-category"></span></div>
                    </div>

                    <div class="pm-price" id="pm-price"></div>
                    <div class="pm-desc" id="pm-desc"></div>
                </div>
            </div>
        </div>
    </div>



    @push('page-scripts')
        <script>
            (function() {
                const modal = document.getElementById('productModal');
                const main = document.getElementById('pm-main');
                const thumbs = document.getElementById('pm-thumbs');

                const title = document.getElementById('pm-title');
                const code = document.getElementById('pm-code');
                const type = document.getElementById('pm-type');
                const cat = document.getElementById('pm-category');
                const price = document.getElementById('pm-price');
                const desc = document.getElementById('pm-desc');

                function openModal() {
                    modal.hidden = false;
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    modal.hidden = true;
                    document.body.style.overflow = '';
                    main.src = '';
                    thumbs.innerHTML = '';
                }

                modal.querySelector('.pm-close').addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModal();
                });
document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape' && !modal.hidden) closeModal(); });
                function formatPrice(n) {
                    n = Number(n || 0);
                    return n.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' AZN';
                }

                async function loadProduct(url) {
                    // простая индикация загрузки
                    title.textContent = 'Loading...';
                    code.textContent = type.textContent = cat.textContent = '';
                    price.textContent = '';
                    desc.textContent = '';
                    main.src = '';
                    thumbs.innerHTML = '';

                    openModal();
                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!res.ok) throw new Error('Failed: ' + res.status);
                        const p = await res.json();

                        title.textContent = p.name || 'Product';
                        code.textContent = p.code || '—';
                        type.textContent = p.type || '—';
                        cat.textContent = p.category || '—';
                        price.textContent = formatPrice(p.price);
                        desc.textContent = p.description || '—';

                        // картинки
                        const imgs = Array.isArray(p.images) ? p.images : [];
                        const primary = p.primary;
                        let current = primary?.url || (imgs[0]?.url) || '';

                        if (current) {
                            main.src = current;
                        }

                        imgs.forEach((im) => {
                            const a = document.createElement('button');
                            a.type = 'button';
                            a.className = 'pm-thumb' + (im.url === current ? ' is-active' : '');
                            a.innerHTML = `<img src="${im.url}" alt="">`;
                            a.addEventListener('click', () => {
                                main.src = im.url;
                                thumbs.querySelectorAll('.pm-thumb').forEach(t => t.classList.remove(
                                    'is-active'));
                                a.classList.add('is-active');
                            });
                            thumbs.appendChild(a);
                        });
                    } catch (e) {
                        title.textContent = 'Error';
                        desc.textContent = 'Could not load product details.';
                        console.error(e);
                    }
                }

                // Вешаем клики на карточки
                document.querySelectorAll('.catalog__element').forEach(card => {
                    const url = card.getAttribute('data-product');
                    if (!url) return;
                    card.querySelectorAll('.js-open-product').forEach(el => {
                        el.addEventListener('click', () => loadProduct(url));
                    });
                });

            })();
        </script>
    @endpush

@endsection
