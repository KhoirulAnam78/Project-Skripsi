<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAkademik;

class TabelTahunAkademik extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $tgl_mulai, $semester, $tgl_berakhir, $tahun_akademik_edit_id, $status, $search = '';
    //Rules Validation
    protected $rules = [
        'nama' => 'required|unique:tahun_akademiks',
        'tgl_mulai' => 'required|date',
        'tgl_berakhir' => 'required|date',
        'status' => 'required',
        'semester' => 'required'
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = null;
        $this->tgl_mulai = null;
        $this->tgl_berakhir = null;
        $this->status = null;
        $this->semester = null;
        $this->tahun_akademik_edit_id = null;
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama tahun akademik wajib diisi !',
        'nama.unique' => 'Nama tahun akademik telah digunakan !',
        'tgl_mulai.required' => 'Tanggal mulai wajib diisi !',
        'tgl_mulai.date' => 'Data yang diperbolehkan adalah date !',
        'tgl_berakhir.required' => 'Tanggal berakhir wajib diisi !',
        'tgl_berakhir.date' => 'Data yang diperbolehkan adalah date !',
        'status.required' => 'Status wajib diisi !',
        'semester.required' => 'Semester wajib diisi !',
    ];

    //Reatime Validation
    public function updated($propertyName)
    {
        if ($this->tahun_akademik_edit_id) {
            $this->rules = [
                'nama' => 'required|unique:tahun_akademiks,nama,' . $this->tahun_akademik_edit_id,
                'tgl_mulai' => 'required|date',
                'tgl_berakhir' => 'required|date',
                'status' => 'required',
                'semester' => 'required'
            ];
            $this->validateOnly($propertyName);
        } else {
            $this->validateOnly($propertyName);
        }
    }

    //Save data to database
    public function save()
    {
        $this->validate();
        TahunAkademik::create([
            'nama' => $this->nama,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_berakhir' => $this->tgl_berakhir,
            'status' => $this->status,
            'semester' => $this->semester,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function edit($id)
    {
        $tahun_akademik = TahunAkademik::find($id);
        $this->tahun_akademik_edit_id = $tahun_akademik->id;
        $this->nama = $tahun_akademik->nama;
        $this->tgl_mulai = $tahun_akademik->tgl_mulai;
        $this->tgl_berakhir = $tahun_akademik->tgl_berakhir;
        $this->status = $tahun_akademik->status;
        $this->semester = $tahun_akademik->semester;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:tahun_akademiks,nama,' . $this->tahun_akademik_edit_id,
            'tgl_mulai' => 'required|date',
            'tgl_berakhir' => 'required|date',
            'status' => 'required',
            'semester' => 'required'
        ]);

        TahunAkademik::where('id', $this->tahun_akademik_edit_id)->update([
            'nama' => $this->nama,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_berakhir' => $this->tgl_berakhir,
            'status' => $this->status,
            'semester' => $this->semester,
        ]);

        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->tahun_akademik_delete_id = $id; //tahun_akademik id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteTahunAkademikData()
    {
        $tahun_akademik = TahunAkademik::where('id', $this->tahun_akademik_delete_id)->first();
        try {
            $tahun_akademik->delete();
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->tahun_akademik_delete_id = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.tabel-tahun-akademik', [
            'tahun_akademik' => TahunAkademik::where('nama', 'like', '%' . $this->search . '%')->latest()->paginate(5),

        ]);
    }
}
