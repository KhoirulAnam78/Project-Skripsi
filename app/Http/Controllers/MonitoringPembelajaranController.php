<?php

namespace App\Http\Controllers;

use App\Models\MonitoringPembelajaran;
use App\Http\Requests\StoreMonitoringPembelajaranRequest;
use App\Http\Requests\UpdateMonitoringPembelajaranRequest;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;

class MonitoringPembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.presensi_pembelajaran', [
            'title' => 'Presensi Pembelajaran',
        ]);
    }

    public function daftarPertemuan()
    {
        return view('pages.daftar_pertemuan', [
            'title' => 'Daftar Pertemuan'
        ]);
    }

    public function validasi()
    {
        return view('pages.validasi_pembelajaran', [
            'title' => 'Validasi Pembelajaran'
        ]);
    }

    public function rekapSiswa()
    {
        return view('pages.rekapitulasi_siswa', [
            'title' => 'Rekapitulasi Siswa'
        ]);
    }
}
