<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\MonitoringKegiatan;
use App\Http\Requests\StoreMonitoringKegiatanRequest;
use App\Http\Requests\UpdateMonitoringKegiatanRequest;

class MonitoringKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function daftarKegiatan($slug)
    {
        $this->authorize('adwalpim');
        $kegiatan = Kegiatan::where('slug', $slug)->first();
        if ($kegiatan->narasumber == true) {
            return view('pages.admin.daftar_kegiatan_nara', [
                'title' => 'Daftar Kegiatan ' . $kegiatan->nama,
                'kegiatan' => $kegiatan
            ]);
        } else {
            return view('pages.admin.daftar_kegiatan_tanpanara', [
                'title' => 'Daftar Kegiatan ' . $kegiatan->nama,
                'kegiatan' => $kegiatan
            ]);
        }
    }
}
