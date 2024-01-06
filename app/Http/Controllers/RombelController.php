<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class RombelController extends Controller
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
        return view('pages.admin.rombel', [
            'title' => 'Rombongan Belajar'
        ]);
    }


    public function download()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $file = public_path() . '/assets/template-excel/Data Mapping Rombel.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Rombel.xlsx', $headers);
    }
}
