<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;
use App\Models\KehadiranPembelajaran;

class PimpinanApiController extends Controller
{
  public $kelasAktif = [];
  public $tanggal;

  public function getPersentase(Request $request)
  {
    $this->tanggal = $request->tanggal;
    $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
    foreach ($data as $d) {
      array_push($this->kelasAktif, $d->id);
    }
    $persentase = [];
    if ($request->hari && $request->tanggal) {
      if ($request->hari == 'Senin') {
        $waktu = array(
          array(
            'mulai' => '07:55',
            'berakhir' => '08:35'
          ),
          array(
            'mulai' => '08:35',
            'berakhir' => '09:15'
          ),
          array(
            'mulai' => '09:15',
            'berakhir' => '09:55'
          ),
          array(
            'mulai' => '10:40',
            'berakhir' => '11:20'
          ),
          array(
            'mulai' => '11:20',
            'berakhir' => '12:00'
          ),
          array(
            'mulai' => '13:00',
            'berakhir' => '13:35'
          ),
          array(
            'mulai' => '13:35',
            'berakhir' => '14:10'
          ),
          array(
            'mulai' => '14:10',
            'berakhir' => '15:45'
          ),
          array(
            'mulai' => '15:45',
            'berakhir' => '15:20'
          ),
        );
      } else if ($request->hari == 'Jumat') {
        $waktu = array(
          array(
            'mulai' => '07:15',
            'berakhir' => '07:55'
          ),
          array(
            'mulai' => '07:55',
            'berakhir' => '08:35'
          ),
          array(
            'mulai' => '08:35',
            'berakhir' => '09:15'
          ),
          array(
            'mulai' => '10:00',
            'berakhir' => '10:40'
          ),
          array(
            'mulai' => '10:40',
            'berakhir' => '11:20'
          ),
        );
      } else {
        $waktu = array(
          array(
            'mulai' => '07:15',
            'berakhir' => '07:55'
          ),
          array(
            'mulai' => '07:55',
            'berakhir' => '08:35'
          ),
          array(
            'mulai' => '08:35',
            'berakhir' => '09:15'
          ),
          array(
            'mulai' => '09:15',
            'berakhir' => '09:55'
          ),
          array(
            'mulai' => '10:40',
            'berakhir' => '11:20'
          ),
          array(
            'mulai' => '11:20',
            'berakhir' => '12:00'
          ),
          array(
            'mulai' => '13:00',
            'berakhir' => '13:35'
          ),
          array(
            'mulai' => '13:35',
            'berakhir' => '14:10'
          ),
          array(
            'mulai' => '14:10',
            'berakhir' => '15:45'
          ),
          array(
            'mulai' => '15:45',
            'berakhir' => '15:20'
          ),
        );
      }
      foreach ($waktu as $w) {
        $jadwal = JadwalPelajaran::where('waktu_mulai', '>=', $w['mulai'])->where('waktu_berakhir', '<=', $w['berakhir'])->where('hari', $request->hari)->whereIn('kelas_id', $this->kelasAktif)->with(['monitoringPembelajarans' => function ($query) {
          if ($query) {
            $query->where('tanggal', $this->tanggal)->select('id', 'jadwal_pelajaran_id', 'status_validasi');
          };
        }])->select('id', 'hari', 'waktu_mulai', 'waktu_berakhir')->get();

        $total = count($jadwal);
        $terlaksana = 0;
        foreach ($jadwal as $j) {
          if (count($j->monitoringPembelajarans) !== 0) {
            if ($j->monitoringPembelajarans->first()->status_validasi === 'terlaksana') {
              $terlaksana++;
            }
          }
        }
        if ($total !== 0) {
          $persen = round($terlaksana / $total, 1);
        } else {
          $persen = 0;
        }
        array_push($persentase, ['waktu_mulai' => $w['mulai'], 'waktu_berakhir' => $w['berakhir'], 'persentase' => $persen]);
      }

      return response()->json([
        'message' => 'Fetch data success',
        'tanggal' => $request->tanggal,
        'waktu' => $persentase
        // 'jadwal-pengganti' => $jadwalPengganti
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
    $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas;
    return response()->json([
      'message' => 'Fetch data success',
      'kelas' => $data
    ]);
  }

  public function getDetail(Request $request)
  {
    if ($request->waktu_mulai && $request->waktu_berakhir && $request->tanggal && $request->kelas_id && $request->hari) {
      $this->tanggal = $request->tanggal;
      $jadwal = JadwalPelajaran::where('waktu_mulai', '>=', $request->waktu_mulai)->where('hari', $request->hari)->where('waktu_berakhir', '<=', $request->waktu_berakhir)->where('kelas_id', $request->kelas_id)->with(['guru' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['mataPelajaran' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['monitoringPembelajarans' => function ($query) {
        $query->where('tanggal', $this->tanggal);
      }])->select('id', 'mata_pelajaran_id', 'guru_id', 'waktu_mulai', 'waktu_berakhir')->get();
      // $monitoring_id = $jadwal->first()->monitoringPembelajarans->first()->id;
      // $presensi = [];
      // $siswa = Kelas::where('id', $request->kelas_id)->first()->siswas;
      // foreach ($siswa as $s) {
      //   array_push($presensi, ['siswa' => $s->nama, 'presensi' => KehadiranPembelajaran::where('monitoring_id', $monitoring_id)->where('siswa_id', $s->id)->first()->status]);
      // }
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal' => $jadwal,
        // 'presensi' => $presensi
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data gagal',
        'data' => 'Hari, waktu mulai, waktu berakhir, tanggal, dan kelas id wajib diisi'
      ]);
    }
  }
}
