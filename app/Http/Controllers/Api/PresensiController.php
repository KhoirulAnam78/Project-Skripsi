<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;
use App\Http\Controllers\Controller;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSiswa(Request $request)
    {
        // dd($request);
        if ($request->kelas_id) {
            $siswa = Siswa::whereRelation('kelas', 'kelas_id', $request->kelas_id)->select('id', 'nama')->get();
            return response()->json([
                'message' => 'Fetch data success',
                'siswa' => $siswa,
            ]);
        }
    }

    public function presensiPembelajaran(Request $request)
    {
        $presensi = json_decode($request->presensi);
        if (auth('sanctum')->user()->role === 'guru') {
            $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
                $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
            })->first();
            $status = 'belum tervalidasi';
            if ($jadwalToday === null) {
                $guruPiketId = null;
            } else {
                $guruPiketId = $jadwalToday->guru_id;
            }
        } else {
            $guruPiketId = null;
            $status = 'valid';
        }
        $monitoring = MonitoringPembelajaran::create([
            'tanggal' => $request->tanggal,
            'topik' => $request->agendaBelajar,
            'waktu_mulai' => $request->jamDimulai,
            'waktu_berakhir' => $request->jamBerakhir,
            'status_validasi' => $status,
            'jadwal_pelajaran_id' => $request->jadwalId,
            'guru_piket_id' => $guruPiketId
        ]);
        foreach ($presensi as $value) {
            KehadiranPembelajaran::create([
                'siswa_id' => $value->siswaID,
                'status' => $value->status,
                'monitoring_pembelajaran_id' => $monitoring->id
            ]);
        }
        return response()->json([
            'message' => 'Data berhasil diinputkan',
        ]);
    }

    public function validasi()
    {
        $jadwalToday = JadwalGuruPiket::where('guru_id', auth('sanctum')->user()->guru->id)->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->first();
        if ($jadwalToday === null) {
            $jadwal = [];
            $jadwalPengganti = [];
        } else {
            //Mengambil jadwal hari ini
            $jadwal = JadwalPelajaran::select('id', 'waktu_mulai', 'waktu_berakhir', 'kelas_id', 'guru_id', 'mata_pelajaran_id')->where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where('waktu_mulai', '>=', $jadwalToday->waktu_mulai)->where('waktu_berakhir', '<=', $jadwalToday->waktu_berakhir)->with(
                [
                    'guru' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                ]
            )->with(['monitoringPembelajarans' => function ($query) {
                $query->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->get();
            }])->get();

            //Get Jadwal Pengganti
            $jadwalPengganti = JadwalPengganti::where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->where('waktu_mulai', '>=', $jadwalToday->waktu_mulai)->where('waktu_berakhir', '<=', $jadwalToday->waktu_berakhir)->with(['jadwalPelajaran' => function ($query) {
                $query->with([
                    'guru' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'kelas' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'mataPelajaran' => function ($query) {
                        $query->select('id', 'nama');
                    },
                    'monitoringPembelajarans' => function ($query) {
                        $query->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->get();
                    }
                ])->select('id', 'guru_id', 'kelas_id', 'mata_pelajaran_id');
            }])->get();
        }
        return response()->json([
            'message' => 'Fetch data berhasil!',
            'validasi-jadwal' => $jadwal,
            'validasi-pengganti' => $jadwalPengganti
        ]);
    }

    public function valid(Request $request)
    {
        $monitoring = MonitoringPembelajaran::where('id', $request->monitoring_id)->update([
            'status_validasi' => 'valid',
            'keterangan' => ''
        ]);
        return response()->json([
            'message' => 'Validasi berhasil!',
            'monitoring' => $monitoring
        ]);
    }

    public function tidakValid(Request $request)
    {
        $presensi = json_decode($request->presensi);

        $guruPiketId = auth('sanctum')->user()->guru->id;

        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $request->jadwal_id)->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->first()) {
            // MonitoringPembelajaran::where('id', $this->editPresensi)
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $request->jadwal_id)->where('tanggal', \Carbon\Carbon::now()->translatedFormat('Y-m-d'))->update([
                'keterangan' => $request->keterangan,
                'topik' => $request->topik,
                'status_validasi' => 'tidak valid',
                'guru_piket_id' => $guruPiketId
            ]);
            foreach ($presensi as $value) {
                KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->where('siswa_id', $value->siswaID)->update([
                    'status' => $value->status,
                ]);
            }
        } else {
            $jadwal = JadwalPelajaran::where('id', $request->jadwal_id)->first();
            $waktu_mulai = $jadwal->waktu_mulai;
            $waktu_berakhir = $jadwal->waktu_berakhir;

            $monitoring = MonitoringPembelajaran::create([
                'tanggal' => \Carbon\Carbon::now()->translatedFormat('Y-m-d'),
                'topik' => $request->topik,
                'waktu_mulai' => $waktu_mulai,
                'waktu_berakhir' => $waktu_berakhir,
                'status_validasi' => 'tidak valid',
                'jadwal_pelajaran_id' => $request->jadwal_id,
                'guru_piket_id' => $guruPiketId,
                'keterangan' => $request->keterangan
            ]);
            foreach ($presensi as $value) {
                KehadiranPembelajaran::create([
                    'siswa_id' => $value->siswaID,
                    'status' => $value->status,
                    'monitoring_pembelajaran_id' => $monitoring->id
                ]);
            }
        }
        return response()->json([
            'message' => 'Validasi berhasil!',
            'monitoring' => $monitoring
        ]);
    }
}
