<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required',
        'password' => 'required|min:8'
    ];

    protected $messages = [
        'username.required' => 'Username wajib diisi !',
        'password.required' => 'Password wajib diisi !',
        'password.min' => 'Password harus terdiri dari min 8 karakter'
    ];

    public function render()
    {
        return view('livewire.login-form');
    }

    public function updated($propertyName)
    {

        $this->validateOnly($propertyName);
    }

    public function authenticate()
    {
        $validatedData = $this->validate();
        if (Auth::attempt($validatedData)) {
            request()->session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            return redirect('/login')->with('loginError', 'Username atau Password Anda salah!!');
        }
    }
}
