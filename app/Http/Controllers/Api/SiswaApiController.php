<?php

namespace App\Http\Controllers\Api;


use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiswaApiController extends Controller
{
  public function getJadwal(Request $request)
  {
    if ($request->hari) {
      $jadwal = Siswa::where('user_id', auth('sanctum')->user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
        $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
      }])->first();
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-siswa' => $jadwal->kelas->first()->jadwalPelajarans()->where('hari', $request->hari)->with('mataPelajaran')->with(['monitoringPembelajarans' => function ($query) {
          $query;
        }])->get(),
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari is required !',
      ]);
    }
  }
}
