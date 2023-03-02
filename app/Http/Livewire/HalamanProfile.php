<?php

namespace App\Http\Livewire;

use App\Models\Guru;
use App\Models\User;
use App\Models\Siswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HalamanProfile extends Component
{
    public $username;
    public $no_telp;
    public $password;
    public $validation = [];
    public $user;

    protected $messages = [
        'no_telp.required' => 'No Telp wajib diisi !',
        'no_telp.max' => 'No Telp maksimal 14 karakter angka (numeric) !',
        'no_telp.regex' => 'No Telp merupakan angka dan boleh menggunakan karakter + !',
        'username.required' => 'Username wajib diisi !',
        'username.unique' => 'Username telah digunakan !',
        'password.min' => 'Password harus terdiri dari min 8 karakter',
    ];

    public function empty()
    {
        $this->user = User::find(Auth::user()->id);
        $this->username = $this->user->username;
        if ($this->user->role === 'guru') {
            $this->no_telp = $this->user->guru->no_telp;
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
                'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            ];
        }
        if ($this->user->role === 'siswa') {
            $this->no_telp = $this->user->siswa->no_telp;
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
                'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            ];
        }
        if ($this->user->role === 'admin') {
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
            ];
        }
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function edit()
    {
        $this->validate($this->validation);

        if ($this->password) {
            $this->validate([
                'password' => 'min:8'
            ]);
            User::where('id', $this->user->id)->update([
                'username' => $this->username,
                'password' => bcrypt($this->password)
            ]);
        } else {
            User::where('id', $this->user->id)->update([
                'username' => $this->username
            ]);
        }

        if ($this->user->role === 'guru') {
            Guru::where('user_id', $this->user->id)->update(['no_telp' => $this->no_telp]);
        }
        if ($this->user->role === 'siswa') {
            Siswa::where('user_id', $this->user->id)->update(['no_telp' => $this->no_telp]);
        }

        $this->empty();
        session()->flash('message', 'Data berhasil diubah !');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function mount()
    {
        $this->user = User::find(Auth::user()->id);
        $this->username = $this->user->username;
        if ($this->user->role === 'guru') {
            $this->no_telp = $this->user->guru->no_telp;
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
                'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            ];
        }
        if ($this->user->role === 'siswa') {
            $this->no_telp = $this->user->siswa->no_telp;
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
                'no_telp' => 'required|max:14|regex:/^([0-9\s\+]*)$/',
            ];
        }
        if ($this->user->role === 'admin') {
            $this->validation = [
                'username' => 'required|unique:users,username,' . $this->user->id,
            ];
        }
    }

    public function render()
    {
        return view('livewire.halaman-profile', [
            'user' => User::find(Auth::user()->id)
        ]);
    }
}
