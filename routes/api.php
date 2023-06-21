<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WaliAsramaController;
use App\Http\Controllers\Api\GuruApiController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\SiswaApiController;
use App\Http\Controllers\Api\PimpinanApiController;
use App\Http\Controllers\Api\WaliAsramaApiController;
use App\Http\Controllers\Api\RekapitulasiApiController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //Update Role Pimpinan/Guru
    Route::post('/status-pimpinan', [GuruApiController::class, 'updateRole']);

    //Ubah Password
    Route::post('/ubah-password', [AuthController::class, 'changePassword']);

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
    Route::post('/input-presensi-kegiatan-narasumber', [WaliAsramaApiController::class, 'presensiNarasumber']);
    Route::get('/get-narasumber', [WaliAsramaApiController::class, 'getNarasumber']);

    #PIMPINAN
    //Dashboard pimpinan
    //get data pembelajaran selama satu minggu
    Route::post('/get-persentase', [PimpinanApiController::class, 'getPersentase']);
    Route::post('/get-persentase-non-akademik', [PimpinanApiController::class, 'getNonAkademik']);
    Route::get('/get-kelas-monitoring', [PimpinanApiController::class, 'getKelas']);
    Route::post('/detail-monitoring', [PimpinanApiController::class, 'getDetail']);
    Route::post('/get-kelas-monitoring-non-akademik', [PimpinanApiController::class, 'getKelasNonAkademik']);
    Route::post('/detail-monitoring-non-akademik', [PimpinanApiController::class, 'getDetailNonAkademik']);

    #REKAPITULASI
    //ambil kelas aktif
    Route::get('/get-kelas', [RekapitulasiApiController::class, 'getKelas']);
    //Get Kegiatan
    Route::get('/get-kegiatan', [RekapitulasiApiController::class, 'getKegiatan']);
    //Get Angkatan
    Route::get('/get-angkatan', [RekapitulasiApiController::class, 'getAngkatan']);
    //Get Rekap Daftar Pertemuan dan Keterlaksanaan
    //Keterlaksanaan Pembelajaran
    Route::get('/get-keterlaksanaan-guru/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'getKeterlaksanaanGuru']);
    //Daftar Pertemuan Pembelajaran
    Route::get('/get-daftar-pertemuan-guru/{kelas_id}/{mapel_id}/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'getDaftarPertemuanGuru']);
    //ambil mata pelajaran berdasarkan kelas yang dipilih
    Route::get('/get-mapel/{kelas_id}', [GuruApiController::class, 'getMapel']);
    //Kehadiran Pembelajaran
    Route::get('/get-rekap-kehadiran-pembelajaran/{kelas_id}/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'getRekapKehadiranPembelajaran']);

    //Rekap Daftar Pertemuan Kegiatan
    Route::get('/get-daftar-kegiatan/{kegiatan_id}/{angkatan_id}/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'getDaftarKegiatan']);
    //Rekap Kehadiran Kegiatan
    Route::get('/get-rekap-kehadiran-kegiatan/{kegiatan_id}/{kelas_id}/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'getRekapKehadiranKegiatan']);

    #REKAP WALI MURID
    Route::get('/get-rekap-pembelajaran-siswa/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'rekapPembelajaranSiswa']);

    Route::get('/get-rekap-kegiatan-siswa/{kegiatan_id}/{tanggalAwal}/{tanggalAkhir}', [RekapitulasiApiController::class, 'rekapKegiatanSiswa']);
});
