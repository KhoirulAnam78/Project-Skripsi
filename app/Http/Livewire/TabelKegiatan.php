<?php

namespace App\Http\Livewire;

use App\Models\Kegiatan;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class TabelKegiatan extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $narasumber, $kegiatan_edit_id, $kegiatan_delete_id;
    public $search = '';
    //Rules Validation
    protected $rules = [
        'nama' => 'required|unique:kegiatans',
        'narasumber' => 'required',
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = null;
        $this->narasumber = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama wajib diisi !',
        'narasumber.required' => 'Apakah kegiatan memerlukan narasumber ?',
        'nama.unique' => 'Nama kegiatan telah digunakan !',
    ];

    //Reatime Validation
    public function updated($propertyName)
    {
        if ($this->kegiatan_edit_id) {
            $this->rules = [
                'nama' => 'required|unique:kegiatans,nama,' . $this->kegiatan_edit_id,
                'narasumber' => 'required',
            ];
            $this->validateOnly($propertyName);
        } else {
            $this->validateOnly($propertyName);
        }
    }

    //Save data to database
    public function save()
    {
        $this->validate([
            'nama' => 'required|unique:kegiatans',
            'narasumber' => 'required',
        ]);

        $slug = Str::slug($this->nama);

        Kegiatan::create([
            'nama' => $this->nama,
            'narasumber' => $this->narasumber,
            'slug' => $slug,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $kegiatan = Kegiatan::find($id);
        $this->kegiatan_edit_id = $kegiatan->id;
        $this->nama = $kegiatan->nama;
        $this->narasumber = $kegiatan->narasumber;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:kegiatans,nama,' . $this->kegiatan_edit_id,
        ]);
        $slug = Str::slug($this->nama);
        Kegiatan::where('id', $this->kegiatan_edit_id)->update([
            'nama' => $this->nama,
            'slug' => $slug,
        ]);

        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->kegiatan_delete_id = $id; //kegiatan id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteKegiatanData()
    {
        $kegiatan = Kegiatan::where('id', $this->kegiatan_delete_id)->first();
        try {
            $kegiatan->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->kegiatan_delete_id = '';
    }

    public function render()
    {
        return view('livewire.tabel-kegiatan', [
            'kegiatan' => Kegiatan::where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(10),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
