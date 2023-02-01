<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;


class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.kelas', [
            'title' => 'Data Kelas'
        ]);
    }
    public function download()
    {
        $file = public_path() . '/assets/template-excel/Data Kelas.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Kelas.xlsx', $headers);
    }
}
