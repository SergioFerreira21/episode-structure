<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BlockController;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('episodes.index');
});

Route::resource('episodes', EpisodeController::class);
Route::resource('parts', PartController::class);
Route::resource('items', ItemController::class);
Route::resource('blocks', BlockController::class);

Route::post('/fn/duplicate', [GeneralController::class, 'duplicate'])->name('duplicate');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
