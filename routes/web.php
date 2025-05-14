<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\PurchaseController; 

// Authentication Routes
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth.session'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');

    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // Calculator
    Route::get('/calculator', [CalculatorController::class, 'index'])->name('calculator.index');
    Route::post('/calculator/calculate', [CalculatorController::class, 'calculate'])->name('calculator.calculate');
});

// Custom Auth Session Middleware
Route::middleware([\App\Http\Middleware\CheckSessionAuth::class])->group(function () {
    // Your protected routes here
});
Route::post('/products/{product}/purchase', [PurchaseController::class, 'store'])
    ->name('products.purchase');


// routes/web.php (additional)
// Route::post('/set-theme', function(Request $request) {
//     session(['theme' => $request->theme]);
//     return response()->json(['success' => true]);
// });

// Route::get('/check-notifications', function() {
//     $hasNewNotifications = \App\Models\Notification::where('read', false)->exists();
//     return response()->json(['hasNewNotifications' => $hasNewNotifications]);
// });
