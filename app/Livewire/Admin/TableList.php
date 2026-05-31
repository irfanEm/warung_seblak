<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Services\TableTokenService;

#[Layout('layouts.admin')]
class TableList extends Component
{
    public array $tables = [];
    public bool $showForm = false;
    public ?int $editingTableId = null;
    public array $form = [
        'table_number' => '',
        'is_active' => true,
    ];

    public function mount()
    {
        // Coba ambil data dari session
        $sessionTables = session('admin.tables');

        if (empty($sessionTables)) {
            // Inisialisasi data dummy jika session kosong
            $dummyData = [
                ['id' => 1, 'table_number' => 'Meja 1', 'is_active' => true],
                ['id' => 2, 'table_number' => 'Meja 2', 'is_active' => true],
                ['id' => 3, 'table_number' => 'Meja 3', 'is_active' => true],
                ['id' => 4, 'table_number' => 'Meja 4', 'is_active' => true],
                ['id' => 5, 'table_number' => 'Meja VIP', 'is_active' => true],
            ];

            foreach ($dummyData as $table) {
                // Generate token menggunakan metode yang sama dengan TableTokenService
                $table['token'] = app(TableTokenService::class)->generateToken($table['id']);
                $table['qr_code_path'] = null; // QR belum digenerate
                
                $this->tables[] = $table;
            }

            session(['admin.tables' => $this->tables]);
        } else {
            $this->tables = $sessionTables;
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id)
    {
        $table = collect($this->tables)->firstWhere('id', $id);
        
        if ($table) {
            $this->editingTableId = $id;
            $this->form = [
                'table_number' => $table['table_number'],
                'is_active' => $table['is_active'],
            ];
            $this->showForm = true;
        }
    }

    public function save()
    {
        $this->validate([
            'form.table_number' => 'required|string|max:255',
            'form.is_active' => 'boolean',
        ]);

        // Validasi unik manual karena array
        $exists = collect($this->tables)
            ->where('table_number', $this->form['table_number'])
            ->where('id', '!==', $this->editingTableId)
            ->first();

        if ($exists) {
            $this->addError('form.table_number', 'Nomor meja sudah digunakan.');
            return;
        }

        if ($this->editingTableId) {
            // Update
            foreach ($this->tables as &$table) {
                if ($table['id'] === $this->editingTableId) {
                    $table['table_number'] = $this->form['table_number'];
                    $table['is_active'] = $this->form['is_active'];
                    break;
                }
            }
        } else {
            // Create
            $newId = collect($this->tables)->max('id') + 1;
            $this->tables[] = [
                'id' => $newId,
                'table_number' => $this->form['table_number'],
                'is_active' => $this->form['is_active'],
                // Token menggunakan metode yang sama dengan TableTokenService agar sinkron
                'token' => app(TableTokenService::class)->generateToken($newId),
                'qr_code_path' => null,
            ];
        }

        session(['admin.tables' => $this->tables]);
        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id)
    {
        $this->tables = array_values(array_filter($this->tables, function ($table) use ($id) {
            return $table['id'] !== $id;
        }));

        session(['admin.tables' => $this->tables]);
    }

    public function resetForm()
    {
        $this->editingTableId = null;
        $this->form = [
            'table_number' => '',
            'is_active' => true,
        ];
        $this->resetErrorBag();
    }

    public function generateQr(int $id)
    {
        foreach ($this->tables as &$table) {
            if ($table['id'] === $id) {
                // Buat URL untuk scan QR
                $url = route('customer.scan', ['token' => $table['token']]);
                
                // Generate SVG QR Code
                $svg = QrCode::format('svg')->size(300)->generate($url);
                
                // Path tujuan
                $path = 'qrcodes/table_' . $id . '.svg';
                
                // Simpan ke storage/app/public/qrcodes
                Storage::disk('public')->put($path, $svg);
                
                // Update array tables dengan path QR code
                $table['qr_code_path'] = $path;
                break;
            }
        }

        // Simpan pembaruan ke session
        session(['admin.tables' => $this->tables]);
    }

    public function render()
    {
        return view('livewire.admin.table-list');
    }
}
