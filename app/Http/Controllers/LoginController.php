<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginGuru()
    {
        User::where('id', Auth::user()->id)->update(['role' => 'guru']);
        request()->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function loginPimpinan()
    {
        User::where('id', Auth::user()->id)->update(['role' => 'pimpinan']);
        request()->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
}
