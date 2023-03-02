<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'siswa') {
            $data = Auth::user()->siswa->id;
            $jadwal = Siswa::where('id', $data)->select('id',)->with(['kelas' => function ($query) {
                $query->whereRelation('tahunAkademik', 'status', 'aktif')->with(['jadwalPelajarans' => function ($sql) {
                    $hari = \Carbon\Carbon::now()->translatedFormat('l');
                    $sql->where('hari', $hari)->get();
                }])->get();
            }])->first();
            return view('pages.siswa.dashboard', [
                'title' => 'Dashboard',
                'jadwal' => $jadwal->kelas->first()->jadwalPelajarans
            ]);
        } else if (Auth::user()->role === 'admin') {
            return view('pages.admin.dashboard', [
                'title' => 'Dashboard'
            ]);
        } else if (Auth::user()->role === 'guru') {
            $kelasAktif = [];
            $data = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas->all();
            foreach ($data as $d) {
                array_push($kelasAktif, $d->id);
            }
            //mengambil nama hari 
            $day = \Carbon\Carbon::now()->translatedFormat('l');

            $jadwal = JadwalPelajaran::where('guru_id', Auth::user()->guru->id)->whereIn('kelas_id', $kelasAktif)->where('hari', $day)->with(['kelas' => function ($query) {
                $query->select('id', 'nama');
            }])->with(['mataPelajaran' => function ($query) {
                $query->select('id', 'nama');
            }])->paginate(10);
            return view('pages.guru.dashboard', [
                'title' => 'Dashboard',
                'jadwal' => $jadwal
            ]);
        } else if (Auth::user()->role === 'pimpinan') {
            return view('pages.pimpinan.dashboard', [
                'title' => 'Dashboard'
            ]);
        } else {
            return abort(403, 'Anda tidak memiliki akses kehalaman ini.');
        }
    }

    public function profile()
    {
        return view('profile', [
            'title' => 'Halaman Profile'
        ]);
    }
}