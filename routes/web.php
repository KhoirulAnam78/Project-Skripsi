<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalGuruPiketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard', [
        'title' => 'Dashboard'
    ]);
})->middleware('auth');

//AUTH
Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::get('/logout', function () {
    Auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->middleware('auth');

//ROUTE DATA MASTER GURU
Route::get('/data-guru', [GuruController::class, 'index'])->middleware('auth');
