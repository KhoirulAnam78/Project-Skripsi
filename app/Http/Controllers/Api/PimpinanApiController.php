<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
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
      $jadwal = JadwalPelajaran::where('waktu_mulai', '<=', $request->waktu_mulai)->where('hari', $request->hari)->where('waktu_berakhir', '<=', $request->waktu_berakhir)->where('kelas_id', $request->kelas_id)->with(['guru' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['mataPelajaran' => function ($query) {
        $query->select('id', 'nama');
      }])->with(['monitoringPembelajarans' => function ($query) {
        $query->where('tanggal', $this->tanggal);
      }])->select('id', 'mata_pelajaran_id', 'guru_id', 'waktu_mulai', 'waktu_berakhir')->get();
      $siswa = [];
      $presensi = [];
      if ($jadwal->first()) {
        if ($jadwal->first()->monitoringPembelajarans->first()) {
          $monitoring_id = $jadwal->first()->monitoringPembelajarans->first()->id;
          $siswa = Kelas::where('id', $request->kelas_id)->first()->siswas;
          foreach ($siswa as $s) {
            array_push($presensi, ['siswa' => $s->nama, 'presensi' => $s->kehadiranPembelajarans->where('monitoring_pembelajaran_id', $monitoring_id)->first()->status]);
          }
        }
      }
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal' => $jadwal,
        'monitoring_id' => $monitoring_id,
        'presensi' => $presensi
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data gagal',
        'data' => 'Hari, waktu mulai, waktu berakhir, tanggal, dan kelas id wajib diisi'
      ]);
    }
  }

  public $hari, $tahunAkademik;
  public function getNonAkademik(Request $request)
  {
    if ($request->hari and $request->tanggal) {
      $this->tanggal = $request->tanggal;
      $this->hari = $request->hari;
      $this->tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;

      $jadwal = JadwalKegiatan::where('tahun_akademik_id', $this->tahunAkademik)->where('hari', '=', 'Setiap Hari')->orwhere('hari', '=', $this->hari)->with('kegiatan')->with(['monitoringKegnas' => function ($query) {
        if ($query) {
          $query->with('narasumber')->where('tanggal', $this->tanggal);
        } else {
          $query;
        }
      }])->with(['monitoringKegiatan' => function ($query) {
        if ($query) {
          $query->where('tanggal', $this->tanggal);
        } else {
          $query;
        }
      }])->get()->groupBy('kegiatan_id');

      $persentase = [];
      foreach ($jadwal as $key => $j) {
        $terlaksana = 0;
        $kegiatan = Kegiatan::find($key);
        foreach ($j as $k) {
          if ($k->kegiatan->narasumber == 0) {
            if ($k->monitoringKegiatan->first()) {
              $terlaksana++;
            }
          } else {
            if ($k->monitoringKegnas->first()) {
              $terlaksana++;
            }
          }
        }
        $total = count($j);
        $persen = $terlaksana / $total;
        array_push($persentase, ['kegiatan' => $kegiatan->nama, 'kegiatan_id' => $kegiatan->id, 'terlaksana' => $persen, 'waktu_mulai' => $j[0]->waktu_mulai, 'waktu_berakhir' => $j[0]->waktu_berakhir]);
      }

      // $jadwal = JadwalKegiatan::where('tahun_akademik_id', $this->tahunAkademik)
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-kegiatan' => $persentase
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari dan tanggal wajib diisi !',
      ]);
    }
  }

  public function getDetailNonakademik(Request $request)
  {
    //ambil kegiatan_id, kelas_id, hari, tanggal

    //ambil jadwal kegiatan dimana kegiatan_id, angkatan_id, hari sesuai
    //kirim dengan monitoringnya
    if ($request->kegiatan_id && $request->tanggal && $request->kelas_id && $request->hari) {
      $this->tanggal = $request->tanggal;
      $this->tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;
      //ambil angkatan id
      $angkatan_id = Kelas::find($request->kelas_id)->angkatan->id;

      $jadwal = JadwalKegiatan::where('kegiatan_id', $request->kegiatan_id)
        ->where('hari', '=', 'Setiap Hari')->orwhere('hari', '=', $request->hari)
        ->where('angkatan_id', $angkatan_id)->where('tahun_akademik_id', $this->tahunAkademik)->with('kegiatan')
        ->with(['monitoringKegnas' => function ($query) {
          if ($query) {
            $query->with('narasumber')->where('tanggal', $this->tanggal);
          } else {
            $query;
          }
        }])->with(['monitoringKegiatan' => function ($query) {
          if ($query) {
            $query->where('tanggal', $this->tanggal);
          } else {
            $query;
          }
        }])
        ->first();

      //ambil monitoring id dan siswa
      $monitoring_id = '';
      $presensi = [];
      if ($jadwal->kegiatan->narasumber === 0) {
        if ($jadwal->monitoringKegiatan->first()) {
          $monitoring_id = $jadwal->monitoringKegiatan->first()->id;
          $siswa = Kelas::where('id', $request->kelas_id)->first()->siswas;
          foreach ($siswa as $s) {
            array_push($presensi, [
              'siswa' => $s->nama, 'presensi' => $s->kehadiranKegiatan->where('monitoring_kegiatan_id', $monitoring_id)->first()->status
            ]);
          }
        }
      } else {
        if ($jadwal->monitoringKegnas->first()) {
          $monitoring_id = $jadwal->monitoringKegnas->first()->id;
          $siswa = Kelas::where('id', $request->kelas_id)->first()->siswas;
          foreach ($siswa as $s) {
            array_push($presensi, ['siswa' => $s->nama, 'presensi' => $s->kehadiranKegnas->where('monitoring_kegnas_id', $monitoring_id)->first()->status]);
          }
        }
      }
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal' => $jadwal,
        'monitoring_id' => $monitoring_id,
        'presensi' => $presensi
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data gagal',
        'data' => 'Hari, waktu mulai, waktu berakhir, tanggal, dan kelas id wajib diisi'
      ]);
    }
  }
}
