<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardRowController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
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
Route::delete('pages/{page}/force_delete', [PageController::class, 'forceDelete'])->name('pages.force_delete')->middleware(['auth', 'verified']);
Route::get('/pages/trashed', [PageController::class, 'trashed'])->name('pages.trashed')->middleware(['auth', 'verified']);
Route::post('pages/{page}/restore', [PageController::class, 'restore'])->name('pages.restore')->middleware(['auth', 'verified']);

Route::resource('pages', PageController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy', 'edit', 'show'])
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
Route::get('/board_rows/create/{board}', [BoardRowController::class, 'create'])->name('board_rows.create')->middleware(['auth', 'verified']);

Route::resource('board_rows', BoardRowController::class)
    ->only(['store', 'update', 'destroy', 'edit'])
    ->middleware(['auth', 'verified']);
    
# questionsルート
Route::post('/questions/store', [QuestionController::class, 'store'])->name('questions.store')->middleware(['auth', 'verified']);
Route::put('/questions/update', [QuestionController::class, 'update'])->name('questions.update')->middleware(['auth', 'verified']);
Route::delete('/questions/destroy', [QuestionController::class, 'destroy'])->name('questions.update')->middleware(['auth', 'verified']);

# commentsルート
Route::post('/comments/store', [CommentController::class, 'store'])->name('comments.store')->middleware(['auth', 'verified']);
Route::delete('/comments/destroy', [CommentController::class, 'destroy'])->name('comments.update')->middleware(['auth', 'verified']);

#tagsルート
Route::post('/tags/store', [TagController::class, 'store'])->name('tags.store')->middleware(['auth', 'verified']);
Route::post('/tags/attachTagToBoardRow', [TagController::class, 'attachTagToBoardRow'])->name('tags.attachTagToBoardRow')->middleware(['auth', 'verified']);
Route::delete('/tags/detachTagFromBoardRow', [TagController::class, 'detachTagFromBoardRow'])->name('tags.detachTagFromBoardRow')->middleware(['auth', 'verified']);
Route::delete('/tags/delete', [TagController::class, 'delete'])->name('tags.delete')->middleware(['auth', 'verified']);

#postsルート
Route::resource('posts', PostController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
