<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/save-history', [TrackingController::class, 'saveHistory']);
Route::get('/history', [TrackingController::class, 'index'])->name('history.index');
Route::get('/history/{id}', [TrackingController::class, 'show'])->name('history.show');
Route::delete('/history/{id}', [TrackingController::class, 'destroy'])->name('history.destroy');
