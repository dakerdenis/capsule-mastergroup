<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
//Admin controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;



// ====== Гость ======
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit')->middleware('throttle:login');

    Route::get('/register', [RegisterController::class, 'choose'])->name('auth.register');
    Route::get('/register/user', [RegisterController::class, 'showUser'])->name('auth.register.user');
    Route::get('/register/company', [RegisterController::class, 'showCompany'])->name('auth.register.company');
    Route::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');

    // <<< ТОЛЬКО ЭТИ ДВЕ СТРОКИ ДЛЯ СБРОСА >>>
    Route::get('/password/forgot', [PasswordController::class, 'forgot'])->name('password.forgot');
    Route::post('/password/generate', [PasswordController::class, 'generateNew'])->name('password.generate')->middleware('throttle:password-email');

});

// ====== Авторизованные ======
Route::middleware('auth')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('home');
    Route::get('/account', [AccountController::class, 'account'])->name('account');
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    // AJAX: Product details (JSON)
    Route::get('/catalog/api/products/{product}', [CatalogController::class, 'showJson'])
        ->name('catalog.api.product');
    // CART PAGES + AJAX
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::get('/cart/items', [CartController::class, 'items'])->name('cart.items');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/decrement', [CartController::class, 'decrement'])->name('cart.decrement');
    Route::post('/cart/set', [CartController::class, 'setQuantity'])->name('cart.set');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/select', [CartController::class, 'select'])->name('cart.select');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}/json', [OrderController::class, 'showJson'])
        ->name('orders.show.json')
        ->whereNumber('order');

    Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');


    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});


// Админ-логин: 5 попыток/час по IP и по паре email+IP
RateLimiter::for('admin-login', function (Request $request) {
    $ip = $request->ip();
    $email = (string) $request->input('email');

    return [
        Limit::perHour(5)->by('ip:' . sha1($ip)),
        Limit::perHour(5)->by('combo:' . sha1(strtolower($email) . '|' . $ip)),
    ];
});

// Админ-маршруты: отдельный guard и middlewareЫ
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit')->middleware('throttle:admin-login');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');

        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');

        // PRODUCTS
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');


        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
