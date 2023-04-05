<?php

namespace App\Http\Controllers\Api;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        // return $request->presensi;
        $data = json_decode($request->presensi);
        // // return $data[0]->id;

        return response()->json([
            'message' => 'Data berhasil dikirim',
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->jamDimulai,
            'waktu_berakhir' => $request->jamBerakhir,
            'topik' => $request->agendaBelajar,
            'presensi' => $data[0],
            'jadwal_id' => $request->jadwalId
        ]);
    }
}
