<?php

namespace App\Http\Controllers;

use App\Models\JadwalGuruPiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\StoreJadwalGuruPiketRequest;
use App\Http\Requests\UpdateJadwalGuruPiketRequest;

class JadwalGuruPiketController extends Controller
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
        return view('pages.admin.jadwal_guru_piket', [
            'title' => 'Jadwal Guru Piket'
        ]);
    }
    public function download()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $file = public_path() . '/assets/template-excel/Data Jadwal Piket Guru.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Jadwal Piket Guru.xlsx', $headers);
    }
}
