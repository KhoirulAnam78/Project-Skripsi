<?php

namespace App\Http\Controllers\Api;


use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;

class SiswaApiController extends Controller
{
  public $jadwal;
  public function getJadwal(Request $request)
  {
    if ($request->hari and $request->tanggal) {
      $this->jadwal = Siswa::where('user_id', auth('sanctum')->user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
        $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
      }])->first();

      $jadwalPengganti = JadwalPengganti::where('tanggal', $request->tanggal)->with(['jadwalPelajaran' => function ($query) {
        // $query->with()->get();
        $query->where('kelas_id', $this->jadwal->kelas->first()->id)->with(['kelas' => function ($query) {
          $query->select('id', 'nama');
        }])->with(['mataPelajaran' => function ($query) {
          $query->select('id', 'nama');
        }])->get();
      }])->get();

      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-siswa' => $this->jadwal->kelas->first()->jadwalPelajarans()->where('hari', $request->hari)->with('mataPelajaran', 'guru')->with(['monitoringPembelajarans' => function ($query) {
          if ($query) {
            $query->with(['kehadiranPembelajarans' => function ($query) {
              $query->where('siswa_id', auth('sanctum')->user()->siswa->id);
            }]);
          };
        }])->get(),
        'jadwal-pengganti' => $jadwalPengganti
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari is required !',
      ]);
    }
  }
}
