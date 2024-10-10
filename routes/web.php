<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StepController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterventionController;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/interventions/code', [InterventionController::class, 'showByCode'])
    ->name('interventions.showByCode');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('interventions', InterventionController::class)
        ->only(['index', 'create', 'store', 'edit', 'destroy']);

    Route::post('/steps/{step}/status', [StepController::class, 'updateStatus']);
});


