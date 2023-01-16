<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Exports\ExportSiswa;
use App\Imports\SiswaImport;
use App\Models\Rombel;
use Livewire\WithPagination;
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
        'file' => 'required|mimes:xlsx,xls',
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

    public function save()
    {
        foreach ($this->selectedSiswa as $siswa) {
            Rombel::create([
                'siswa_id' => $siswa,
                'kelas_id' => $this->filterKelas
            ]);
            array_push($this->listSiswa, $siswa);
        }
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export()
    {
        return Excel::download(new ExportSiswa, 'Data Siswa SMAN Titian Teras.xlsx');
    }

    public function updatedFilterTahunAkademik($tahunAkademik_id)
    {
        $this->filterKelas = '';
        $this->kelas = TahunAkademik::where('id', $tahunAkademik_id)->first()->kelas;
        // dd($this->kelas);
        $this->listSiswa = [];
        foreach ($this->kelas as $k) {
            // dd($k->id);
            $coba = Kelas::where('id', $k->id)->first()->siswas()->pluck('siswa_id')->toArray();
            foreach ($coba as $c) {
                array_push($this->listSiswa, $c);
            }
        }
        // dd($this->listSiswa);
    }


    public function render()
    {
        if ($this->filterTahunAkademik !== '') {
            $status = TahunAkademik::where('id', $this->filterTahunAkademik)->first()->status;
            if ($status === 'aktif') {
                $allow = true;
                $addSiswa = Siswa::whereNotIn('id', $this->listSiswa)->where('nama', 'like', '%' . $this->search2 . '%')->where('status', 'aktif')->paginate(10);
            } else {
                $addSiswa = null;
                $allow = false;
            }
            if ($this->filterKelas !== '') {
                $siswa = Kelas::where('id', $this->filterKelas)->first()->siswas()->where('nama', 'like', '%' . $this->search . '%')->latest()->paginate(5);
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
            'tahun_akademik' => TahunAkademik::all()
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
