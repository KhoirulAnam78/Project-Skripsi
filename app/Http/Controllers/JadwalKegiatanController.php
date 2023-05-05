<?php

namespace App\Http\Controllers;

use App\Models\JadwalKegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\StoreJadwalKegiatanRequest;
use App\Http\Requests\UpdateJadwalKegiatanRequest;

class JadwalKegiatanController extends Controller
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
        $this->authorize('adpim');
        return view('pages.admin.jadwal_kegiatan', [
            'title' => 'Jadwal Kegiatan'
        ]);
    }

    public function download()
    {
        $file = public_path() . '/assets/template-excel/Data Jadwal Kegiatan.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Jadwal Kegiatan.xlsx', $headers);
    }
}
