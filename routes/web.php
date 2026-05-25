<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\B2bChatbotController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFinanceController;
use App\Http\Controllers\Admin\AdminB2bOrderController;
use App\Http\Controllers\Admin\AdminShippingRateController;
use App\Http\Controllers\B2b\B2bOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (!auth()->check()) {
        return view('welcome');
    }

    $user = auth()->user();

    // Admin bisa melihat halaman utama — akses dashboard via sidebar
    if ($user->hasRole('admin')) {
        return view('welcome');
    }

    if ($user->isB2b()) {
        return redirect()->route('b2b.dashboard');
    }

    return view('welcome');
})->name('home');



// Chatbot (public — no auth needed)
Route::post('/chatbot', [ChatbotController::class, 'reply'])->name('chatbot.reply');

// Chatbot B2B (khusus mitra login)
Route::post('/chatbot/b2b', [B2bChatbotController::class, 'reply'])
    ->middleware('auth')
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ])
    ->name('b2b.chatbot.reply');

// Midtrans Payment Notification Webhook (no auth, excluded from CSRF)
Route::post('/payment/notification', [CartController::class, 'notification'])
    ->name('payment.notification')
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);

// Lalamove Driver Status Webhook (no auth, excluded from CSRF)
Route::post('/lalamove/webhook', [CartController::class, 'lalamoveWebhook'])
    ->name('lalamove.webhook')
    ->withoutMiddleware([
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ]);
Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product:slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

use App\Http\Controllers\ContactMessageController;

Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

// Public Pages
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/contact', fn() => view('pages.contact'))->name('contact');
Route::get('/faq', fn() => view('pages.faq'))->name('faq');

// Cart public data (for navbar hover, no auth required so guest can see empty state)
Route::get('/cart/data', [CartController::class, 'cartData'])->name('cart.data');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/b2b', [ProfileController::class, 'updateB2b'])->name('profile.b2b.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ======= CART & CHECKOUT =======
    Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove',[CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/checkout',                  [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout',                  [CartController::class, 'placeOrder'])->name('checkout.place');
    Route::post('/checkout/shipping-quote',   [CartController::class, 'getShippingQuote'])->name('checkout.shipping.quote');
    Route::get('/checkout/finish',            [CartController::class, 'finishPayment'])->name('checkout.finish');

    // ======= ORDERS (Pesanan Saya) =======
    Route::get('/orders',                      [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}',            [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{orderId}/invoice',    [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{orderId}/confirm',   [\App\Http\Controllers\OrderController::class, 'confirmReceived'])->name('orders.confirm');
    Route::post('/orders/{orderId}/reviews',   [\App\Http\Controllers\ProductReviewController::class, 'store'])->name('orders.reviews.store');

    // Admin CMS Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products CRUD
        Route::resource('products', \App\Http\Controllers\Admin\AdminProductController::class);

        // Categories CRUD
        Route::resource('categories', \App\Http\Controllers\Admin\AdminCategoryController::class);

        // B2B Registrations Management
        Route::get('/b2b-registrations', [\App\Http\Controllers\Admin\B2bRegistrationController::class, 'index'])->name('b2b.index');
        Route::get('/b2b-registrations/{b2bRegistration}', [\App\Http\Controllers\Admin\B2bRegistrationController::class, 'show'])->name('b2b.show');
        Route::post('/b2b-registrations/{b2bRegistration}/approve', [\App\Http\Controllers\Admin\B2bRegistrationController::class, 'approve'])->name('b2b.approve');
        Route::post('/b2b-registrations/{b2bRegistration}/reject', [\App\Http\Controllers\Admin\B2bRegistrationController::class, 'reject'])->name('b2b.reject');
        Route::get('/b2b-registrations/{b2bRegistration}/mou', [\App\Http\Controllers\Admin\B2bRegistrationController::class, 'downloadMou'])->name('b2b.mou');

        // Order Management
        Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.status.update');

        // Finance Management
        Route::get('/finances', [AdminFinanceController::class, 'index'])->name('finances.index');
        Route::get('/finances/transactions', [AdminFinanceController::class, 'transactions'])->name('finances.transactions');
        Route::post('/finances/sync-midtrans', [AdminFinanceController::class, 'syncMidtrans'])->name('finances.sync');
        Route::get('/finances/export/csv', [AdminFinanceController::class, 'exportCsv'])->name('finances.export.csv');
        Route::get('/finances/export/pdf', [AdminFinanceController::class, 'exportPdf'])->name('finances.export.pdf');

        // Contact Messages Inbox
        Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::post('/contact-messages/{contactMessage}/replied', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markReplied'])->name('contact-messages.replied');
    });

    // B2B Portal Routes (Mitra)
    Route::prefix('b2b')->name('b2b.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\B2bController::class, 'dashboard'])->name('dashboard');

        // B2B Orders
        Route::get('/orders',                                   [B2bOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create',                            [B2bOrderController::class, 'create'])->name('orders.create');
        Route::post('/orders',                                  [B2bOrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{orderNumber}',                     [B2bOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{orderNumber}/invoice',             [B2bOrderController::class, 'downloadInvoice'])->name('orders.invoice');
        Route::post('/orders/{orderNumber}/upload-dp',          [B2bOrderController::class, 'uploadDp'])->name('orders.upload-dp');
        Route::post('/orders/{orderNumber}/upload-settlement',  [B2bOrderController::class, 'uploadSettlement'])->name('orders.upload-settlement');
        Route::post('/orders/{orderNumber}/inspect-goods',      [B2bOrderController::class, 'inspectGoods'])->name('orders.inspect-goods');
        Route::post('/orders/{orderNumber}/dispute',            [B2bOrderController::class, 'openDispute'])->name('orders.dispute');

        // B2B Product Detail (khusus stok & info B2B)
        Route::get('/products/{slug}', [\App\Http\Controllers\B2bController::class, 'productShow'])->name('products.show');

        // B2B Contact Admin
        Route::get('/contact', [\App\Http\Controllers\B2bContactController::class, 'index'])->name('contact.index');
        Route::post('/contact', [\App\Http\Controllers\B2bContactController::class, 'store'])->name('contact.store');
    });

    // Admin B2B Order Management
    Route::middleware(['admin'])->prefix('admin/b2b-orders')->name('admin.b2b.orders.')->group(function () {
        Route::get('/',                                     [AdminB2bOrderController::class, 'index'])->name('index');
        Route::get('/{orderNumber}',                        [AdminB2bOrderController::class, 'show'])->name('show');
        Route::post('/{orderNumber}/approve-review',         [AdminB2bOrderController::class, 'approveReview'])->name('approve-review');
        Route::get('/{orderNumber}/print-surat-jalan',          [AdminB2bOrderController::class, 'printSuratJalan'])->name('print-surat-jalan');
        Route::post('/{orderNumber}/verify-dp',             [AdminB2bOrderController::class, 'verifyDp'])->name('verify-dp');
        Route::post('/{orderNumber}/dispatch-armada',        [AdminB2bOrderController::class, 'dispatchArmada'])->name('dispatch-armada');
        Route::post('/{orderNumber}/mark-arrived',           [AdminB2bOrderController::class, 'markArrived'])->name('mark-arrived');
        Route::post('/{orderNumber}/verify-settlement',     [AdminB2bOrderController::class, 'verifySettlement'])->name('verify-settlement');
        Route::post('/{orderNumber}/set-damage-deduction',   [AdminB2bOrderController::class, 'setDamageDeduction'])->name('set-damage-deduction');
        Route::post('/{orderNumber}/upload-spb',            [AdminB2bOrderController::class, 'uploadSpb'])->name('upload-spb');
        Route::post('/{orderNumber}/dispute',               [AdminB2bOrderController::class, 'handleDispute'])->name('dispute');
        Route::post('/{orderNumber}/cancel',                [AdminB2bOrderController::class, 'cancel'])->name('cancel');
    });

    // Admin Shipping Rates Management
    Route::middleware(['admin'])->prefix('admin/shipping-rates')->name('admin.shipping-rates.')->group(function () {
        Route::get('/',             [AdminShippingRateController::class, 'index'])->name('index');
        Route::post('/',            [AdminShippingRateController::class, 'store'])->name('store');
        Route::put('/{rate}',       [AdminShippingRateController::class, 'update'])->name('update');
        Route::delete('/{rate}',    [AdminShippingRateController::class, 'destroy'])->name('destroy');
        Route::post('/calculate',   [AdminShippingRateController::class, 'calculate'])->name('calculate');
    });
});

// Driver Route (Signed URL)
Route::middleware(['signed'])->prefix('driver/order')->name('driver.order.')->group(function () {
    Route::get('/{orderNumber}/arrive', [\App\Http\Controllers\DriverOrderController::class, 'show'])->name('show');
    Route::post('/{orderNumber}/arrive', [\App\Http\Controllers\DriverOrderController::class, 'markArrived'])->name('mark-arrived');
});

require __DIR__.'/auth.php';
