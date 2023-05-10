<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Angkatan;
use App\Models\WaliAsrama;
use Livewire\WithPagination;
use App\Models\TahunAkademik;
use App\Models\AngkatanWaliAsrama;

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
                    'waliAsrama' => 'required'
                ];
        } else {
            return
                [
                    'nama' => 'required|unique:angkatans,nama,NULL,id',
                    'status' => 'required',
                    'waliAsrama' => 'required'
                ];
        }
    }


    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nama = '';
        $this->status = '';
        $this->waliAsrama = [];
        $this->angkatan_delete_id = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nama.required' => 'Nama angkatan wajib diisi !',
        'status.required' => 'Status angkatan wajib diisi !',
        'nama.unique' => 'Nama angkatan sudah ada !',
        'waliAsrama.required' => 'Wajib memilih wali asrama terlebih dahulu !'
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
            'status' => 'required',
            'waliAsrama' => 'required'
        ]);

        $angkatan = Angkatan::create([
            'nama' => $this->nama,
            'status' => $this->status
        ]);

        foreach ($this->waliAsrama as $w) {
            AngkatanWaliAsrama::create([
                'angkatan_id' => $angkatan->id,
                'wali_asrama_id' => $w
            ]);
        }

        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->dispatchBrowserEvent('close-modal');
        $this->empty();
    }

    //show modal edit
    public function edit($id)
    {
        $angkatan = Angkatan::find($id);
        $this->nama = $angkatan->nama;
        $this->status = $angkatan->status;
        $wali = $angkatan->waliAsramas()->select('wali_asrama_id')->get();
        foreach ($wali as $w) {
            array_push($this->waliAsrama, $w->wali_asrama_id);
        }
        // dd($this->waliAsrama);
        $this->angkatan_edit_id = $id;
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required|unique:angkatans,nama,' . $this->angkatan_edit_id,
            'status' => 'required',
            'waliAsrama' => 'required'
        ]);
        if ($this->status === 'belum lulus') {

            Angkatan::where('id', $this->angkatan_edit_id)->update([
                'nama' => $this->nama,
                'status' => $this->status
            ]);
            $akademik = TahunAkademik::where('status', 'aktif')->first()->id;
            $kelas = Angkatan::find($this->angkatan_edit_id)->kelas->where('tahun_akademik_id', $akademik)->all();
            // dd($kelas);
            foreach ($kelas as $k) {
                $siswa = $k->siswas;
                foreach ($siswa as $s) {
                    $s->update(['status' => 'belum lulus']);
                }
            }

            AngkatanWaliAsrama::where('angkatan_id', $this->angkatan_edit_id)->delete();

            // dd($this->waliAsrama);
            foreach ($this->waliAsrama as $w) {
                AngkatanWaliAsrama::create([
                    'angkatan_id' => $this->angkatan_edit_id,
                    'wali_asrama_id' => $w
                ]);
            }
        } else {
            Angkatan::where('id', $this->angkatan_edit_id)->update([
                'nama' => $this->nama,
                'status' => $this->status
            ]);
            $kelas = Angkatan::find($this->angkatan_edit_id)->kelas->all();
            foreach ($kelas as $k) {
                $siswa = $k->siswas;
                foreach ($siswa as $s) {
                    $s->update(['status' => 'lulus']);
                }
            }

            AngkatanWaliAsrama::where('angkatan_id', $this->angkatan_edit_id)->delete();

            // dd($this->waliAsrama);
            foreach ($this->waliAsrama as $w) {
                AngkatanWaliAsrama::create([
                    'angkatan_id' => $this->angkatan_edit_id,
                    'wali_asrama_id' => $w
                ]);
            }
        }
        session()->flash('message', 'Data berhasil diedit !');
        $this->dispatchBrowserEvent('close-edit-modal');
        $this->empty();
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
            foreach ($this->waliAsrama as $w) {
                AngkatanWaliAsrama::where('angkatan_id', $angkatan->id)->where('wali_asrama_id', $w->id)->first()->delete();
            }
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
            'angkatan' => Angkatan::orderBy('created_at', 'desc')->paginate(5),
            'wali' => WaliAsrama::where('status', 'aktif')->paginate(5)
        ]);
    }
}
