<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {

    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect('/dashboard')
            : redirect('/kasir');
    }

    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {

    // ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('/products', ProductController::class);
    });

    // KASIR
    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir', [CashierController::class, 'index'])
            ->name('kasir');

        Route::post('/checkout', [CashierController::class, 'checkout']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
});

// SPK
Route::get('/spk', [SpkController::class, 'waspas'])->name('spk');

// EVENTS

Route::post('/set-event', function (Request $request) {
    session(['event_id' => $request->event_id]);
    return back();
});


Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
Route::resource('/events', EventController::class);

require __DIR__ . '/auth.php';
