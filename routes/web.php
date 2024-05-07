<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\PageIndex;
use App\Livewire\PageTrashed;
use App\Livewire\BoardIndex;
use App\Livewire\BoardEditor;
use App\Livewire\BoardTrashed;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        if(DB::connection()->getDatabaseName()){
            echo "Successfully connected to the DB: " . DB::connection()->getDatabaseName();
        }else{
            die("Could not find the database. Please check your configuration.");
        }
    } catch (\Exception $e) {
        die("Could not open connection to database server. Error: " . $e->getMessage());
    }
});

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
Route::get('/boards/trashed', BoardTrashed::class)->name('boards.trashed')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
