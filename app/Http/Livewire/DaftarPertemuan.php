<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KehadiranPembelajaran;
use App\Exports\DaftarPertemuanExport;
use App\Models\MonitoringPembelajaran;

class DaftarPertemuan extends Component
{
    public $mapel;
    public $filterKelas = '';
    public $filterMapel = null;
    public $presensi = [];
    public function mount()
    {
        //set default kelas
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        //Ambil Mata pelajaran
        $this->filterMapel = MataPelajaran::first()->id;
        // if (JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()) {
        //     $this->filterMapel = JadwalPelajaran::where('kelas_id', $this->filterKelas)->first()->id;
        // } else {
        //     $this->filterMapel = null;
        // }
    }

    public function detail($id)
    {
        //ambil data
        $monitoring = MonitoringPembelajaran::find($id);

        //ambil data kehadiran siswa yang sudah diinputkan
        $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
        foreach ($kehadiran as $k) {
            $this->presensi[$k->siswa_id] = $k->status;
        }
        $this->dispatchBrowserEvent('show-detail-modal');
    }

    public function export()
    {
        $namaKelas = Kelas::find($this->filterKelas)->nama;
        $namaMapel = MataPelajaran::find($this->filterMapel)->nama;
        $jml_siswa = Kelas::select('id')->find($this->filterKelas)->siswas->count();
        return Excel::download(new DaftarPertemuanExport($this->filterKelas, $this->filterMapel, $jml_siswa), 'Daftar Pertemuan ' . $namaMapel . ' ' . $namaKelas . '.xlsx');
    }

    public function render()
    {
        $this->mapel = MataPelajaran::all();
        return view('livewire.daftar-pertemuan', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'pertemuan' => MonitoringPembelajaran::with('kehadiranPembelajarans')->whereRelation('jadwalPelajaran', 'mata_pelajaran_id', $this->filterMapel)->whereRelation('jadwalPelajaran', 'kelas_id', $this->filterKelas)->paginate(10),
            'jml_siswa' => count(Kelas::where('id', $this->filterKelas)->first()->siswas),
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
        ]);
    }
}
