<?php

namespace App\Http\Livewire;

use App\Models\Siswa;
use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\JadwalKegiatan;
use App\Models\MonitoringKegnas;
use App\Models\MonitoringKegiatan;
use Illuminate\Support\Facades\Auth;

class RekapKegiatanSiswa extends Component
{
    public $kegiatan, $tanggalAwal, $tanggalAkhir, $jadwalId = [];
    public function mount($kegiatan)
    {
        $this->kegiatan = $kegiatan;
        $this->tanggalAkhir = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->tanggalAwal =  \Carbon\Carbon::now()->subDays(6)->translatedFormat('Y-m-d');
        $angkatan = Siswa::where('user_id', Auth::user()->id)->select('id', 'user_id')->with(['kelas' => function ($query) {
            $query->whereRelation('tahunAkademik', 'status', 'aktif');
        }])->first();
        $angkatan_id = $angkatan->kelas->first()->angkatan->id;
        $tahunAkademikId = TahunAkademik::where('status', 'aktif')->first()->id;
        // dd($angkatan->kelas->first()->angkatan->id);
        $jadwal = JadwalKegiatan::where('angkatan_id', $angkatan_id)->where('tahun_akademik_id', $tahunAkademikId)->where('kegiatan_id', $this->kegiatan->id)->get();
        foreach ($jadwal as $value) {
            array_push($this->jadwalId, $value->id);
        }
    }
    public function render()
    {
        if ($this->kegiatan->narasumber === 1) {
            $monitoring = MonitoringKegnas::whereIn('jadwal_kegiatan_id', $this->jadwalId)->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with('narasumber')->with(['kehadiranKegnas' => function ($query) {
                if ($query) {
                    $query->where('siswa_id', Auth::user()->siswa->id);
                } else {
                    $query;
                }
            }])->paginate(10);
            // dd($monitoring);
        } else {
            $monitoring = MonitoringKegiatan::whereIn('jadwal_kegiatan_id', $this->jadwalId)->where('tanggal', '>=', $this->tanggalAwal)->where('tanggal', '<=', $this->tanggalAkhir)->with(['kehadiranKegiatan' => function ($query) {
                if ($query) {
                    $query->where('siswa_id', Auth::user()->siswa->id);
                } else {
                    $query;
                }
            }])->paginate(10);
        }
        return view('livewire.rekap-kegiatan-siswa', [
            'monitoring' => $monitoring
        ]);
    }
}