<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Http\Controllers\Controller;

class GuruApiController extends Controller
{
  public $kelasAktif = [];

  public function getJadwal(Request $request)
  {
    if ($request->hari) {
      $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
      foreach ($data as $d) {
        array_push($this->kelasAktif, $d->id);
      }
      $jadwal = JadwalPelajaran::where('guru_id', auth('sanctum')->user()->guru->id)->where('hari', $request->hari)->whereIn('kelas_id', $this->kelasAktif)->with(['kelas' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['mataPelajaran' => function ($query) {
        $query->select('id', 'nama');
      }])->select('hari', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->get();
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-mengajar' => $jadwal,
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari is required !',
      ]);
    }
  }
}
