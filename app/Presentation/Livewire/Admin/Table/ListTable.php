<?php

namespace App\Presentation\Livewire\Admin\Table;

use App\Application\Actions\Table\DeleteTableAction;
use App\Domain\Table\Models\Table;
use Hashids\Hashids;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ListTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($tableId, DeleteTableAction $action)
    {
        $action->execute($tableId);
        session()->flash('message', 'Meja berhasil dihapus!');
        session()->flash('message_type', 'success');
        
        $this->resetPage();
        $this->dispatch('table-deleted'); // Alpine modal listener
    }

    public function generateQr($tableId)
    {
        try {
            $table = Table::findOrFail($tableId);
            
            // 1 & 3. Pastikan token ada, generate jika kosong
            if (empty($table->token)) {
                $hashids = new Hashids(config('app.key'), 10);
                $table->token = $hashids->encode($table->id);
                $table->save(); 
            }

            // 2. Buat direktori jika belum ada
            Storage::disk('public')->makeDirectory('qrcodes/tables');
            
            $path = "qrcodes/tables/{$table->token}.png";
            $absolutePath = Storage::disk('public')->path($path);
            $url = route('customer.scan', $table->token);

            // Generate gambar QR Code langsung ke path absolut
            QrCode::size(300)->format('png')->generate($url, $absolutePath);

            // 5. Cek apakah file benar-benar terbuat
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File QR Code gagal ditulis ke direktori penyimpanan.");
            }

            // 4. Update path di database
            $table->qr_code_image_path = $path;
            $table->save();
            
            session()->flash('message', 'QR Code berhasil di-generate!');
            session()->flash('message_type', 'success');
        } catch (\Exception $e) {
            // 4. Logging error detail
            \Illuminate\Support\Facades\Log::error('QR Generate Error: ' . $e->getMessage());
            session()->flash('message', 'Gagal men-generate QR Code. Silakan coba lagi.');
            session()->flash('message_type', 'error');
        }
    }

    public function regenerateToken($tableId)
    {
        try {
            $table = Table::findOrFail($tableId);
            
            if ($table->qr_code_image_path && Storage::disk('public')->exists($table->qr_code_image_path)) {
                Storage::disk('public')->delete($table->qr_code_image_path);
            }

            $hashids = new Hashids(config('app.key'), 10);
            $table->token = $hashids->encode($table->id . time());
            $table->save();

            $this->generateQr($tableId); // Reuse logika di atas
            session()->flash('message', 'Token & QR Code berhasil di-regenerate!');
            session()->flash('message_type', 'success');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('QR Regenerate Error: ' . $e->getMessage());
            session()->flash('message', 'Gagal men-generate ulang QR Code.');
            session()->flash('message_type', 'error');
        }
    }



    public function render()
    {
        $tables = Table::with('outlet')
            ->when($this->search, function ($query) {
                $query->where('table_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('table_number', 'asc')
            ->paginate(12);

        return view('livewire.admin.table.list-table', compact('tables'))->layout('layouts.admin');
    }
}
