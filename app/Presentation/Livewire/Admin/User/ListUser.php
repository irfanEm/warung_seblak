<?php

namespace App\Presentation\Livewire\Admin\User;

use App\Models\User;
use App\Traits\HasRoleAuthorization;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.admin')]
class ListUser extends Component
{
    use HasRoleAuthorization, WithPagination;

    public string $search = '';

    // Modal state
    public bool $showModal = false;
    public ?int $editingUserId = null;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';
    public bool $is_active = true;

    public function mount()
    {
        $this->authorizeRole('Admin');
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $userId)
    {
        $user = User::findOrFail($userId);

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = $user->getRoleNames()->first() ?? '';
        $this->is_active = $user->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $isEditing = $this->editingUserId !== null;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($isEditing ? ',' . $this->editingUserId : ''),
            'role' => 'required|string',
            'is_active' => 'boolean',
        ];

        if ($isEditing) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $this->validate($rules);

        if ($isEditing) {
            $user = User::findOrFail($this->editingUserId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->is_active = $this->is_active;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $user->syncRoles([$this->role]);
            session()->flash('success', 'Pengguna berhasil diperbarui.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => $this->is_active,
            ]);
            $user->syncRoles([$this->role]);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive(int $userId)
    {
        $user = User::findOrFail($userId);

        // Jangan menonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak dapat menonaktifkan akun sendiri.');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();
    }

    public function delete(int $userId)
    {
        $user = User::findOrFail($userId);

        // Jangan menghapus diri sendiri
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Tidak dapat menghapus akun sendiri.');
            return;
        }

        // Cek apakah ini admin terakhir
        if ($user->hasRole('Admin')) {
            $adminCount = User::role('Admin')->count();
            if ($adminCount <= 1) {
                session()->flash('error', 'Tidak dapat menghapus admin terakhir.');
                return;
            }
        }

        $user->delete();
        session()->flash('success', 'Pengguna berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get();

        $users = User::with('roles')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user.list-user', compact('users', 'roles'));
    }
}
