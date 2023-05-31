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
use App\Exports\RekapSiswaExport;
use App\Exports\RekapKegnasExport;
use App\Exports\DaftarKegnasExport;
use App\Exports\RekapKegiatanExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DaftarKegiatanExport;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Exports\DaftarPertemuanGuruExport;

class RekapitulasiApiController extends Controller
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
      'angkatan' => Angkatan::where('status', 'belum lulus')->get()
    ]);
  }

  public function getKelas()
  {
    return response()->json([
      'message' => 'Fetch data succes',
      'kelas' => Kelas::whereRelation('tahunAkademik', 'status', 'aktif')->select('id', 'nama')->get()
    ]);
  }

  public function getDaftarPertemuanGuru($kelas_id, $mapel_id, $tanggalAwal, $tanggalAkhir)
  {
    $namaKelas = Kelas::find($kelas_id)->nama;
    $namaMapel = MataPelajaran::find($mapel_id)->nama;
    $jml_siswa = Kelas::select('id')->find($kelas_id)->siswas->count();
    return Excel::download(new DaftarPertemuanGuruExport($kelas_id, $mapel_id, $jml_siswa, $tanggalAwal, $tanggalAkhir), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . ' Tanggal ' . $tanggalAwal . ' - ' . $tanggalAkhir . '.xlsx');
  }

  public function getKeterlaksanaanGuru($tanggalAwal, $tanggalAkhir)
  {
    $kelasAktif = [];
    $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
    foreach ($data as $d) {
      array_push($kelasAktif, $d->id);
    }
    return Excel::download(new RekapGuruExport($kelasAktif, $tanggalAwal, $tanggalAkhir), 'Rekap Guru Tanggal ' . $tanggalAwal . ' Sampai ' . $tanggalAkhir . '.xlsx');
  }

  public function getRekapKehadiranPembelajaran($kelas_id, $tanggalAwal, $tanggalAkhir)
  {
    $namaKelas = Kelas::select('nama')->where('id', $kelas_id)->first()->nama;
    return Excel::download(new RekapSiswaExport($kelas_id, $tanggalAwal, $tanggalAkhir), 'Rekap Kehadiran Siswa ' . $namaKelas . ' Tanggal ' . $tanggalAwal . ' Sampai ' . $tanggalAkhir . '.xlsx');
  }

  public function getDaftarKegiatan($kegiatan_id, $angkatan_id, $tanggalAwal, $tanggalAkhir)
  {
    $kegiatan = Kegiatan::find($kegiatan_id);
    $namaKegiatan = $kegiatan->nama;
    $angkatan = Angkatan::find($angkatan_id)->nama;
    $tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
    $jml_siswa = 0;
    $kelas = Kelas::where('angkatan_id', $angkatan_id)->where('tahun_akademik_id', $tahunAkademik)->select('id')->get();
    foreach ($kelas as $k) {
      $jml_siswa = $jml_siswa + $k->siswas->count();
    }
    if ($kegiatan->narasumber == 1) {
      return Excel::download(new DaftarKegnasExport($angkatan_id, $jml_siswa, $tanggalAwal, $tanggalAkhir, $kegiatan_id, $tahunAkademik), 'Daftar Pertemuan Kegiatan ' . $namaKegiatan . ' Angkatan ' . $angkatan . ' ' . $tanggalAwal . ' sampai ' . $tanggalAkhir . '.xlsx');
    } else {
      return Excel::download(new DaftarKegiatanExport($angkatan_id, $jml_siswa, $tanggalAwal, $tanggalAkhir, $kegiatan->id, $tahunAkademik), 'Daftar Pertemuan Kegiatan ' . $namaKegiatan . ' Angkatan ' . $angkatan . ' ' . $tanggalAwal . ' sampai ' . $tanggalAkhir . '.xlsx');
    }
  }

  public function getRekapKehadiranKegiatan($kegiatan_id, $kelas_id, $tanggalAwal, $tanggalAkhir)
  {
    $kegiatan = Kegiatan::find($kegiatan_id);
    $namaKelas = Kelas::select('nama')->where('id', $kelas_id)->first()->nama;

    $tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
    $namaKegiatan = $kegiatan->nama;
    if ($kegiatan->narasumber == 1) {
      return Excel::download(new RekapKegnasExport($kelas_id, $tanggalAwal, $tanggalAkhir, $kegiatan_id, $tahunAkademik), 'Rekap Kehadiran ' . $namaKegiatan . ' ' . $namaKelas . ' ' . $tanggalAwal . ' - ' . $tanggalAkhir . '.xlsx');
    } else {
      return Excel::download(new RekapKegiatanExport($kelas_id, $tanggalAwal, $tanggalAkhir, $kegiatan_id, $tahunAkademik), 'Rekap Kehadiran ' . $namaKegiatan . ' ' . $namaKelas . ' ' . $tanggalAwal . ' - ' . $tanggalAkhir . '.xlsx');
    }
  }
}
