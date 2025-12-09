<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Expense Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::get('/expenses/export/csv', [\App\Http\Controllers\ExpenseController::class, 'export'])
        ->name('expenses.export.csv');
});

require __DIR__.'/auth.php';
