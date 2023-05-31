<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use App\Models\Angkatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Exports\RekapGuruExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;

class PimpinanApiController extends Controller
{
  public function getKegiatan()
  {
    return response()->json([
      'message' => 'Fetch data succes',
      'kegiatan' => Kegiatan::select('id', 'nama')->get()
    ]);
  }


  public function getAngkatan()
  {
    return response()->json([
      'message' => 'Fetch data succes',
      'kegiatan' => Angkatan::where('status', 'belum lulus')->get()
    ]);
  }

  public function getKelas()
  {
    return response()->json([
      'message' => 'Fetch data succes',
      'kelas' => Kelas::whereRelation('tahunAkademik', 'status', 'aktif')->select('id', 'nama')->get()
    ]);
  }

  public function getRekapGuru($data, $kelas_id, $mapel_id)
  {
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
