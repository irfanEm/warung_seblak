<?php

namespace App\Presentation\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function authenticate()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            // Pengecekan Role untuk Redirect
            if ($user->hasRole('Admin')) {
                return redirect()->intended(route('admin.menu.index'));
            } elseif ($user->hasRole('Kasir')) {
                return redirect()->intended(route('pos.index'));
            } elseif ($user->hasRole('Dapur')) {
                return redirect()->intended(route('kitchen.index'));
            } elseif ($user->hasRole('Driver')) {
                return redirect()->intended(route('driver.deliveries'));
            }

            // Fallback
            return redirect('/');
        }

        session()->flash('error', 'Kredensial yang diberikan tidak cocok dengan data kami.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
