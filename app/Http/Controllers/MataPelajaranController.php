<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
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
  public function download()
  {
    if (Auth::user()->role === 'siswa') {
      return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
    }
    $file = public_path() . '/assets/template-excel/Data Mapel.xlsx';
    $headers = array(
      'Content-Type: application/xlsx',
    );

    return Response::download($file, 'Template Import Data Mapel.xlsx', $headers);
  }
}
