<?php

namespace App\Http\Controllers;

use App\Models\Narasumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\StoreNarasumberRequest;
use App\Http\Requests\UpdateNarasumberRequest;

class NarasumberController extends Controller
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
        return view('pages.admin.narasumber', [
            'title' => 'Data Narasumber'
        ]);
    }

    public function download()
    {
        if (Auth::user()->role === 'siswa') {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
        $file = public_path() . '/assets/template-excel/Data Narasumber.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );

        return Response::download($file, 'Template Import Data Narasumber.xlsx', $headers);
    }
}
