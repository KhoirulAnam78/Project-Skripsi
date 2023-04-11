<?php

namespace App\Http\Livewire;

use App\Models\Angkatan;
use Livewire\Component;
use Livewire\WithPagination;

class TabelAngkatan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $status, $angkatan_edit_id, $angkatan_delete_id;
    public $waliAsrama = [];

    public function rules()
    {
        if ($this->angkatan_edit_id !== null) {
            return
                [
                    'nama' => 'required|unique:angkatans,nama,' . $this->angkatan_edit_id,
                    'status' => 'required',
                ];
        } else {
            return
                [
                    'nama' => 'required|unique:angkatans,nama,NULL,id',
                    'status' => 'required',
                ];
        }
    }


    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = '';
        $this->status = '';
        $this->angkatan_delete_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama angkatan wajib diisi !',
        'status.required' => 'Status angkatan wajib diisi !',
        'nama.unique' => 'Nama angkatan sudah ada !',
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
            'nama' => 'required|unique:angkatans,nama,NULL,id',
            'status' => 'required'
        ]);
        Angkatan::create([
            'nama' => $this->nama,
            'status' => $this->status
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $angkatan = Angkatan::find($id);
        $this->nama = $angkatan->nama;
        $this->status = $angkatan->status;
        $this->angkatan_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:angkatans,nama,' . $this->angkatan_edit_id,
            'status' => 'required'
        ]);
        Angkatan::where('id', $this->angkatan_edit_id)->update([
            'nama' => $this->nama,
            'status' => $this->status
        ]);
        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->angkatan_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteKelasData()
    {
        $angkatan = Angkatan::where('id', $this->angkatan_delete_id)->first();
        try {
            $angkatan->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->angkatan_delete_id = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.tabel-angkatan', [
            'angkatan' => Angkatan::latest()->paginate(5),
        ]);
    }
}
