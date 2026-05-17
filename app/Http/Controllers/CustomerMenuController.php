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

        if (!$table) {
            abort(404, 'Kode meja tidak valid.');
        }

        // Simpan referensi meja di session pelanggan
        session(['table_id' => $table->id]);

        // Redirect ke menu customer
        return redirect()->route('customer.menu');
    }
}
