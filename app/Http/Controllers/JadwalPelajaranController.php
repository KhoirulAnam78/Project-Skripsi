<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreJadwalPelajaranRequest;
use App\Http\Requests\UpdateJadwalPelajaranRequest;

class JadwalPelajaranController extends Controller
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
        return view('pages.admin.jadwal_pelajaran', [
            'title' => 'Jadwal Pelajaran'
        ]);
    }

    public function jadwalPengganti()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $this->authorize('adpim');
        return view('pages.admin.jadwal_pengganti', [
            'title' => 'Jadwal Pengganti'
        ]);
    }
    public function download()
    {
        $file = public_path() . '/assets/template-excel/Data Jadwal Pelajaran.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Jadwal Pelajaran.xlsx', $headers);
    }
}
