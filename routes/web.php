<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Models\Rice;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $ricemenu = Rice::all();
    $orders   = Order::where('user_id', auth()->id())
                     ->with('items.rice', 'payment')
                     ->latest()
                     ->take(8)
                     ->get();
    return view('dashboard', compact('ricemenu', 'orders'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // Rice menu management
    Route::get('/rice',         [RiceController::class, 'index'])->name('rice.index');
    Route::post('/rice',        [RiceController::class, 'store'])->name('rice.store');
    Route::patch('/rice/{id}',  [RiceController::class, 'update'])->name('rice.update');
    Route::delete('/rice/{id}', [RiceController::class, 'destroy'])->name('rice.destroy');

    // Orders
    Route::post('/orders',      [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}',  [OrderController::class, 'show'])->name('orders.show');

    // Order items
    Route::post('/order-items',         [OrderItemController::class, 'store'])->name('order-items.store');
    Route::patch('/order-items/{id}',   [OrderItemController::class, 'update'])->name('order-items.update');
    Route::delete('/order-items/{id}',  [OrderItemController::class, 'destroy'])->name('order-items.destroy');

    // Payments
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';