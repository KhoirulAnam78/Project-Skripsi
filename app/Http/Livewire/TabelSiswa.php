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

class TabelSiswa extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nisn, $nama, $status, $no_telp, $username, $password, $tahun_akademik_id = '', $kelas_id = '';
    public $siswa_edit_id, $siswa_delete_id;
    public $file, $search = '', $filter_tahun_akademik = '', $filter_kelas = '', $checkbox, $checkboxUname;
    //Rules Validation
    protected $rules = [
        'file' => 'required|mimes:xlsx,xls',
        'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas',
        'nama' => 'required',
        'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
        'status' => 'required',
        'tahun_akademik_id' => 'required',
        'kelas_id' => 'required',
        'username' => 'required|unique:users',
        'password' => 'required|min:8'
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nisn = null;
        $this->file = null;
        $this->nama = null;
        $this->status = null;
        $this->no_telp = null;
        $this->tahun_akademik_id = '';
        $this->kelas_id = '';
        $this->username = null;
        $this->password = null;
        $this->checkbox = false;
        $this->checkboxUname = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nisn.required' => 'NISN wajib diisi !',
        'nisn.min_digits' => 'NISN harus berisi 10 karakter !',
        'nisn.max_digits' => 'NISN lebih dari 10 karakter !',
        'nisn.numeric' => 'NISN harus merupakan angka !',
        'nisn.unique' => 'NISN telah digunakan !',
        'nama.required' => 'Nama wajib diisi !',
        'tahun_akademik_id.required' => 'Nama wajib diisi !',
        'kelas_id.required' => 'Nama wajib diisi !',
        'no_telp.required' => 'No Telp wajib diisi !',
        'no_telp.max' => 'No Telp maksimal 14 karakter angka (numeric) !',
        'no_telp.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
        'status.required' => 'Status wajib diisi !',
        'username.required' => 'Username wajib diisi !',
        'username.unique' => 'Username telah digunakan !',
        'password.required' => 'Password wajib diisi !',
        'password.min' => 'Password harus terdiri dari min 8 karakter',
        'file.required' => 'File tidak boleh kosong',
        'file.mimes' => 'File harus memiliki format excel(.xlxs/.xls)'
    ];

    //Reatime Validation
    public function updated($propertyName)
    {
        if ($this->siswa_edit_id) {
            $this->rules = [
                'nisn' => 'required|min_digits:10|max_digits:10|unique:siswas,nisn,' . $this->siswa_edit_id,
                'nama' => 'required',
                'no_telp' => 'required|max:14',
                'status' => 'required',
                'username' => 'required|unique:users',
                'tahun_akademik_id' => 'required',
                'kelas_id' => 'required',
                // 'password' => 'required|min:8'
            ];
            $this->validateOnly($propertyName);
        } else {
            $this->validateOnly($propertyName);
        }
    }

    //Save data to database
    public function save()
    {
        if ($this->nisn === null or Str::length($this->nisn) < 10) {
            $this->checkbox = false;
            $this->checkboxUname = false;
            $this->username = null;
            $this->password = null;
        }
        $this->validate([
            'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas',
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:8',
            'tahun_akademik_id' => 'required',
            'kelas_id' => 'required',
        ]);
        $user = User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'role' => 'siswa'
        ]);

        $siswa  = Siswa::create([
            'nisn' => $this->nisn,
            'nama' => $this->nama,
            'status' => $this->status,
            'no_telp' => $this->no_telp,
            'user_id' => $user->id,
        ]);
        Rombel::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $this->kelas_id
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function editSiswa($id)
    {
        $siswa = Siswa::find($id);
        $this->siswa_edit_id = $siswa->id;
        $this->nisn = $siswa->nisn;
        $this->nama = $siswa->nama;
        $this->status = $siswa->status;
        $this->no_telp = $siswa->no_telp;
        // $this->username = $siswa->user->username;
        // $this->password = '';
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas,nisn,' . $this->siswa_edit_id,
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
        ]);

        Siswa::where('id', $this->siswa_edit_id)->update([
            'nisn' => $this->nisn,
            'nama' => $this->nama,
            'status' => $this->status,
            'no_telp' => $this->no_telp,
        ]);

        session()->flash('message', 'Data berhasil diedit !');
        $this->empty();
        $this->dispatchBrowserEvent('close-edit-modal');
    }

    //Show modal delete confirmation
    public function deleteConfirmation($id)
    {
        $this->siswa_delete_id = $id; //siswa id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteGuruData()
    {
        $siswa = Siswa::where('id', $this->siswa_delete_id)->first();
        try {
            $siswa->delete();
            User::destroy($siswa->user_id);
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->siswa_delete_id = '';
    }

    // public function import()
    // {
    //     $this->validate([
    //         'file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     try {
    //         Excel::import(new SiswaImport, $this->file);
    //         session()->flash('message', 'Data berhasil diimport');
    //         $this->file = '';
    //         $this->dispatchBrowserEvent('close-modal-import');
    //     } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
    //         $failures = $e->failures();
    //         session()->flash('importError', $failures);
    //         $this->file = '';
    //         $this->dispatchBrowserEvent('close-modal-import');
    //     }
    // }

    public function defaultPw()
    {
        if ($this->password === null) {
            $this->password = $this->nisn;
            $this->checkbox = true;
        } else {
            $this->password = null;
            $this->checkbox = false;
        }
    }
    public function defaultUname()
    {
        if ($this->username === null) {
            $this->username = $this->nisn;
            $this->checkboxUname = true;
        } else {
            $this->username = null;
            $this->checkboxUname = false;
        }
    }

    // public function export()
    // {
    //     return Excel::download(new ExportSiswa, 'Data Siswa SMAN Titian Teras.xlsx');
    // }


    public function render()
    {
        if ($this->filter_tahun_akademik !== '') {
            $kelas = TahunAkademik::where('id', $this->filter_tahun_akademik)->first()->kelas;
            if ($this->filter_kelas !== '') {
                $siswa = Kelas::where('id', $this->filter_kelas)->first()->siswas()->where('nama', 'like', '%' . $this->search . '%')->latest()->paginate(5);
            } else {
                $siswa = [];
            }
        } else {
            $kelas = null;
            $siswa = Siswa::where('nama', 'like', '%' . $this->search . '%')->latest()->paginate(5);
        }
        if ($this->tahun_akademik_id !== '') {
            $kelasModal = TahunAkademik::where('id', $this->tahun_akademik_id)->first()->kelas;
        } else {
            $kelasModal = null;
        }
        return view('livewire.tabel-siswa', [
            'kelas' => $kelas,
            'kelasModal' => $kelasModal,
            'siswa' => $siswa,
            'tahun_akademik' => TahunAkademik::all()
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
