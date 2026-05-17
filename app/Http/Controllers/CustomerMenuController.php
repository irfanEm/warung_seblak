<?php

namespace App\Http\Controllers;

use App\Domain\Table\Models\Table;
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    public function scan(string $token)
    {
        // Cari meja berdasarkan token
        $table = Table::where('token', $token)->first();

        if (!$table || !$table->is_active) {
            abort(404, 'Kode meja tidak valid atau meja sedang tidak aktif.');
        }

        // Simpan referensi meja di session pelanggan
        session(['table_id' => $table->id]);

        // Tampilkan halaman placeholder
        return view('customer.placeholder', compact('table'));
    }
}
