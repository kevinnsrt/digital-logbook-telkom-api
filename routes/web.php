<?php

use App\Http\Controllers\DocumentsController;
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
    Route::get('/create', [DocumentsController::class, 'create'])->name('create');
    Route::get('/history', [DocumentsController::class, 'history'])->name('history');
    Route::post('/store', [DocumentsController::class, 'history'])->name('documents.store');
});

// add documents
 Route::post('/add', [DocumentsController::class, 'add'])->name('add');

require __DIR__.'/auth.php';
