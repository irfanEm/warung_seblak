<?php

namespace App\Presentation\Livewire\Admin;

use App\Traits\HasRoleAuthorization;
use Livewire\Component;

class AdminDashboard extends Component
{
    use HasRoleAuthorization;

    public function mount()
    {
        // Proteksi ekstra di level komponen memastikan hanya Admin yang bisa render
        $this->authorizeRole('Admin');
    }

    public function render()
    {
        return <<<'HTML'
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Selamat datang, {{ auth()->user()->name }}. Anda login sebagai Admin.</p>
        </div>
        HTML;
    }
}
