<?php

namespace App\Http\Controllers;

use App\Services\TableTokenService;
use Illuminate\Http\Request;

/**
 * Controller untuk menangani alur pemindaian QR meja (dine-in scan).
 */
class CustomerMenuController extends Controller
{
    protected TableTokenService $tableService;

    /**
     * Dependency injection untuk TableTokenService.
     */
    public function __construct(TableTokenService $tableService)
    {
        $this->tableService = $tableService;
    }

    /**
     * Menangani pemindaian QR Code meja oleh pelanggan.
     */
    public function scan(string $token)
    {
        // Cari dan dekode meja berdasarkan token QR Crypt
        $table = $this->tableService->decode($token);

        // Jika meja tidak ditemukan / token dekripsi tidak valid
        if (!$table) {
            return response()->view('customer.invalid-table', [], 404);
        }

        // Jika meja terdaftar tetapi status is_active = false
        if (!$this->tableService->isActive($table)) {
            return response()->view('customer.invalid-table', [], 403);
        }

        // Simpan referensi nomor meja di session pelanggan
        session([
            'table_id' => (int) $table['id'],
            'table_number' => $table['table_number']
        ]);

        // Redirect ke menu utama customer
        return redirect()->route('customer.menu');
    }
}
