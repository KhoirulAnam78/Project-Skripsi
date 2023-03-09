<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRombelRequest;
use App\Http\Requests\UpdateRombelRequest;

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
}
