<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\Kelas;
use App\Exports\ExportSiswa;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Exports\RekapGuruExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DaftarPertemuanExport;
use Illuminate\Support\Facades\Response;

class GuruApiController extends Controller
{
  public $kelasAktif = [];
  public $tanggal;

  public function login(Request $request)
  {
    User::where('id', auth('sanctum')->user()->id)->update(['role' => $request->role]);
    return response()->json([
      'message' => 'Update data succes',
      'status_guru' => $request->role
    ]);
  }

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

  public function getKelas()
  {
    return response()->json([
      'message' => 'Fetch data succes',
      'kelas' => Kelas::whereRelation('tahunAkademik', 'status', 'aktif')->select('id', 'nama')->get()
    ]);
  }

  public function getMapel($kelas_id)
  {
    $arrayMapel = [];
    $jadwal = JadwalPelajaran::where('guru_id', auth('sanctum')->user()->guru->id)->where('kelas_id', $kelas_id)->with(['mataPelajaran' => function ($query) {
      $query->select('id');
    }])->select('id', 'guru_id', 'mata_pelajaran_id')->get();
    //Ambil Id Mata Pelajaran dari setiap jadwal
    foreach ($jadwal as $d) {
      array_push($arrayMapel, $d->mataPelajaran->id);
    }
    $mapel = MataPelajaran::whereIn('id', $arrayMapel)->select('id', 'nama')->get();
    return response()->json([
      'message' => 'Fetch data succes',
      'mapel' => $mapel
    ]);
  }

  public function getRekap($data, $kelas_id, $mapel_id)
  {
    // return response()->json([
    //   'message' => 'Fetch data failed',
    //   'jenisRekap' => $data,
    //   'bulan' => $bulan
    // ]);
    if ($data === 'Daftar Pertemuan') {
      $namaKelas = Kelas::find($kelas_id)->nama;
      $namaMapel = MataPelajaran::find($mapel_id)->nama;
      $jml_siswa = Kelas::select('id')->find($kelas_id)->siswas->count();
      return Excel::download(new DaftarPertemuanExport($kelas_id, $mapel_id, $jml_siswa), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . '.xlsx');
    } else {
      $kelasAktif = [];
      $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
      foreach ($data as $d) {
        array_push($kelasAktif, $d->id);
      }
      return Excel::download(new RekapGuruExport($kelasAktif, $kelas_id, $mapel_id), 'Rekap Guru ' . 'Tanggal ' . $kelas_id . ' Sampai ' . $mapel_id . '.xlsx');
    }
  }
}
