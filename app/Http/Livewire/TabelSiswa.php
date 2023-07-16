<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Exports\ExportSiswa;
use App\Imports\SiswaImport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TabelSiswa extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nisn, $nama, $status, $username, $password;
    public $siswa_edit_id, $siswa_delete_id;
    public $file, $search = '', $checkbox, $checkboxUname;
    // public $filter_kelas = '', $filter_tahun_akademik = '';
    //Rules Validation
    protected $rules = [
        'file' => 'required|mimes:xlsx,xls',
        'nisn' => 'required|numeric|min_digits:10|max_digits:10|unique:siswas',
        'nama' => 'required',
        'status' => 'required',
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
        $this->username = null;
        $this->siswa_edit_id = null;
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
                'status' => 'required',
                'username' => 'required|unique:users',
                // 'kelas_id' => 'required',
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
            'status' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'role' => 'siswa'
        ]);

        Siswa::create([
            'nisn' => $this->nisn,
            'nama' => $this->nama,
            'status' => $this->status,
            'user_id' => $user->id,
        ]);
        // Rombel::create([
        //     'siswa_id' => $siswa->id,
        //     'kelas_id' => $this->kelas_id
        // ]);
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
            'status' => 'required',
        ]);

        Siswa::where('id', $this->siswa_edit_id)->update([
            'nisn' => $this->nisn,
            'nama' => $this->nama,
            'status' => $this->status,
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
    public function deleteSiswaData()
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

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new SiswaImport, $this->file);
            session()->flash('message', 'Data berhasil diimport');
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('importError', $failures);
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Illuminate\Database\QueryException $ex) {
            session()->flash('errorDuplikasi', 'Terdapat nisn yang sama pada file excel. Periksa kembali !');
            $this->dispatchBrowserEvent('close-modal-import');
        }
    }

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

    public function export()
    {
        return Excel::download(new ExportSiswa, 'Data Siswa SMAN Titian Teras.xlsx');
    }


    public function render()
    {

        return view('livewire.tabel-siswa', [
            'siswa' => Siswa::where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(10),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetPassword($id)
    {
        $user = Siswa::find($id);

        User::where('id', $user->user_id)->update([
            'username' => $user->nisn,
            'password' => bcrypt($user->nisn)
        ]);

        session()->flash('message', 'Akun berhasil direset, user dapat login menggunakan nisn sebagai username dan password !');
    }
}
