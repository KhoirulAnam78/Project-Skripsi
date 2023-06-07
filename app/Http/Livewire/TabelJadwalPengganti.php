<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPengganti;

class TabelJadwalPengganti extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $mapel, $filterKelas = '', $japel_id;
    public $tanggal, $waktu_mulai, $waktu_berakhir;
    public $jadwal_edit_id, $jadwal_delete_id;
    public function mount()
    {
        if (TahunAkademik::where('status', 'aktif')->first()->kelas->first()) {
            $this->filterKelas = TahunAkademik::where('status', 'aktif')->first()->kelas->first()->id;
        } else {
            $this->filterKelas = '';
        }
        $this->mapel = JadwalPelajaran::with('mataPelajaran')->with('guru')->where('kelas_id', $this->filterKelas)->orderBy('hari', 'asc')->get()->all();
        // dd($this->mapel);
        if ($this->mapel) {
            $this->japel_id = $this->mapel[0]->id;
        } else {
            $this->japel_id = null;
        }
    }
    public function rules()
    {
        if ($this->jadwal_edit_id !== null) {
            return [
                'tanggal' => 'required|date',
                'japel_id' => 'required|unique:jadwal_penggantis,jadwal_pelajaran_id,' . $this->jadwal_edit_id . ',id,tanggal,' . $this->tanggal,
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            ];
        } else {
            return [
                'tanggal' => 'required|date',
                'japel_id' => 'required|unique:jadwal_penggantis,jadwal_pelajaran_id,NULL,id,tanggal,' . $this->tanggal,
                'waktu_mulai' => 'required|date_format:H:i',
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
            ];
        }
    }

    //Custom Errror messages for validation
    protected $messages = [
        'tanggal.required' => 'Tanggal wajib diisi !',
        'japel_id.unique' => 'Jadwal pengganti telah diinputkan pada hari/tanggal yang sama',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'japel_id.required' => 'Jadwal pengganti wajib diisi !',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function empty()
    {
        $this->tanggal = null;
        $this->jadwal_edit_id = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        JadwalPengganti::create([
            'tanggal' => $this->tanggal,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'jadwal_pelajaran_id' => $this->japel_id
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $jadwal = JadwalPengganti::find($id);
        $this->tanggal = $jadwal->tanggal;
        // dd($this->tahun_akademik_id);
        $this->japel_id = $jadwal->jadwal_pelajaran_id;
        $this->filterKelas = $jadwal->jadwalPelajaran->kelas->id;
        $this->waktu_mulai = substr($jadwal->waktu_mulai, 0, -3);
        $this->waktu_berakhir = substr($jadwal->waktu_berakhir, 0, -3);
        $this->jadwal_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'tanggal' => 'required|date',
            'japel_id' => 'required|unique:jadwal_penggantis,jadwal_pelajaran_id,' . $this->jadwal_edit_id . ',id,tanggal,' . $this->tanggal,
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ]);
        JadwalPengganti::where('id', $this->jadwal_edit_id)->update([
            'tanggal' => $this->tanggal,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'jadwal_pelajaran_id' => $this->japel_id,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->jadwal_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteJadwalData()
    {
        $jadwal = JadwalPengganti::where('id', $this->jadwal_delete_id)->first();
        try {
            $jadwal->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->jadwal_delete_id = '';
    }

    public function render()
    {
        $this->mapel = JadwalPelajaran::with('mataPelajaran')->with('guru')->where('kelas_id', $this->filterKelas)->orderBy('hari', 'asc')->get()->all();
        return view('livewire.tabel-jadwal-pengganti', [
            'jadwalPengganti' => JadwalPengganti::latest()->paginate(10),
            'kelas' => TahunAkademik::where('status', 'aktif')->first()->kelas,
            'mapel' => $this->mapel,
        ]);
    }
}
