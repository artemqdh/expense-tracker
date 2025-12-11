<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [ExpenseController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
    
    Route::get('/expenses/category/{category}', [ExpenseController::class, 'filterByCategory'])
        ->name('expenses.filter.category');
    
    Route::get('/expenses/month/{month}', [ExpenseController::class, 'filterByMonth'])
        ->name('expenses.filter.month');

    Route::get('/expenses/export/csv', [ExpenseController::class, 'export'])
        ->name('expenses.export.csv');
});

require __DIR__.'/auth.php';