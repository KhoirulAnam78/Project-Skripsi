<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuruApiController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\WaliAsramaApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-rekap-guru/{data}/{bulan}', [GuruApiController::class, 'getRekap']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/get-user', [AuthController::class, 'getUser']);
    Route::post('/get-jadwal', [GuruApiController::class, 'getJadwal']);
    Route::post('/get-siswa', [PresensiController::class, 'getSiswa']);
    Route::post('/presensi-pembelajaran', [PresensiController::class, 'presensiPembelajaran']);
    Route::get('/validasi', [PresensiController::class, 'validasi']);
    Route::post('/validasi/valid', [Presensicontroller::class, 'valid']);
    Route::post('/validasi/tidak-valid', [Presensicontroller::class, 'tidakValid']);
    Route::post('/get-jadwal-siswa', [SiswaApiController::class, 'getJadwal']);
    Route::post('/get-jadwal-non-akademik', [SiswaApiController::class, 'getNonAkademik']);
    Route::get('/get-jadwal-waliasrama', [WaliAsramaApiController::class, 'getJadwal']);
});
