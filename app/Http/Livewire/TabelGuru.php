<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use App\Models\User;
use Livewire\Component;

class TabelGuru extends Component
{
    public $nip, $nama, $kode_guru, $status, $no_telp, $username, $password = 'smantitianteras', $guru_edit_id;

    protected $rules = [
        'nip' => 'required|min:18|unique:gurus',
        'nama' => 'required',
        'no_telp' => 'required|max:14',
        'status' => 'required',
        'kode_guru' => 'required|min:2|max:2|unique:gurus',
        'username' => 'required|unique:users',
        'password' => 'required|min:8'
    ];

    public function empty()
    {
        $this->nip = '';
        $this->kode_guru = '';
        $this->nama = '';
        $this->status = '';
        $this->no_telp = '';
        $this->username = '';
        $this->password = 'smantitianteras';
    }

    protected $messages = [
        'nip.required' => 'NIP wajib diisi !',
        'nip.min' => 'NIP harus berisi 18 karakter !',
        'nip.unique' => 'NIP telah digunakan !',
        'nama.required' => 'Nama wajib diisi !',
        'no_telp.required' => 'No Telp wajib diisi !',
        'no_telp.max' => 'No Telp maksimal 14 karakter number !',
        'status.required' => 'Status wajib diisi !',
        'kode_guru.required' => 'Kode Guru wajib diisi !',
        'kode_guru.min' => 'Kode Guru harus berisi 2 karakter !',
        'kode_guru.max' => 'Kode Guru harus berisi 2 karakter !',
        'kode_guru.unique' => 'Kode Guru telah digunakan !',
        'username.required' => 'Username wajib diisi !',
        'username.unique' => 'Username telah digunakan !',
        'password.required' => 'Password wajib diisi !',
        'password.min' => 'Password harus terdiri dari min 8 karakter'
    ];

    public function updated($propertyName)
    {
        if ($this->guru_edit_id) {
            $this->rules = [
                'nip' => 'required|min:18|unique:gurus,nip,' . $this->guru_edit_id,
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

    public function save()
    {
        $this->validate();
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

    public function update()
    {
        $this->validate([
            'nip' => 'required|min:18|unique:gurus,nip,' . $this->guru_edit_id,
            'nama' => 'required',
            'no_telp' => 'required|max:14',
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

    public function render()
    {
        return view('livewire.tabel-guru', [
            'guru' => Guru::latest()->get()->all()
        ]);
    }
}
