<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardRowController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Livewire\PageIndex;
use App\Livewire\PageTrashed;
use App\Livewire\BoardIndex;
use App\Livewire\BoardEditor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () 
{
    return Auth::check() ? redirect()->route('pages.index') : view('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

# Pagesルート
Route::get('/pages', PageIndex::class)->name('pages.index')->middleware(['auth', 'verified']);
Route::get('/pages/trashed', PageTrashed::class)->name('pages.trashed')->middleware(['auth', 'verified']);
    
# boardsルート
Route::get('/boards', BoardIndex::class)->name('boards.index')->middleware(['auth', 'verified']);
Route::get('/boards/{board}/edit', BoardEditor::class)->name('boards.edit')->middleware(['auth', 'verified']);

// Route::delete('boards/{board}/force_delete', [BoardController::class, 'forceDelete'])->name('boards.force_delete')->middleware(['auth', 'verified']);
// Route::get('/boards/trashed', [BoardController::class, 'trashed'])->name('boards.trashed')->middleware(['auth', 'verified']);
// Route::post('/boards/{board}/restore', [BoardController::class, 'restore'])->name('boards.restore')->middleware(['auth', 'verified']);

// Route::resource('boards', BoardController::class)
//     ->only(['create', 'store', 'update', 'destroy', 'show', 'edit'])
//     ->middleware(['auth', 'verified']);

# board_rowsルート
Route::get('/board_rows/{board}/store', [BoardRowController::class, 'store'])->name('board_rows.store')->middleware(['auth', 'verified']);

Route::resource('board_rows', BoardRowController::class)
    ->only(['update', 'destroy', 'edit'])
    ->middleware(['auth', 'verified']);
    
# questionsルート
Route::resource('questions', QuestionController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

# commentsルート
Route::resource('comments', CommentController::class)
    ->only(['store', 'destroy'])
    ->middleware(['auth', 'verified']);

#tagsルート
Route::post('/tags/store', [TagController::class, 'store'])->name('tags.store')->middleware(['auth', 'verified']);
Route::post('/tags/attachTagToBoardRow', [TagController::class, 'attachTagToBoardRow'])->name('tags.attachTagToBoardRow')->middleware(['auth', 'verified']);
Route::delete('/tags/detachTagFromBoardRow', [TagController::class, 'detachTagFromBoardRow'])->name('tags.detachTagFromBoardRow')->middleware(['auth', 'verified']);
Route::delete('/tags/delete', [TagController::class, 'delete'])->name('tags.delete')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
