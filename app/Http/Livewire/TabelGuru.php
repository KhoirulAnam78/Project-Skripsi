<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use App\Models\User;
use Livewire\Component;
use App\Exports\ExportGuru;
use App\Imports\GuruImport;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class TabelGuru extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nip, $nama, $kode_guru, $status, $no_telp, $username, $password, $guru_edit_id, $guru_delete_id;
    public $file, $search = '', $checkbox, $checkboxUname;
    //Rules Validation
    protected $rules = [
        'file' => 'required|mimes:xlsx,xls',
        'nip' => 'required|numeric|min_digits:18|max_digits:18|unique:gurus',
        'nama' => 'required',
        'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
        'status' => 'required',
        'kode_guru' => 'required|min:2|max:2|unique:gurus',
        'username' => 'required|unique:users',
        'password' => 'required|min:8'
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->nip = null;
        $this->file = null;
        $this->kode_guru = null;
        $this->nama = null;
        $this->status = null;
        $this->no_telp = null;
        $this->username = null;
        $this->password = null;
        $this->checkbox = false;
        $this->checkboxUname = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    //Custom Errror messages for validation
    protected $messages = [
        'nip.required' => 'NIP wajib diisi !',
        'nip.min_digits' => 'NIP harus berisi 18 karakter !',
        'nip.max_digits' => 'NIP lebih dari 18 karakter !',
        'nip.numeric' => 'NIP harus merupakan angka !',
        'nip.unique' => 'NIP telah digunakan !',
        'nama.required' => 'Nama wajib diisi !',
        'no_telp.required' => 'No Telp wajib diisi !',
        'no_telp.max' => 'No Telp maksimal 14 karakter angka (numeric) !',
        'no_telp.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
        'status.required' => 'Status wajib diisi !',
        'kode_guru.required' => 'Kode Guru wajib diisi !',
        'kode_guru.min' => 'Kode Guru harus berisi 2 karakter !',
        'kode_guru.max' => 'Kode Guru harus berisi 2 karakter !',
        'kode_guru.unique' => 'Kode Guru telah digunakan !',
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
        if ($this->guru_edit_id) {
            $this->rules = [
                'nip' => 'required|min_digits:18|max_digits:18|unique:gurus,nip,' . $this->guru_edit_id,
                'nama' => 'required',
                'no_telp' => 'required|max:14',
                'status' => 'required',
                'kode_guru' => 'required|min:2|max:2|unique:gurus,kode_guru,' . $this->guru_edit_id,
                'username' => 'required|unique:users',
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
        if ($this->nip === null or Str::length($this->nip) < 18) {
            $this->checkbox = false;
            $this->checkboxUname = false;
            $this->username = null;
            $this->password = null;
        }
        $this->validate([
            'nip' => 'required|numeric|min_digits:18|max_digits:18|unique:gurus',
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
            'kode_guru' => 'required|min:2|max:2|unique:gurus',
            'username' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'role' => 'guru'
        ]);

        Guru::create([
            'nip' => $this->nip,
            'kode_guru' => $this->kode_guru,
            'nama' => $this->nama,
            'status' => $this->status,
            'no_telp' => $this->no_telp,
            'user_id' => $user->id,
        ]);
        session()->flash('message', 'Data berhasil ditambahkan !');
        $this->empty();
        $this->dispatchBrowserEvent('close-modal');
    }

    //show modal edit
    public function editGuru($id)
    {
        $guru = Guru::find($id);
        $this->guru_edit_id = $guru->id;
        $this->nip = $guru->nip;
        $this->nama = $guru->nama;
        $this->kode_guru = $guru->kode_guru;
        $this->status = $guru->status;
        $this->no_telp = $guru->no_telp;
        // $this->username = $guru->user->username;
        // $this->password = '';
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nip' => 'required|numeric|min_digits:18|max_digits:18|unique:gurus,nip,' . $this->guru_edit_id,
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
            'kode_guru' => 'required|min:2|max:2|unique:gurus,kode_guru,' . $this->guru_edit_id,
        ]);

        Guru::where('id', $this->guru_edit_id)->update([
            'nip' => $this->nip,
            'kode_guru' => $this->kode_guru,
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
        $this->guru_delete_id = $id; //guru id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteGuruData()
    {
        $guru = Guru::where('id', $this->guru_delete_id)->first();
        try {
            $guru->delete();
            User::destroy($guru->user_id);
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->guru_delete_id = '';
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new GuruImport, $this->file);
            session()->flash('message', 'Data berhasil diimport');
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('importError', $failures);
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Illuminate\Database\QueryException $ex) {
            session()->flash('error', 'Terdapat nip yang sama pada file excel. Periksa kembali !');
            $this->dispatchBrowserEvent('close-modal-import');
        }
    }

    public function setPimpinan($id)
    {
        $guru = Guru::find($id);
        if ($guru->pimpinan == 0) {
            $set = 1;
        } else {
            $set = 0;
        }
        Guru::where('id', $id)->update([
            'pimpinan' => $set
        ]);

        session()->flash('message', 'Data berhasil diubah !');
    }

    public function defaultPw()
    {
        if ($this->password === null) {
            $this->password = $this->nip;
            $this->checkbox = true;
        } else {
            $this->password = null;
            $this->checkbox = false;
        }
    }
    public function defaultUname()
    {
        if ($this->username === null) {
            $this->username = $this->nip;
            $this->checkboxUname = true;
        } else {
            $this->username = null;
            $this->checkboxUname = false;
        }
    }

    public function export()
    {
        return Excel::download(new ExportGuru, 'Data Guru SMAN Titian Teras.xlsx');
    }


    public function resetPassword($id)
    {
        $user = Guru::find($id);

        User::where('id', $user->user_id)->update([
            'username' => $user->nip,
            'password' => bcrypt($user->nip)
        ]);

        session()->flash('message', 'Akun berhasil direset, user dapat login menggunakan nip sebagai username dan password !');
    }

    public function render()
    {
        return view('livewire.guru', [
            'guru' => Guru::where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(5),
            'show' => true
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
