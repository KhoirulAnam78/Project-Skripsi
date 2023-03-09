<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Rombel;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Exports\ExportSiswa;
use App\Imports\SiswaImport;
use Livewire\WithPagination;
use App\Exports\RombelExport;
use App\Imports\ImportRombel;
use App\Models\TahunAkademik;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TabelRombel extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $rombel_delete_id;
    public $file, $search = '', $search2 = '';
    public $filterKelas = '', $filterTahunAkademik = '', $kelas = null;
    public $listSiswa;
    public $selectedSiswa = [];
    //Rules Validation
    protected $rules = [
        'file' => 'required|file|mimes:xlsx,xls',
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->file = null;
        $this->search2 = '';
        $this->resetErrorBag();
        $this->resetValidation();
        $this->selectedSiswa = [];
    }

    //Custom Errror messages for validation
    protected $messages = [
        'file.required' => 'File tidak boleh kosong',
        'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
    ];

    public function mount()
    {
        $this->filterTahunAkademik = TahunAkademik::select('id')->where('status', 'aktif')->first()->id;
        $this->kelas = TahunAkademik::select('id')->where('status', 'aktif')->first()->kelas;
        $this->filterKelas = $this->kelas->first()->id;
        // dd($this->filterKelas);
    }

    public function save()
    {
        foreach ($this->selectedSiswa as $siswa) {
            Rombel::create([
                'siswa_id' => $siswa,
                'kelas_id' => $this->filterKelas
            ]);
            // array_push($this->listSiswa, $siswa);
        }
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new ImportRombel($this->filterKelas), $this->file);
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
        return Excel::download(new RombelExport($this->filterKelas), 'Data Siswa ' . $namaKelas . '.xlsx');
    }

    public function updatedFilterTahunAkademik($tahunAkademik_id)
    {
        $this->kelas = TahunAkademik::where('id', $tahunAkademik_id)->first()->kelas;
        $this->filterKelas = $this->kelas->first()->id;
    }


    public function render()
    {
        if ($this->filterTahunAkademik !== '') {
            $status = TahunAkademik::where('id', $this->filterTahunAkademik)->first()->status;
            if ($status === 'aktif') {
                $this->listSiswa = [];
                foreach ($this->kelas as $k) {
                    // dd($k->id);
                    $coba = Kelas::where('id', $k->id)->first()->siswas()->pluck('siswa_id')->toArray();
                    foreach ($coba as $c) {
                        array_push($this->listSiswa, $c);
                    }
                }
                $allow = true;
                $addSiswa = Siswa::whereNotIn('id', $this->listSiswa)->where('nama', 'like', '%' . $this->search2 . '%')->where('status', 'aktif')->orderBy('created_at')->paginate(5);
            } else {
                $addSiswa = null;
                $allow = false;
            }
            if ($this->filterKelas !== '') {
                $siswa = Kelas::where('id', $this->filterKelas)->first()->siswas()->where('nama', 'like', '%' . $this->search . '%')->paginate(5);
            } else {
                $siswa = [];
            }
        } else {
            $siswa = [];
            $allow = false;
            $addSiswa = null;
        }

        return view('livewire.tabel-rombel', [
            'siswa' => $siswa,
            'allow' => $allow,
            'dataSiswa' => $addSiswa,
            'tahun_akademik' => TahunAkademik::latest()->get()->all()
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteConfirmation($id)
    {
        $this->rombel_delete_id = $id; //siswa id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteRombelData()
    {
        $rombel = Rombel::where('siswa_id', $this->rombel_delete_id)->where('kelas_id', $this->filterKelas)->first();
        try {
            $rombel->delete();
            session()->flash('message', 'Data berhasil dihapus dari rombongan belajar !');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');
        $this->empty();
        $this->listSiswa = array_diff($this->listSiswa, array($this->rombel_delete_id));
        $this->rombel_delete_id = '';
    }
}
