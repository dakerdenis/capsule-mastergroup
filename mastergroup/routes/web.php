<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
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

// Админ-логин: 5 попыток/час по IP и по паре email+IP
RateLimiter::for('admin-login', function (Request $request) {
    $ip = $request->ip();
    $email = (string) $request->input('email');

    return [
        Limit::perHour(5)->by('ip:'.sha1($ip)),
        Limit::perHour(5)->by('combo:'.sha1(strtolower($email).'|'.$ip)),
    ];
});

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('account.dashboard')
        : redirect()->route('auth.login');
})->name('home');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit')->middleware('throttle:login'); // лимит
    Route::get('/register', [RegisterController::class, 'choose'])->name('auth.register');
    Route::get('/register/user', [RegisterController::class, 'showUser'])->name('auth.register.user');
    Route::get('/register/company', [RegisterController::class, 'showCompany'])->name('auth.register.company');
    Route::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');

    Route::get('/password/forgot', [PasswordController::class, 'forgot'])->name('password.forgot');
    Route::post('/password/email', [PasswordController::class, 'sendLink'])->name('password.email')->middleware('throttle:password-email');
    Route::get('/password/reset/{token}', [PasswordController::class, 'reset'])->name('password.reset');
    Route::post('/password/reset', [PasswordController::class, 'update'])->name('password.update');
});

// Защищённые разделы (требуют login и verified email)
Route::middleware(['auth'])->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/account', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Админ-маршруты: отдельный guard и middleware
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:admin-login');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
