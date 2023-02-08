<?php

namespace App\Http\Livewire;

use App\Models\JadwalGuruPiket;
use App\Models\Kelas;
use Livewire\Component;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\MonitoringPembelajaran;

class InputPresensi extends Component
{
    public $filterKelas = '';
    public $siswa;
    public $day;
    public $mapel;
    public $tanggal, $waktu_mulai, $waktu_berakhir, $topik;
    public $jadwal_pelajaran_id;
    public $presensi = [];
    public function mount()
    {
        $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        $this->siswa = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
        foreach ($this->siswa as $s) {
            $this->presensi[$s->id] = 'hadir';
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
        $this->tanggal = null;
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
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', 'like', '%' . $this->filterKelas . '%')->where('hari', $this->day)->get()->all();
        $this->siswa = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
    }

    public function save()
    {
        dd('Belum bisa di save wkwk');
        foreach ($this->presensi as $key => $value) {
            dd($key);
        }
        $this->validate();
        $jadwalToday = JadwalGuruPiket::where('hari', \Carbon\Carbon::now()->translatedFormat('l'))->where(function ($query) {
            $query->where('waktu_mulai', '<=', \Carbon\Carbon::now()->translatedFormat('h:i'))->orWhere('waktu_berakhir', '>=', \Carbon\Carbon::now()->translatedFormat('h:i'));
        })->get();
        $guruPiketId = $jadwalToday->guru_id;
        $monitoring = MonitoringPembelajaran::create([
            'tanggal' => $this->tanggal,
            'topik' => $this->topik,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'status_validasi' => 'tidak valid',
            'jadwal_pelajaran_id' => $this->jadwal_pelajaran_id,
            'guru_piket_id' => $guruPiketId
        ]);

        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function inputPresensi($id)
    {
        $this->jadwal_pelajaran_id = $id;
        $this->dispatchBrowserEvent('show-input-modal');
    }

    public function render()
    {
        $this->siswa = Kelas::where('id', $this->filterKelas)->first()->siswas->all();
        $this->day = \Carbon\Carbon::now()->translatedFormat('l');
        $this->mapel = JadwalPelajaran::with('guru')->with('kelas')->with('mataPelajaran')->where('kelas_id', 'like', '%' . $this->filterKelas . '%')->where('hari', $this->day)->get()->all();
        return view('livewire.input-presensi', [
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel
        ]);
    }
}
