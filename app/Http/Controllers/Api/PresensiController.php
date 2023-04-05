<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\JadwalGuruPiket;
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
        // if (auth('sanctum')->user()->role === 'guru') {
        //     $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
        //         $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
        //     })->first();
        //     $status = 'belum tervalidasi';
        //     if ($jadwalToday === null) {
        //         $guruPiketId = null;
        //     } else {
        //         $guruPiketId = $jadwalToday->guru_id;
        //     }
        // } else {
        //     $guruPiketId = null;
        //     $status = 'valid';
        // }
        // $monitoring = MonitoringPembelajaran::create([
        //     'tanggal' => $request->tanggal,
        //     'topik' => $request->agendaBelajar,
        //     'waktu_mulai' => $request->jamDimulai,
        //     'waktu_berakhir' => $request->jamBerakhir,
        //     'status_validasi' => $status,
        //     'jadwal_pelajaran_id' => $request->filterMapel,
        //     'guru_piket_id' => $guruPiketId
        // ]);
        // foreach ($presensi as $value) {
        //     KehadiranPembelajaran::create([
        //         'siswa_id' => $value->siswaID,
        //         'status' => $value->status,
        //         'monitoring_pembelajaran_id' => $monitoring->id
        //     ]);
        // }
        return response()->json([
            'message' => 'Data berhasil dikirim',
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->jamDimulai,
            'waktu_berakhir' => $request->jamBerakhir,
            'topik' => $request->agendaBelajar,
            'presensi' => $presensi[0]->siswaID,
            'jadwal_id' => $request->jadwalId
        ]);
    }
}
