<?php

namespace App\Http\Controllers;

use App\Models\WaliAsrama;
use App\Models\JadwalKegiatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\StoreWaliAsramaRequest;
use App\Http\Requests\UpdateWaliAsramaRequest;

class WaliAsramaController extends Controller
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
        return view('pages.admin.wali-asrama', [
            'title' => 'Data Wali Asrama'
        ]);
    }

    public function download()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $file = public_path() . '/assets/template-excel/Data Wali Asrama.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Wali Asrama.xlsx', $headers);
    }

    public function jadwal()
    {
        $angkatan = WaliAsrama::where('user_id', Auth::user()->id)->first()->angkatans->where('status', 'belum lulus')->first();
        $jadwal = JadwalKegiatan::where('angkatan_id', $angkatan->id)->with('kegiatan')->get();
        // dd($angkatan);
        return view('pages.wali_asrama.jadwal', [
            'title' => 'Jadwal Kegiatan',
            'angkatan' => $angkatan->nama,
            'jadwal' => $jadwal
        ]);
    }
}
