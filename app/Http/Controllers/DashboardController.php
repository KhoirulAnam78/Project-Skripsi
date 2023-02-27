<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
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
        } else if (Auth::user()->role === 'pimpinan') {
        } else {
            return abort(403, 'Unauthorized action.');
        }
    }
}
