<?php

namespace App\Http\Controllers;

class MonitoringPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.admin.presensi_pembelajaran', [
            'title' => 'Presensi Pembelajaran',
        ]);
    }

    public function daftarPertemuan()
    {
        return view('pages.admin.daftar_pertemuan', [
            'title' => 'Daftar Pertemuan'
        ]);
    }

    public function validasi()
    {
        return view('pages.admin.validasi_pembelajaran', [
            'title' => 'Validasi Pembelajaran'
        ]);
    }

    public function rekapSiswa()
    {
        return view('pages.admin.rekapitulasi_siswa', [
            'title' => 'Rekapitulasi Siswa'
        ]);
    }
    public function rekapGuru()
    {
        return view('pages.admin.rekapitulasi_guru', [
            'title' => 'Rekapitulasi Guru'
        ]);
    }
}
