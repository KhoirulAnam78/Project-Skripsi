<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SiswaController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        return view('pages.admin.siswa', [
            'title' => 'Data Siswa'
        ]);
    }

    public function download()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $file = public_path() . '/assets/template-excel/Data Siswa.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Siswa.xlsx', $headers);
    }

    public function jadwal()
    {
        $this->authorize('siswa');
        return view('pages.siswa.jadwal', [
            'title' => 'Jadwal Siswa'
        ]);
    }

    public function rekapPembelajaran()
    {
        $this->authorize('siswa');
        return view('pages.siswa.rekap-pembelajaran', [
            'title' => 'Rekap Pembelajaran Siswa'
        ]);
    }
}
