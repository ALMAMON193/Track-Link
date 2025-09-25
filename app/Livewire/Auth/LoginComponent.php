<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            return redirect()->intended('/dashboard');
        }
        session()->flash('error', 'Invalid credentials');
    }

    public function render()
    {
        return view('livewire.auth.login-component')->layout('auth.app');
    }
}
