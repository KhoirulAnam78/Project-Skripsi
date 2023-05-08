<?php

namespace App\Http\Controllers\Api;


use App\Models\Siswa;
use App\Models\WaliAsrama;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;

class WaliAsramaApiController extends Controller
{
  public function getJadwal()
  {
    $tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
    $angkatan = WaliAsrama::where('user_id', auth('sanctum')->user()->id)->first()->angkatans->where('status', 'belum lulus')->first();
    $jadwal = JadwalKegiatan::where('angkatan_id', $angkatan->id)->where('tahun_akademik_id', $tahunAkademik)->with('kegiatan')->get();

    return response()->json([
      'message' => 'Fetch data success',
      'jadwal-angkatan' => $jadwal,
    ]);
  }
}
