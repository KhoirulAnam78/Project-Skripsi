<?php

namespace App\Http\Controllers\Api;


use App\Exports\ExportSiswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class GuruApiController extends Controller
{
  public $kelasAktif = [];
  public $tanggal;

  public function getJadwal(Request $request)
  {
    $this->tanggal = $request->tanggal;
    if ($request->hari && $request->tanggal) {
      $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
      foreach ($data as $d) {
        array_push($this->kelasAktif, $d->id);
      }
      $jadwal = JadwalPelajaran::where('guru_id', auth('sanctum')->user()->guru->id)->where('hari', $request->hari)->whereIn('kelas_id', $this->kelasAktif)->with(['kelas' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['mataPelajaran' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['monitoringPembelajarans' => function ($query) {
        if ($query) {
          $query->where('tanggal', $this->tanggal);
        };
      }])->select('id', 'hari', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'mata_pelajaran_id')->get();

      $jadwalPengganti = JadwalPengganti::where('tanggal', $request->tanggal)->whereRelation('jadwalPelajaran', 'guru_id', auth('sanctum')->user()->guru->id)->with(['jadwalPelajaran' => function ($query) {
        // $query->with()->get();
        $query->with(['kelas' => function ($query) {
          $query->select('id', 'nama');
        }])->with(['mataPelajaran' => function ($query) {
          $query->select('id', 'nama');
        }])->with(['monitoringPembelajarans' => function ($query) {
          if ($query) {
            $query->where('tanggal', $this->tanggal);
          };
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


  public function getRekap($data)
  {
    if ($data === 'daftar-pertemuan') {
      return Excel::download(new ExportSiswa, 'Data Siswa SMAN Titian Teras.xlsx');
    } else {
      return Excel::download(new ExportSiswa, 'Data Siswa SMAN Titian Teras.xlsx');
    }
  }
}
