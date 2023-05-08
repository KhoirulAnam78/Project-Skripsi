<?php

namespace App\Http\Livewire;

use App\Models\Narasumber;
use Livewire\Component;
use App\Exports\NarasumberExport;
use App\Imports\ImportNarasumber;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TabelNarasumber extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $no_telp, $instansi, $narasumber_edit_id, $narasumber_delete_id;
    public $file, $search = '';
    //Rules Validation
    protected $rules = [
        'file' => 'required|mimes:xlsx,xls',
        'nama' => 'required',
        'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
        'instansi' => 'required',
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->file = null;
        $this->nama = null;
        $this->instansi = null;
        $this->no_telp = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama wajib diisi !',
        'no_telp.required' => 'No Telp wajib diisi !',
        'no_telp.max' => 'No Telp maksimal 14 karakter angka (numeric) !',
        'no_telp.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
        'instansi.required' => 'Instansi wajib diisi !',
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
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'instansi' => 'required',
        ]);

        Narasumber::create([
            'nama' => $this->nama,
            'instansi' => $this->instansi,
            'no_telp' => $this->no_telp,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function editNarasumber($id)
    {
        $narasumber = Narasumber::find($id);
        $this->narasumber_edit_id = $id;
        $this->nama = $narasumber->nama;
        $this->instansi = $narasumber->instansi;
        $this->no_telp = $narasumber->no_telp;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'instansi' => 'required',
        ]);
        Narasumber::where('id', $this->narasumber_edit_id)->update([
            'nama' => $this->nama,
            'instansi' => $this->instansi,
            'no_telp' => $this->no_telp,
        ]);

        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->narasumber_delete_id = $id; //narasumber id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteNarasumberData()
    {
        $narasumber = Narasumber::where('id', $this->narasumber_delete_id)->first();
        try {
            $narasumber->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->narasumber_delete_id = '';
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new ImportNarasumber, $this->file);
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
        return Excel::download(new NarasumberExport, 'Data Narasumber SMAN Titian Teras.xlsx');
    }


    public function render()
    {
        return view('livewire.tabel-narasumber', [
            'narasumber' => Narasumber::where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(5),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
