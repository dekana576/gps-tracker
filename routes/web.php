<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HistoryController;
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



Route::middleware(['auth', 'verified'])->group(function () {
    

        Route::middleware('is_admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin');
            Route::get('/admin/history', [HistoryController::class, 'index'])->name('history.index');
            Route::get('/admin/histories', [HistoryController::class, 'getHistories'])->name('admin.histories');
            Route::get('/admin/history/show/{id}', [HistoryController::class, 'show'])->name('history.show');
            Route::delete('/admin/history/delete/{id}', [HistoryController::class, 'destroy'])->name('history.destroy');
            // Tambahkan route lain untuk admin di sini
            Route::get('/admin/user', [AdminController::class, 'userindex'])->name('user.index');
            Route::get('admin/user-histories', [AdminController::class, 'Getuserindex'])->name('user.histories');
            // Route untuk halaman edit
            Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('user.edit');

            // Route untuk update data pengguna
            Route::put('/users/{id}', [AdminController::class, 'update'])->name('user.update');

            // Route untuk hapus data pengguna
            Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('user.destroy');

        });
        
        Route::middleware('is_not_admin')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
        });
});
