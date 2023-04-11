<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;

class GuruApiController extends Controller
{
  public $kelasAktif = [];

  public function getJadwal(Request $request)
  {
    if ($request->hari && $request->tanggal) {
      $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
      foreach ($data as $d) {
        array_push($this->kelasAktif, $d->id);
      }
      $jadwal = JadwalPelajaran::where('guru_id', auth('sanctum')->user()->guru->id)->where('hari', $request->hari)->whereIn('kelas_id', $this->kelasAktif)->with(['kelas' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['mataPelajaran' => function ($query) {
        $query->select('id', 'nama');
      }])->select('id', 'hari', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->get();

      $jadwalPengganti = JadwalPengganti::where('tanggal', $request->tanggal)->whereRelation('jadwalPelajaran', 'guru_id', auth('sanctum')->user()->guru->id)->with(['jadwalPelajaran' => function ($query) {
        // $query->with()->get();
        $query->with(['kelas' => function ($query) {
          $query->select('id', 'nama');
        }])->with(['mataPelajaran' => function ($query) {
          $query->select('id', 'nama');
        }])->get();
      }])->get();

      return response()->json([
        'message' => 'Fetch data success',
        'tanggal' => $request->tanggal,
        'jadwal-mengajar' => $jadwal,
        'jadwal-pengganti' => $jadwalPengganti
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari and tanggal is required !',
      ]);
    }
  }
}
