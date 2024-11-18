<?php

use App\Http\Controllers\AdminController;
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
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\UserController;

Route::post('/save-history', [TrackingController::class, 'saveHistory']);
Route::get('/history', [TrackingController::class, 'index'])->name('history.index');
Route::get('/history/{id}', [TrackingController::class, 'show'])->name('history.show');
Route::delete('/history/{id}', [TrackingController::class, 'destroy'])->name('history.destroy');

Route::middleware(['auth', 'verified'])->group(function () {
    // Hanya admin yang dapat mengakses route ini
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin')
        ->middleware('is_admin');

    // Hanya user biasa yang dapat mengakses route ini
    Route::get('/', [UserController::class, 'index'])
        ->name('user')
        ->middleware('is_not_admin');
});
