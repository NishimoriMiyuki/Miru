<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardRowController;
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

# Pagesルート

// ゴミ箱に入ってるページ復元
Route::patch('/pages/restore_selected', [PageController::class, 'restoreSelected'])->name('pages.restore_selected')->middleware(['auth', 'verified']);

// ゴミ箱を表示
Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed')->middleware(['auth', 'verified']);

// ゴミ箱の中身を永久削除
Route::delete('/pages/delete_all', [PageController::class, 'deleteAll'])->name('pages.delete_all')->middleware(['auth', 'verified']);

// ゴミ箱の中の選択されたページを永久削除
Route::delete('/pages/delete_selected', [PageController::class, 'deleteSelected'])->name('pages.delete_selected')->middleware(['auth', 'verified']);

Route::resource('pages', PageController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy', 'show'])
    ->middleware(['auth', 'verified']);
    
# boardsルート
Route::patch('/boards/restore_selected', [BoardController::class, 'restoreSelected'])->name('boards.restore_selected')->middleware(['auth', 'verified']);
Route::get('/boards/trashed', [BoardController::class, 'trashed'])->name('boards.trashed')->middleware(['auth', 'verified']);
Route::delete('/boards/delete_all', [BoardController::class, 'deleteAll'])->name('boards.delete_all')->middleware(['auth', 'verified']);
Route::delete('/boards/delete_selected', [BoardController::class, 'deleteSelected'])->name('boards.delete_selected')->middleware(['auth', 'verified']);

Route::resource('boards', BoardController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy', 'show'])
    ->middleware(['auth', 'verified']);

# board_rowsルート
Route::resource('board_rows', BoardRowController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
