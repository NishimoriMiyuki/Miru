<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () 
{
    return Auth::check() ? redirect()->route('pages.index') : view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ゴミ箱に入ってるページ復元
Route::patch('/pages/restoreSelected', [PageController::class, 'restoreSelected'])->name('pages.restoreSelected')->middleware(['auth', 'verified']);

// ゴミ箱を表示
Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed')->middleware(['auth', 'verified']);

// ゴミ箱の中身を永久削除
Route::delete('/pages/deleteAll', [PageController::class, 'deleteAll'])->name('pages.deleteAll')->middleware(['auth', 'verified']);

// ゴミ箱の中の選択されたページを永久削除
Route::delete('/pages/deleteSelected', [PageController::class, 'deleteSelected'])->name('pages.deleteSelected')->middleware(['auth', 'verified']);

Route::resource('pages', PageController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'])
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
