<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;

class SpicinessLevelList extends Component
{
    public array $levels = [];
    public bool $showForm = false;
    public ?int $editingLevelId = null;

    // Form inputs
    public string $name = '';
    public int|string $level = '';

    /**
     * Inisialisasi data dummy level pedas saat pertama kali dimuat.
     * TODO: Ganti dengan query Eloquent model SpicinessLevel::orderBy('level')->get() di masa mendatang.
     */
    public function mount(): void
    {
        // Ambil dari session agar data bertahan selama session aktif untuk keperluan simulasi
        if (!session()->has('admin.spiciness')) {
            session()->put('admin.spiciness', [
                ['id' => 1, 'name' => 'Tidak Pedas', 'level' => 0],
                ['id' => 2, 'name' => 'Level 1 (Sedikit Pedas)', 'level' => 1],
                ['id' => 3, 'name' => 'Level 2 (Pedas Sedang)', 'level' => 2],
                ['id' => 4, 'name' => 'Level 3 (Pedas Mantap)', 'level' => 3],
                ['id' => 5, 'name' => 'Level 4 (Sangat Pedas)', 'level' => 4],
                ['id' => 6, 'name' => 'Level 5 (Pedas Gila)', 'level' => 5],
            ]);
        }
        $this->levels = session()->get('admin.spiciness');
        $this->sortLevels();
    }

    /**
     * Membuka modal form tambah level pedas baru.
     */
    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    /**
     * Membuka modal form untuk mengedit level pedas.
     */
    public function edit(int $id): void
    {
        $this->resetForm();
        $this->editingLevelId = $id;

        foreach ($this->levels as $lvl) {
            if ($lvl['id'] === $id) {
                $this->name = $lvl['name'];
                $this->level = $lvl['level'];
                break;
            }
        }

        $this->showForm = true;
    }

    /**
     * Menyimpan data level pedas (tambah atau edit).
     * TODO: Ganti dengan penyimpanan database model SpicinessLevel::updateOrCreate()
     */
    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:50',
            'level' => 'required|integer|min:0|max:20',
        ], [
            'name.required' => 'Nama tingkat pedas wajib diisi.',
            'name.min' => 'Nama tingkat pedas minimal 2 karakter.',
            'level.required' => 'Nilai tingkatan level wajib diisi.',
            'level.integer' => 'Tingkatan level harus berupa angka bulat.',
            'level.min' => 'Tingkatan level minimal bernilai 0.',
            'level.max' => 'Tingkatan level maksimal bernilai 20.',
        ]);

        // Cek keunikan level angka
        foreach ($this->levels as $lvl) {
            if ((int) $lvl['level'] === (int) $this->level && $lvl['id'] !== $this->editingLevelId) {
                $this->addError('level', 'Tingkatan angka level ini sudah digunakan oleh tingkat pedas lain.');
                return;
            }
        }

        if ($this->editingLevelId) {
            // Proses Update
            foreach ($this->levels as &$lvl) {
                if ($lvl['id'] === $this->editingLevelId) {
                    $lvl['name'] = $this->name;
                    $lvl['level'] = (int) $this->level;
                    break;
                }
            }
        } else {
            // Proses Create
            $newId = count($this->levels) > 0 ? max(array_column($this->levels, 'id')) + 1 : 1;
            $this->levels[] = [
                'id' => $newId,
                'name' => $this->name,
                'level' => (int) $this->level,
            ];
        }

        $this->sortLevels();
        session()->put('admin.spiciness', $this->levels);
        $this->showForm = false;
        $this->resetForm();
    }

    /**
     * Menghapus level pedas dari array lokal/session.
     * TODO: Ganti dengan SpicinessLevel::destroy()
     */
    public function delete(int $id): void
    {
        $this->levels = array_filter($this->levels, fn($lvl) => $lvl['id'] !== $id);
        $this->levels = array_values($this->levels); // Reset indeks array agar terurut
        $this->sortLevels();
        session()->put('admin.spiciness', $this->levels);
    }

    /**
     * Mengurutkan level pedas berdasarkan nilai level angka secara ascending.
     */
    protected function sortLevels(): void
    {
        usort($this->levels, fn($a, $b) => $a['level'] <=> $b['level']);
    }

    /**
     * Mereset isian formulir ke kondisi kosong.
     */
    protected function resetForm(): void
    {
        $this->name = '';
        $this->level = '';
        $this->editingLevelId = null;
        $this->resetErrorBag();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.spiciness-level-list');
    }
}
