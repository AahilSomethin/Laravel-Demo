<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;

Route::get('/', function () {
    return redirect()->route('store.index');
});

// Public store route (no authentication required)
Route::get('/store', [StoreController::class, 'index'])->name('store.index');

Route::get('/dashboard', function () {
    return redirect()->route('admin.products.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('products', ProductController::class);
    });

require __DIR__.'/auth.php';
