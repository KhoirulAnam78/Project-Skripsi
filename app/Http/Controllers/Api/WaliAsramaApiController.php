<?php

namespace App\Http\Controllers\Api;


use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Kegiatan;
use App\Models\WaliAsrama;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\JadwalPengganti;
use App\Models\KehadiranKegiatan;
use App\Models\MonitoringKegiatan;
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
  public function getKelas()
  {
    //Cek siswa kelas 

    $tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
    $angkatan_id = WaliAsrama::where('user_id', auth('sanctum')->user()->id)->first()->angkatans->where('status', 'belum lulus')->first()->id;
    $kelas = Kelas::where('tahun_akademik_id', $tahunAkademik)->where('angkatan_id', $angkatan_id)->get();
    return response()->json([
      'message' => 'Fetch data success',
      'kelas' => $kelas,
    ]);
  }

  public $tanggal;
  public $presensi = [];
  public $student;

  public function getPresensiSiswa(Request $request)
  {
    $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
    //Cek siswa kelas 
    $narasumber = Kegiatan::find($request->kegiatan_id)->narasumber;

    $siswa = Kelas::where('id', $request->kelas_id)->first()->siswas->first();
    if ($siswa) {
      $siswa_id = $siswa->id;
    } else {
      $siswa_id = '';
    }

    if ($narasumber == 0) {

      //ambil data siswa kelas yang dipilih
      $this->student = Kelas::where('id', $request->kelas_id)->first()->siswas()->orderBy('nama', 'asc')->get();

      if (MonitoringKegiatan::where('jadwal_kegiatan_id', $request->jadwal_id)->where('tanggal', $this->tanggal)->first()) {
        //ambil data
        //ambil data siswa kelas yang dipilih
        $this->student = Kelas::where('id', $request->kelas_id)->first()->siswas()->orderBy('nama', 'asc')->get();

        $monitoring = MonitoringKegiatan::where('jadwal_kegiatan_id', $request->jadwal_id)->where('tanggal', $this->tanggal)->first();

        //ambil data kehadiran siswa yang sudah diinputkan
        if (KehadiranKegiatan::where('monitoring_kegiatan_id', $monitoring->id)->where('siswa_id', $siswa_id)->get()->first()) {
          $kehadiran = KehadiranKegiatan::where('monitoring_kegiatan_id', $monitoring->id)->get()->all();
          foreach ($kehadiran as $k) {
            $this->presensi[$k->siswa_id] = $k->status;
          }
        } else {
          //set presensi menjadi hadir bagi setiap siswa
          $this->presensi = [];
          foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
          }
        }
      } else {
        //set presensi menjadi hadir bagi setiap siswa
        $this->presensi = [];
        foreach ($this->student as $s) {
          $this->presensi[$s->id] = 'hadir';
        }
      }
    } else {
    }
    return response()->json([
      'message' => 'Fetch data success',
      'presensi' => $this->presensi,
      'siswa' => $this->student,
    ]);
  }

  public function presensi(Request $request)
  {
    $presensi = json_decode($request->presensi);
    $siswa_id = $presensi[0]->siswaID;
    $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
    if (MonitoringKegiatan::where('jadwal_kegiatan_id', $request->jadwal_id)->where('tanggal', $this->tanggal)->first()) {
      //ambil data
      $monitoring = MonitoringKegiatan::where('jadwal_kegiatan_id', $request->jadwal_id)->where('tanggal', $this->tanggal)->first();
      if (KehadiranKegiatan::where('monitoring_kegiatan_id', $monitoring->id)->where('siswa_id', $siswa_id)->get()->first()) {
        //update
        foreach ($presensi as $value) {
          KehadiranKegiatan::where('monitoring_kegiatan_id', $monitoring->id)->where('siswa_id', $value->siswaID)->update([
            'status' => $value->status,
          ]);
        }
      } else {
        //set presensi menjadi hadir bagi setiap siswa
        //save presensi
        foreach ($presensi as $value) {
          KehadiranKegiatan::create([
            'siswa_id' => $value->siswaID,
            'status' => $value->status,
            'monitoring_kegiatan_id' => $monitoring->id
          ]);
        }
      }
    } else {
      $monitoring = MonitoringKegiatan::create([
        'tanggal' => $this->tanggal,
        'waktu_mulai' => $request->waktu_mulai,
        'waktu_berakhir' => $request->waktu_berakhir,
        'jadwal_kegiatan_id' => $request->jadwal_id,
      ]);
      //input presensinya
      foreach ($presensi as $value) {
        KehadiranKegiatan::create([
          'siswa_id' => $value->siswaID,
          'status' => $value->status,
          'monitoring_kegiatan_id' => $monitoring->id
        ]);
      }
    }

    return response()->json([
      'message' => 'Presensi success',
    ]);
  }
}
