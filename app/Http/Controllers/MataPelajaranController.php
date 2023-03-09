<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMataPelajaranRequest;
use App\Http\Requests\UpdateMataPelajaranRequest;

class MataPelajaranController extends Controller
{
  public function index()
  {
    if (Auth::user()->role === 'siswa') {
      return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
    }
    $this->authorize('adpim');
    return view('pages.admin.mapel', [
      'title' => 'Mata Pelajaran'
    ]);
  }
}
