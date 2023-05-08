<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Imports\MapelImport;
use Livewire\WithPagination;
use App\Models\MataPelajaran;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class TabelMapel extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $mapel_edit_id, $mapel_delete_id, $search = '';
    public $file;
    //Rules Validation


    public function rules()
    {
        if ($this->mapel_edit_id !== null) {
            return
                [
                    'file' => 'required|mimes:xlsx,xls',
                    'nama' => 'required|unique:mata_pelajarans,nama,' . $this->mapel_edit_id,
                ];
        } else {
            return
                [
                    'file' => 'required|mimes:xlsx,xls',
                    'nama' => 'required|unique:mata_pelajarans',
                ];
        }
    }


    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = null;
        $this->file = null;
        $this->mapel_delete_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama mata pelajaran wajib diisi !',
        'nama.unique' => 'Nama mata pelajaran sudah ada !',
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
            'nama' => 'required|unique:mata_pelajarans'
        ]);
        MataPelajaran::create([
            'nama' => $this->nama,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $mapel = MataPelajaran::find($id);
        $this->nama = $mapel->nama;
        $this->mapel_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:mata_pelajarans,nama,' . $this->mapel_edit_id,
        ]);
        MataPelajaran::where('id', $this->mapel_edit_id)->update([
            'nama' => $this->nama,
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->mapel_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteKelasData()
    {
        $mapel = MataPelajaran::where('id', $this->mapel_delete_id)->first();
        try {
            $mapel->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->mapel_delete_id = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.tabel-mapel', [
            'mapel' => MataPelajaran::where('nama', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new MapelImport, $this->file);
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
}
