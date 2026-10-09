<?php
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AdminOnly;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Serves uploaded pictures even when `php artisan storage:link` was not run
// (for example when Windows will not create the symlink). If the link exists, the web server serves the file itself.
Route::get('/storage/{path}', function (string $path) {
    abort_if(str_contains($path, '..') || ! Storage::disk('public')->exists($path), 404);
    return Storage::disk('public')->response($path);
})->where('path', '.*');

Route::get('/accessories', [AccessoryController::class, 'index'])->name('accessories');
Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Auth (built in, no Breeze needed)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Sends each role to its own landing page after login
Route::get('/dashboard', fn () => auth()->user()->role === 'admin'
    ? redirect()->route('admin.dashboard') : redirect()->route('home'))->middleware('auth')->name('dashboard');

// Signed-in customers (and admins)
Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'mine'])->name('bookings.mine');
    Route::post('/phones/{phone}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/phones/{phone}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{phone}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin only
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminOnly::class])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('suppliers', Admin\SupplierController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('coupons', Admin\CouponController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('accessories', Admin\AccessoryController::class)->except(['show']);
    Route::resource('banners', Admin\BannerController::class)->except(['show']);
    Route::resource('purchases', Admin\PurchaseController::class)->only(['index', 'create', 'store']);
    Route::get('stock', [Admin\StockController::class, 'index'])->name('stock');
    Route::resource('phones', Admin\StockController::class)->except(['index', 'show']);
    Route::get('movements', fn () => view('admin.movements', ['rows' => StockMovement::with(['phone', 'user'])->latest()->latest('id')->paginate(20)]))->name('movements');
    Route::get('bookings', [Admin\DailyBookingController::class, 'index'])->name('bookings');
    Route::patch('bookings/{booking}', [Admin\DailyBookingController::class, 'update'])->name('bookings.update');
    Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments');
    Route::post('payments', [Admin\PaymentController::class, 'store'])->name('payments.store');
    Route::patch('payments/{payment}/refund', [Admin\PaymentController::class, 'refund'])->name('payments.refund');
    Route::get('messages', [Admin\MessageController::class, 'index'])->name('messages');
    Route::patch('messages/{message}/read', [Admin\MessageController::class, 'read'])->name('messages.read');
    Route::delete('messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports');
});
