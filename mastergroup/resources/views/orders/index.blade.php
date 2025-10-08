@extends('layouts.app')
@section('title', $title ?? 'My Orders')
@section('page_title', 'My Orders')
@push('page-styles')
    <link rel="stylesheet"
        href="{{ asset('css/orders/style.css') }}?v={{ filemtime(public_path('css/market/catalog.css')) }}">
@endpush
@section('content')
    <div class="orders">
        <div class="orders__container">
            <div class="orders-card">
                <div class="orders-card__body">
                    <div class="table-scroll">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order</th>
                                    <th>Products</th>
                                    <th>Quantity</th>
                                    <th>CPS</th>
                                    <th>Date of order</th>
                                    <th>Date of execution</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>235</td>
                                    <td class="js-order-cell" data-order="#242332228">#242332228</td>
                                    <td>PS5</td>
                                    <td>1</td>
                                    <td>CPS 499</td>
                                    <td>08/20/22</td>
                                    <td>—</td>
                                    <td><span class="badge badge--ordered">ORDERED</span></td>
                                </tr>
                                <tr>
                                    <td>234</td>
                                    <td class="js-order-cell" data-order="#242325027">#242325027</td>
                                    <td>PS5</td>
                                    <td>1</td>
                                    <td>CPS 499</td>
                                    <td>08/20/22</td>
                                    <td>—</td>
                                    <td><span class="badge badge--ordered">ORDERED</span></td>
                                </tr>
                                <tr>
                                    <td>233</td>
                                    <td class="js-order-cell" data-order="#242332223">#242332223</td>
                                    <td>PS5</td>
                                    <td>3</td>
                                    <td>CPS 799</td>
                                    <td>08/19/22</td>
                                    <td>—</td>
                                    <td><span class="badge badge--ordered">ORDERED</span></td>
                                </tr>
                                <tr>
                                    <td>232</td>
                                    <td class="js-order-cell" data-order="#242332222">#242332222</td>
                                    <td>PS5</td>
                                    <td>1</td>
                                    <td>CPS 799</td>
                                    <td>08/19/22</td>
                                    <td>08/22/22</td>
                                    <td><span class="badge badge--completed">COMPLETED</span></td>
                                </tr>
                                <tr>
                                    <td>231</td>
                                    <td class="js-order-cell" data-order="#242332221">#242332221</td>
                                    <td>PS5</td>
                                    <td>5</td>
                                    <td>CPS 799</td>
                                    <td>08/18/22</td>
                                    <td>—</td>
                                    <td><span class="badge badge--processing">PROCESSING</span></td>
                                </tr>
                                <tr>
                                    <td>230</td>
                                    <td class="js-order-cell" data-order="#242332223">#242332220</td>
                                    <td>PS5</td>
                                    <td>2</td>
                                    <td>CPS 599</td>
                                    <td>08/18/22</td>
                                    <td>—</td>
                                    <td><span class="badge badge--cancelled">CANCELLED</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.table-scroll -->
                </div>
            </div>
        </div>
    </div>


    <!-- Popup -->
    <div class="modal" id="order-modal" aria-hidden="true">
        <div class="modal__overlay" data-close-modal></div>
        <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="order-modal-title">
            <button class="modal__close" type="button" aria-label="Close" data-close-modal>×</button>
            <div class="modal__body">
                <div class="order_popup__name">
                    <p>235</p>
                    <h3 id="order-modal-title" class="modal__title">Order </h3>
                    <span>ORDERED</span>
                </div>
                <div class="order_popup__desc">
                    <div class="order_popup-data">
                        <p>Order date:</p>
                        <span>21.01.2025</span>
                    </div>
                    <div class="order_popup-price">
                        Total price: <span>799</span>
                    </div>
                </div>
                <div class="order_popup-wrapper">
                    <!-------->
                    <div class="order_pup-element">
                        <div class="order_pup-element-image">
                            <img src="{{ 'images/catalog/catalog_placeholder.png' }}" alt="" srcset="">
                        </div>
                        <div class="order_pup-element-desc">
                            <div class="order_pup-element-namecode">
                                <div class="order_pup-element-name">
                                    Name of the product
                                </div>
                                <div class="order_pup-element-code">
                                    Black - UT894 X7
                                </div>
                            </div>
                            <div class="order_pup-element-price">
                                CPS 50 x 2
                            </div>
                        </div>
                    </div>
                    <!-------->
                    <!-------->
                    <div class="order_pup-element">
                        <div class="order_pup-element-image">
                            <img src="{{ 'images/catalog/catalog_placeholder2.png' }}" alt="" srcset="">
                        </div>
                        <div class="order_pup-element-desc">
                            <div class="order_pup-element-namecode">
                                <div class="order_pup-element-name">
                                    Name of the product
                                </div>
                                <div class="order_pup-element-code">
                                    Black - UT894 X7
                                </div>
                            </div>
                            <div class="order_pup-element-price">
                                CPS 50 x 2
                            </div>
                        </div>
                    </div>
                    <!-------->

                </div>

            </div>
        </div>
    </div>



    <script>
        (function() {
            const modal = document.getElementById('order-modal');
            const dialog = modal.querySelector('.modal__dialog');
            const title = modal.querySelector('#order-modal-title');

            function openModal(orderNumber) {
                modal.classList.add('is-open');
                document.body.classList.add('body--modal-open');
                if (orderNumber) title.textContent = 'Order ' + orderNumber;
                // фокус на крестик
                const closeBtn = modal.querySelector('.modal__close');
                closeBtn && closeBtn.focus();
            }

            function closeModal() {
                modal.classList.remove('is-open');
                document.body.classList.remove('body--modal-open');
            }

            // Клики по ячейкам "Order"
            document.addEventListener('click', function(e) {
                const cell = e.target.closest('.js-order-cell');
                if (!cell) return;
                const orderNumber = cell.dataset.order || cell.textContent.trim();
                openModal(orderNumber);
            });

            // Закрытие по overlay и крестику
            modal.addEventListener('click', function(e) {
                if (e.target.matches('[data-close-modal]')) closeModal();
            });

            // Закрытие по Esc
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });

            // Клик вне диалога (но внутри overlay) — тоже закрываем
            modal.addEventListener('mousedown', function(e) {
                if (e.target === modal.querySelector('.modal__overlay')) closeModal();
            });
        })();
    </script>


@endsection
