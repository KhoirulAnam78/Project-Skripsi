<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAngkatanRequest;
use App\Http\Requests\UpdateAngkatanRequest;

class AngkatanController extends Controller
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
        return view('pages.admin.angkatan', [
            'title' => 'Data Angkatan'
        ]);
    }
}
