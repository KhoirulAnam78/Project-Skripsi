<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('admin');
        return view('pages.admin.guru', [
            'title' => 'Data Guru'
        ]);
    }

    public function download()
    {
        $file = public_path() . '/assets/template-excel/Data Guru.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Guru.xlsx', $headers);
    }

    public function jadwal()
    {
        return view('pages.guru.jadwal_mengajar', [
            'title' => 'Jadwal Mengajar'
        ]);
    }
}
