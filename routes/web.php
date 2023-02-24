<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\JadwalGuruPiketController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MonitoringPembelajaranController;
use App\Http\Controllers\RombelController;
use App\Models\MonitoringPembelajaran;

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

Route::get('/dashboard', function () {
    return view('dashboard', [
        'title' => 'Dashboard'
    ]);
})->middleware('auth');

Route::get('/', function () {
    return view('welcome', [
        'title' => 'Halaman Awal'
    ]);
});
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

//ROUTE DATA MASTER
//GURU
Route::get('/data-guru', [GuruController::class, 'index'])->middleware('auth');
Route::get('/download-template-guru', [GuruController::class, 'download'])->middleware('auth');
//TAHUN AKADEMIK
Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->middleware('auth');
//Data Kelas
Route::get('/kelas', [KelasController::class, 'index'])->middleware('auth');
Route::get('/download-template-kelas', [KelasController::class, 'download'])->middleware('auth');
//Data Siswa
Route::get('/siswa', [SiswaController::class, 'index'])->middleware('auth');
Route::get('/download-template-siswa', [SiswaController::class, 'download'])->middleware('auth');
//DATA ROMBEL
Route::get('/rombongan-belajar', [RombelController::class, 'index'])->middleware('auth');

//DATA MAPEL
Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->middleware('auth');

//Jadwal Piket Guru
Route::get('/jadwal-guru-piket', [JadwalGuruPiketController::class, 'index'])->middleware('auth');
Route::get('/download-template-jadwal-guru-piket', [JadwalGuruPiketController::class, 'download'])->middleware('auth');

//Jadwal Pelajaran
Route::get('/jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->middleware('auth');
Route::get('/download-template-jadwal-pelajaran', [JadwalPelajaranController::class, 'download'])->middleware('auth');

//PRESENSI PEMBELAJARAN
Route::get('/presensi-pembelajaran', [MonitoringPembelajaranController::class, 'index'])->middleware('auth');

//Daftar Pertemuan
Route::get('/daftar-pertemuan', [MonitoringPembelajaranController::class, 'daftarPertemuan'])->middleware('auth');

//Jadwal Pengganti
Route::get('/jadwal-pengganti', [JadwalPelajaranController::class, 'jadwalPengganti'])->middleware('auth');

//Validasi Pembelajaran
Route::get('/validasi-pembelajaran', [MonitoringPembelajaranController::class, 'validasi'])->middleware('auth');

//Rekapitulasi Pembelajaran
Route::get('/rekapitulasi-siswa', [MonitoringPembelajaranController::class, 'rekapSiswa'])->middleware('auth');
Route::get('/rekapitulasi-guru', [MonitoringPembelajaranController::class, 'rekapGuru'])->middleware('auth');
