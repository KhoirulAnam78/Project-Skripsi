<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

class SiswaController extends Controller
{
    public function index()
    {
        return view('pages.siswa', [
            'title' => 'Data Siswa'
        ]);
    }

    public function download()
    {
        $file = public_path() . '\assets\template-excel\Data Guru.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Guru.xlsx', $headers);
    }
}
