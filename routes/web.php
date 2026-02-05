<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to tracking search
Route::get('/', function () {
    return redirect()->route('track.index');
});

// Public Tracking Routes (Livewire Components)
Route::get('/track', App\Livewire\TrackingSearch::class)->name('track.index');
Route::get('/track/{orderCode}', App\Livewire\OrderTracking::class)->name('track.show');
Route::get('/track/{orderCode}/timeline', App\Livewire\OrderTimeline::class)->name('track.timeline');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
