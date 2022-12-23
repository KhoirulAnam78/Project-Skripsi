<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
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
        return view('pages.tahun_akademik', [
            'title' => 'Tahun Akademik'
        ]);
    }
}
