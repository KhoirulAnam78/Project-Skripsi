<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\WaliAsrama;
use App\Exports\WaliAsramaExport;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Imports\WaliAsramaImport;
use Maatwebsite\Excel\Facades\Excel;

class TabelWaliAsrama extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    //Inisialisasi Variable
    public $nama, $status, $no_telp, $username, $password, $wali_edit_id, $wali_delete_id;
    public $file, $search = '', $checkbox, $checkboxUname;
    //Rules Validation
    protected $rules = [
        'file' => 'required|mimes:xlsx,xls',
        'nama' => 'required',
        'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
        'status' => 'required',
        'username' => 'required|unique:users',
        'password' => 'required|min:8'
    ];

    //Mengosongkan inputan pada modal
    public function empty()
    {
        $this->file = null;
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
        'nama.required' => 'Nama wajib diisi !',
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
        if ($this->wali_edit_id) {
            $this->rules = [
                'nama' => 'required',
                'no_telp' => 'required|max:14',
                'status' => 'required',
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
        $this->validate([
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'username' => $this->username,
            'password' => bcrypt($this->password),
            'role' => 'wali_asrama'
        ]);

        WaliAsrama::create([
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
    public function editWali($id)
    {
        $wali_asrama = WaliAsrama::find($id);
        $this->wali_edit_id = $wali_asrama->id;
        $this->nama = $wali_asrama->nama;
        $this->status = $wali_asrama->status;
        $this->no_telp = $wali_asrama->no_telp;
        // $this->username = $wali_asrama->user->username;
        // $this->password = '';
        $this->dispatchBrowserEvent('show-edit-modal');
    }

    //Update data
    public function update()
    {
        $this->validate([
            'nama' => 'required',
            'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            'status' => 'required',
        ]);

        WaliAsrama::where('id', $this->wali_edit_id)->update([
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
        $this->wali_delete_id = $id; //wali_asrama id

        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }

    //Delete data
    public function deleteWaliData()
    {
        $wali_asrama = WaliAsrama::where('id', $this->wali_delete_id)->first();
        try {
            $wali_asrama->delete();
            User::destroy($wali_asrama->user_id);
            session()->flash('message', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            session()->flash('error', 'Data gagal dihapus karena digunakan di dalam sistem');
        }

        $this->dispatchBrowserEvent('close-modal-delete');

        $this->wali_delete_id = '';
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new WaliAsramaImport, $this->file);
            session()->flash('message', 'Data berhasil diimport');
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            session()->flash('importError', $failures);
            $this->file = '';
            $this->dispatchBrowserEvent('close-modal-import');
        } catch (\Illuminate\Database\QueryException $ex) {
            session()->flash('error', 'Terdapat nama yang sama pada file excel atau pada sistem. Periksa kembali !');
            $this->dispatchBrowserEvent('close-modal-import');
        }
    }


    public function defaultPw()
    {
        if ($this->password === null) {
            $this->password = 'monitoring2023';
            $this->checkbox = true;
        } else {
            $this->password = null;
            $this->checkbox = false;
        }
    }

    public function defaultUname()
    {
        if ($this->username === null) {
            $this->username = Str::slug($this->nama);
            // dd($this->username);
            $this->checkboxUname = true;
        } else {
            $this->username = null;
            $this->checkboxUname = false;
        }
    }

    public function export()
    {
        return Excel::download(new WaliAsramaExport, 'Data Wali Asrama SMAN Titian Teras.xlsx');
    }

    public function resetPassword($id)
    {
        $user = WaliAsrama::find($id);

        User::where('id', $user->user_id)->update([
            'username' => Str::slug($user->nama),
            'password' => bcrypt('monitoring2023')
        ]);

        session()->flash('message', 'Akun berhasil direset, username : ' . Str::slug($user->nama) . ' dan password : ' . 'monitoring2023');
    }


    public function render()
    {
        return view('livewire.tabel-wali-asrama', [
            'wali_asrama' => WaliAsrama::where('nama', 'like', '%' . $this->search . '%')->orderBy('nama', 'asc')->paginate(10),
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
