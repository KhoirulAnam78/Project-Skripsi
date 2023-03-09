<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTahunAkademikRequest;
use App\Http\Requests\UpdateTahunAkademikRequest;

class TahunAkademikController extends Controller
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
        return view('pages.admin.tahun_akademik', [
            'title' => 'Tahun Akademik'
        ]);
    }
}
