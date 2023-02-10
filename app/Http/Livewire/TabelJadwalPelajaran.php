<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use App\Models\Kelas;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use App\Models\TahunAkademik;
use Livewire\WithFileUploads;
use App\Models\JadwalPelajaran;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalPelajaranExport;
use App\Imports\JadwalPelajaranImport;

class TabelJadwalPelajaran extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $file;

    public $filterTahunAkademik = '', $filterKelas = '', $kelas = null, $filterHari = '';
    public $hari, $waktu_mulai, $waktu_berakhir, $guru_id, $mata_pelajaran_id;
    public $jadwal_edit_id, $jadwal_delete_id;

    public function rules()
    {
        if ($this->jadwal_edit_id) {
            return [
                'mata_pelajaran_id' => 'required',
                'guru_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_pelajarans,waktu_mulai,' . $this->jadwal_edit_id . ',id,hari,' . $this->hari . ',kelas_id,' . $this->filterKelas,
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:jadwal_pelajarans,hari,' . $this->jadwal_edit_id . ',id,kelas_id,' . $this->filterKelas . ',waktu_mulai,' . $this->waktu_mulai
            ];
        } else {
            return [
                'mata_pelajaran_id' => 'required',
                'guru_id' => 'required',
                'waktu_mulai' => 'required|date_format:H:i|unique:jadwal_pelajarans,waktu_mulai,NULL,id,hari,' . $this->hari . ',kelas_id,' . $this->filterKelas,
                'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu|unique:jadwal_pelajarans,hari,NULL,id,kelas_id,' . $this->filterKelas . ',waktu_mulai,' . $this->waktu_mulai
            ];
        }
    }

    //Custom Errror messages for validation
    protected $messages = [
        'mata_pelajaran_id.required' => 'Field mata pelajaran wajib diisi !',
        'guru_id.required' => 'Field guru wajib diisi !',
        'waktu_mulai.required' => 'Waktu mulai wajib diisi !',
        'waktu_mulai.date_format' => 'Waktu mulai hanya diperbolehkan format waktu !',
        'waktu_mulai.unique' => 'Telah ada jadwal pada hari, waktu dan kelas yang dipilih !',
        'waktu_berakhir.required' => 'Waktu berakhir wajib diisi !',
        'waktu_berakhir.date_format' => 'Waktu berakhir hanya diperbolehkan format waktu !',
        'waktu_berakhir.after' => 'Waktu berakhir harus lebih besar dari waktu mulai !',
        'hari.required' => 'Hari wajib diisi !',
        'hari.unique' => 'Telah ada jadwal pada hari, waktu dan kelas yang dipilih !',
        'file.required' => 'File tidak boleh kosong',
        'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->guru_id = null;
        $this->mata_pelajaran_id = null;
        $this->waktu_mulai = null;
        $this->waktu_berakhir = null;
        $this->hari = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();
        JadwalPelajaran::create([
            'hari' => $this->hari,
            'guru_id' => $this->guru_id,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'kelas_id' => $this->filterKelas
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $jadwal = JadwalPelajaran::find($id);
        $this->guru_id = $jadwal->guru_id;
        // dd($this->tahun_akademik_id);
        $this->hari = $jadwal->hari;
        $this->mata_pelajaran_id = $jadwal->mata_pelajaran_id;
        $this->waktu_mulai = substr($jadwal->waktu_mulai, 0, -3);
        $this->waktu_berakhir = substr($jadwal->waktu_berakhir, 0, -3);
        $this->jadwal_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function update()
    {
        $this->validate();
        JadwalPelajaran::where('id', $this->jadwal_edit_id)->update([
            'hari' => $this->hari,
            'mata_pelajaran_id' => $this->mata_pelajaran_id,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_berakhir' => $this->waktu_berakhir,
            'guru_id' => $this->guru_id,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    public function deleteConfirmation($id)
    {
        $this->jadwal_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteJadwalData()
    {
        $jadwal = JadwalPelajaran::where('id', $this->jadwal_delete_id)->first();
        try {
            $jadwal->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->jadwal_delete_id = '';
    }

    public function updatedFilterTahunAkademik($tahunAkademik_id)
    {
        $this->filterKelas = '';
        $this->kelas = TahunAkademik::where('id', $tahunAkademik_id)->first()->kelas;
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new JadwalPelajaranImport($this->filterKelas), $this->file);
            session()->flash('message', 'Data berhasil diimport');
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('importError', $failures);
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        }
    }

    public function export()
    {
        $namaKelas = Kelas::where('id', $this->filterKelas)->first()->nama;
        return Excel::download(new JadwalPelajaranExport($this->filterKelas), 'Jadwal Pelajaran ' . $namaKelas . '.xlsx');
    }
    public function render()
    {
        if ($this->filterTahunAkademik !== '') {
            $status = TahunAkademik::where('id', $this->filterTahunAkademik)->first()->status;
            if ($status === 'aktif') {
                $allow = true;
            } else {
                $allow = false;
            }
            if ($this->filterKelas !== '') {
                if ($this->filterHari !== '') {
                    $jadwalPelajaran = JadwalPelajaran::with('guru')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->where('hari', $this->filterHari)->latest()->paginate(5);
                } else {
                    $jadwalPelajaran = JadwalPelajaran::with('guru')->with('mataPelajaran')->where('kelas_id', $this->filterKelas)->latest()->paginate(5);
                }
            } else {
                $jadwalPelajaran = [];
            }
        } else {
            $jadwalPelajaran = [];
            $allow = false;
        }

        return view('livewire.tabel-jadwal-pelajaran', [
            'jadwalPelajaran' => $jadwalPelajaran,
            'allow' => $allow,
            'matapelajaran' => MataPelajaran::all(),
            'guru' => Guru::where('status', 'aktif')->get()->all(),
            'tahun_akademik' => TahunAkademik::latest()->get()->all()
        ]);
    }

    public function updatingFilterHari()
    {
        $this->resetPage();
    }
}
