<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalGuruPiket;
use App\Models\JadwalPelajaran;
use App\Models\KehadiranPembelajaran;
use App\Models\MonitoringPembelajaran;

class InputPresensi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterKelas = '', $filterMapel;
    public $student;
    public $day;
    public $update = false;
    public $editPresensi;
    public $mapel;
    public $tanggal, $waktu_mulai, $waktu_berakhir, $topik;
    public $presensi = [];

    public function mount()
    {
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        $this->tanggal = \Carbon\Carbon::now()->translatedFormat('Y-m-d');
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
        if (count($this->mapel) !== 0) {
            $this->filterMapel = $this->mapel[0]->id;
            if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first()) {
                $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first();
                $this->editPresensi = $monitoring->id;
                $this->tanggal = $monitoring->tanggal;
                $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
                $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
                $this->topik = $monitoring->topik;
                $this->update = true;
                $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
                foreach ($kehadiran as $k) {
                    $this->presensi[$k->siswa_id] = $k->status;
                }
                // dd($this->presensi);
            } else {
                $this->update = false;
            }
        } else {
            $this->filterMapel = '';
        }
    }

    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'topik' => 'required',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ];
    }

    //Custom Errror messages for validation
    protected $messages = [
        'tanggal.required' => 'Tanggal wajib diisi !',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'topik.required' => 'Topik/Agenda wajib diisi !',
    ];

    public function empty()
    {
        $this->topik = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->editPresensi = null;
        $this->resetErrorBag();
        $this->resetValidation();
        // $this->dispatchBrowserEvent('close-input-modal');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedFilterKelas()
    {
        $this->empty();
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
        $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
        $this->presensi = [];
        foreach ($this->student as $s) {
            $this->presensi[$s->id] = 'hadir';
        }
        if (count($this->mapel) !== 0) {
            $this->filterMapel = $this->mapel[0]->id;
        } else {
            $this->filterMapel = '';
        }
    }

    public function updatedFilterMapel()
    {
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first()) {
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first();
            $this->tanggal = $monitoring->tanggal;
            $this->editPresensi = $monitoring->id;
            $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
            $this->topik = $monitoring->topik;
            $this->update = true;
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
            // dd($this->presensi);
        } else {
            $this->empty();
            $this->update = false;
            $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
            $this->presensi = [];
            foreach ($this->student as $s) {
                $this->presensi[$s->id] = 'hadir';
            }
        }
    }

    public function updatedTanggal()
    {
        if (MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first()) {
            $monitoring = MonitoringPembelajaran::where('jadwal_pelajaran_id', $this->filterMapel)->where('tanggal', $this->tanggal)->first();
            // $this->tanggal = $monitoring->tanggal;
            $this->editPresensi = $monitoring->id;
            $this->waktu_mulai = substr($monitoring->waktu_mulai, 0, -3);
            $this->waktu_berakhir = substr($monitoring->waktu_berakhir, 0, -3);
            $this->topik = $monitoring->topik;
            $this->update = true;
            $kehadiran = KehadiranPembelajaran::where('monitoring_pembelajaran_id', $monitoring->id)->get()->all();
            foreach ($kehadiran as $k) {
                $this->presensi[$k->siswa_id] = $k->status;
            }
            // dd($this->presensi);
        } else {
            $this->empty();
            $this->update = false;
            $this->student = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
            $this->presensi = [];
            foreach ($this->student as $s) {
                $this->presensi[$s->id] = 'hadir';
            }
        }
    }

    public function save()
    {
        $this->validate();
        $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
            $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
        })->first();
        if ($jadwalToday === null) {
            session()->flash('error', 'Presensi tidak dapat ditambahkan karena tidak ada guru piket pada jam saat ini !');
        } else {
            $guruPiketId = $jadwalToday->guru_id;
            $monitoring = MonitoringPembelajaran::create([
                'tanggal' => $this->tanggal,
                'topik' => $this->topik,
                'waktu_mulai' => $this->waktu_mulai,
                'waktu_berakhir' => $this->waktu_berakhir,
                'status_validasi' => 'belum tervalidasi',
                'jadwal_pelajaran_id' => $this->filterMapel,
                'guru_piket_id' => $guruPiketId
            ]);
            foreach ($this->presensi as $key => $value) {
                KehadiranPembelajaran::create([
                    'siswa_id' => $key,
                    'status' => $value,
                    'monitoring_pembelajaran_id' => $monitoring->id
                ]);
            }
            session()->flash('message', 'Presensi berhasil diinputkan !');
            $this->empty();
        }
    }

    public function update()
    {
        $this->validate();
        MonitoringPembelajaran::where('id', $this->editPresensi)->update([
            'tanggal' => $this->tanggal,
            'topik' => $this->topik,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
        ]);
        foreach ($this->presensi as $key => $value) {
            KehadiranPembelajaran::where('monitoring_pembelajaran_id', $this->editPresensi)->where('siswa_id', $key)->update([
                'status' => $value,
            ]);
        }
        session()->flash('message', 'Presensi berhasil diupdate !');
    }

    public function render()
    {
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->day)->get()->all();
        return view('livewire.input-presensi', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
            'siswa' => Kelas::where('id', $this->filterKelas)->first()->siswas()->paginate(10)
        ]);
    }
}
