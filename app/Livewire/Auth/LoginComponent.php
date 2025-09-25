<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        // Successful login
        return redirect()->intended('/dashboard'); // change to your dashboard route
    }

    public function render()
    {
        return view('livewire.auth.login-component')->layout('auth.app');
    }
}
