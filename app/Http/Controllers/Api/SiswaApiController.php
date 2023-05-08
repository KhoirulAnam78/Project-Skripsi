<?php

namespace App\Http\Controllers\Api;


use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;

class SiswaApiController extends Controller
{
  public $jadwal;
  public $hari;
  public $tanggal;
  public function getJadwal(Request $request)
  {
    if ($request->hari and $request->tanggal) {
      $this->jadwal = Siswa::where('user_id', auth('sanctum')->user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
        $query->whereRelation('tahunAkademik', 'status', 'aktif')->with('jadwalPelajarans');
      }])->first();
      $this->tanggal = $request->tanggal;
      $jadwalPengganti = JadwalPengganti::where('tanggal', $request->tanggal)->with(['jadwalPelajaran' => function ($query) {
        // $query->with()->get();
        $query->where('kelas_id', $this->jadwal->kelas->first()->id)->with('guru')->with(['monitoringPembelajarans' => function ($query) {
          if ($query) {
            $query->where('tanggal', $this->tanggal)->with(['kehadiranPembelajarans' => function ($query) {
              $query->where('siswa_id', auth('sanctum')->user()->siswa->id);
            }]);
          };
        }])->with(['kelas' => function ($query) {
          $query->select('id', 'nama');
        }])->with(['mataPelajaran' => function ($query) {
          $query->select('id', 'nama');
        }])->get();
      }])->get();

      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-siswa' => $this->jadwal->kelas->first()->jadwalPelajarans()->where('hari', $request->hari)->with('mataPelajaran', 'guru')->with(['monitoringPembelajarans' => function ($query) {
          if ($query) {
            $query->where('tanggal', $this->tanggal)->with(['kehadiranPembelajarans' => function ($query) {
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

  public $tahunAkademik;

  public function getNonAkademik(Request $request)
  {
    if ($request->hari and $request->tanggal) {
      $this->tanggal = $request->tanggal;
      $this->hari = $request->hari;
      $this->tahunAkademik = TahunAkademik::where('status', 'aktif')->first()->id;

      $data = Siswa::where('user_id', auth('sanctum')->user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
        if ($query) {
          $query->whereRelation('tahunAkademik', 'status', 'aktif')->with(['angkatan' => function ($query) {
            $query->with(['jadwalKegiatans' => function ($query) {
              $query->where('tahun_akademik_id', $this->tahunAkademik)->with('kegiatan')->where('hari', '=', 'Setiap Hari')->orwhere('hari', '=', $this->hari)->with(['monitoringKegnas' => function ($query) {
                if ($query) {
                  $query->with('narasumber')->where('tanggal', $this->tanggal)->with(['kehadiranKegnas' => function ($query) {
                    if ($query) {
                      $query->where('siswa_id', auth('sanctum')->user()->siswa->id);
                    } else {
                      $query;
                    }
                  }]);
                } else {
                  $query;
                }
              }])->with(['monitoringKegiatan' => function ($query) {
                if ($query) {
                  $query->where('tanggal', $this->tanggal)->with(['kehadiranKegiatan' => function ($query) {
                    if ($query) {
                      $query->where('siswa_id', auth('sanctum')->user()->siswa->id);
                    } else {
                      $query;
                    }
                  }]);
                } else {
                  $query;
                }
              }]);;
            }]);
          }]);
        } else {
          $query;
        }
      }])->get();
      // dd($data[0]->kelas->first()->angkatan->jadwalKegiatans);
      return response()->json([
        'message' => 'Fetch data success',
        'jadwal-kegiatan' => $data[0]->kelas->first()->angkatan->jadwalKegiatans
      ]);
    } else {
      return response()->json([
        'message' => 'Fetch data failed',
        'request' => 'Hari is required !',
      ]);
    }
  }
}
