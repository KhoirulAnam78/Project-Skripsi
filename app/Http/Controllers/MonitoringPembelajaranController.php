<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MonitoringPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.presensi_pembelajaran', [
            'title' => 'Presensi Pembelajaran',
        ]);
    }

    public function daftarPertemuan()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.daftar_pertemuan', [
            'title' => 'Daftar Pembelajaran'
        ]);
    }

    public function validasi()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.validasi_pembelajaran', [
            'title' => 'Validasi Pembelajaran'
        ]);
    }

    public function belumTervalidasi()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.belum_validasi_pembelajaran', [
            'title' => 'Pembelajaran Belum Divalidasi'
        ]);
    }

    public function rekapSiswa()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.rekapitulasi_siswa', [
            'title' => 'Rekapitulasi Siswa'
        ]);
    }
    public function rekapGuru()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.rekapitulasi_guru', [
            'title' => 'Rekapitulasi Guru'
        ]);
    }
}