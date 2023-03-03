<?php

namespace App\Http\Livewire;

use App\Models\Kelas;
use Livewire\Component;
use App\Exports\ExportKelas;
use App\Imports\KelasImport;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TabelKelas extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $tahun_akademik_id, $kelas_edit_id, $kelas_delete_id, $search = '', $compositeUnique;
    public $file, $filter = '';
    //Rules Validation


    public function mount()
    {
        $this->tahun_akademik_id = TahunAkademik::where('status', 'aktif')->first()->id;
        $this->filter = $this->tahun_akademik_id;
    }
    public function rules()
    {
        if ($this->kelas_edit_id !== null) {
            return
                [
                    'file' => 'required|mimes:xlsx,xls',
                    'nama' => 'required|unique:kelas,nama,' . $this->kelas_edit_id . ',id,tahun_akademik_id,' . $this->tahun_akademik_id,
                    // 'tahun_akademik_id' => 'required|unique:kelas,tahun_akademik_id,NULL,id,nama,' . $this->nama
                ];
        } else {
            return
                [
                    'file' => 'required|mimes:xlsx,xls',
                    'nama' => 'required|unique:kelas,nama,NULL,id,tahun_akademik_id,' . $this->tahun_akademik_id,
                    // 'tahun_akademik_id' => 'required|unique:kelas,tahun_akademik_id,NULL,id,nama,' . $this->nama
                ];
        }
    }


    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = null;
        $this->file = null;
        $this->kelas_delete_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama kelas wajib diisi !',
        'nama.unique' => 'Nama kelas pada tahun akademik ini sudah ada !',
        // 'tahun_akademik_id.required' => 'Tahun akademik wajib diisi !',
        // 'tahun_akademik_id.unique' => '',
        'file.required' => 'File tidak boleh kosong',
        'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
    ];

    //Reatime Validation
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    //Save data to database
    public function save()
    {
        $this->validate([
            'nama' => 'required|unique:kelas,nama,NULL,id,tahun_akademik_id,' . $this->tahun_akademik_id,
            // 'tahun_akademik_id' => 'required|unique:kelas,tahun_akademik_id,NULL,id,nama,' . $this->nama
        ]);
        Kelas::create([
            'nama' => $this->nama,
            'tahun_akademik_id' => $this->tahun_akademik_id,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $kelas = Kelas::find($id);
        $this->tahun_akademik_id = $kelas->tahun_akademik_id;
        // dd($this->tahun_akademik_id);
        $this->nama = $kelas->nama;
        $this->kelas_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:kelas,nama,' . $this->kelas_edit_id . ',id,tahun_akademik_id,' . $this->tahun_akademik_id,
            // 'tahun_akademik_id' => 'required|unique:kelas,tahun_akademik_id,' . $this->kelas_edit_id . ',id,nama,' . $this->nama
        ]);
        Kelas::where('id', $this->kelas_edit_id)->update([
            'nama' => $this->nama,
            'tahun_akademik_id' => $this->tahun_akademik_id,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->kelas_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteKelasData()
    {
        $kelas = Kelas::where('id', $this->kelas_delete_id)->first();
        try {
            $kelas->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->kelas_delete_id = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.tabel-kelas', [
            'kelas' => Kelas::where('nama', 'like', '%' . $this->search . '%')->where('tahun_akademik_id', $this->filter)->latest()->paginate(5),
            'tahun_akademik' => TahunAkademik::latest()->get()->all()
        ]);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new KelasImport, $this->file);
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
        $id = $this->filter;
        return Excel::download(new ExportKelas($id), 'Data Kelas SMAN Titian Teras.xlsx');
    }
}
