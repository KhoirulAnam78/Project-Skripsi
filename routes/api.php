<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GuruApiController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\WaliAsramaApiController;
use App\Http\Controllers\WaliAsramaController;

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
    //Logout
    Route::get('/logout', [AuthController::class, 'logout']);
    //Get Jadwal Guru
    Route::post('/get-jadwal', [GuruApiController::class, 'getJadwal']);
    //Get List Siswa Untuk Presensi
    Route::post('/get-siswa', [PresensiController::class, 'getSiswa']);
    //Input presensi
    Route::post('/presensi-pembelajaran', [PresensiController::class, 'presensiPembelajaran']);
    //get data yang perlu divalidasi
    Route::get('/validasi', [PresensiController::class, 'validasi']);
    //aksi validasi pembelajaran menjadi valid
    Route::post('/validasi/valid', [Presensicontroller::class, 'valid']);
    //aksi validasi menjadi tidak valid
    Route::post('/validasi/tidak-valid', [Presensicontroller::class, 'tidakValid']);
    //ambil jadwal siswa
    Route::post('/get-jadwal-siswa', [SiswaApiController::class, 'getJadwal']);
    Route::post('/get-jadwal-non-akademik', [SiswaApiController::class, 'getNonAkademik']);
    //ambil jadwal wali asrama
    Route::get('/get-jadwal-waliasrama', [WaliAsramaApiController::class, 'getJadwal']);
    Route::get('/get-kelas-angkatan', [WaliAsramaApiController::class, 'getKelas']);
    Route::post('/get-presensi-siswa/', [WaliAsramaApiController::class, 'getPresensiSiswa']);
    Route::post('/input-presensi-kegiatan', [WaliAsramaApiController::class, 'presensi']);
    //##AMBIL REKAP GURU##
    //ambil kelas aktif
    Route::get('/get-kelas', [GuruApiController::class, 'getKelas']);
    //ambil mata pelajaran berdasarkan kelas yang dipilih
    Route::get('/get-mapel/{kelas_id}', [GuruApiController::class, 'getMapel']);
    Route::get('/get-rekap-guru/{data}/{kelas_id}/{mapel_id}', [GuruApiController::class, 'getRekap']);
});
