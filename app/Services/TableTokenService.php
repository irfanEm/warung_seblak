<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * Service mock sementara untuk mengelola token meja QR.
 * Menggunakan enkripsi simetris Laravel dengan pembungkus URL-safe Base64.
 * Akan diganti dengan integrasi database Eloquent di masa depan.
 */
class TableTokenService
{
    // Data meja dummy
    private static array $tables = [
        ['id' => 1, 'table_number' => 'Meja 1', 'is_active' => true],
        ['id' => 2, 'table_number' => 'Meja 2', 'is_active' => true],
        ['id' => 3, 'table_number' => 'Meja 3', 'is_active' => false], // Meja tidak aktif
        ['id' => 4, 'table_number' => 'Meja VIP', 'is_active' => true],
    ];

    /**
     * Mendekripsi token meja dan mengembalikan data meja lengkap.
     * Mendukung dekripsi dari format URL-safe Base64.
     */
    public function decode(string $token): ?array
    {
        try {
            // Balikkan format URL-safe Base64 ke Base64 standar
            $base64 = str_replace(['-', '_'], ['+', '/'], $token);
            
            // Tambahkan padding '=' jika panjangnya tidak kelipatan 4
            $padding = strlen($base64) % 4;
            if ($padding) {
                $base64 .= str_repeat('=', 4 - $padding);
            }
            
            // Decode Base64 lalu dekripsi string menggunakan Crypt Laravel
            $decrypted = Crypt::decryptString(base64_decode($base64));
            $id = (int)$decrypted;
            
            // Cari data meja berdasarkan ID hasil dekripsi
            return $this->getTableById($id);
        } catch (\Exception $e) {
            // Menangkap DecryptException dan kesalahan encoding lainnya agar tidak crash
            return null;
        }
    }

    /**
     * Mendapatkan data meja berdasarkan ID.
     */
    public function getTableById(int $id): ?array
    {
        foreach (self::$tables as $table) {
            if ($table['id'] === $id) {
                // Pasang token dinamis saat diambil agar selalu valid sesuai APP_KEY terkini
                $table['token'] = $this->generateToken($table['id']);
                return $table;
            }
        }
        return null;
    }

    /**
     * Memeriksa apakah meja dalam kondisi aktif.
     */
    public function isActive(array $table): bool
    {
        return isset($table['is_active']) && $table['is_active'] === true;
    }

    /**
     * Membuat token enkripsi URL-safe untuk ID meja tertentu.
     */
    public function generateToken(int $tableId): string
    {
        $encrypted = Crypt::encryptString((string)$tableId);
        
        // Konversi ke Base64 standar lalu ubah menjadi URL-safe (hapus +, /, dan =)
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
    }

    /**
     * Mendapatkan semua daftar meja lengkap dengan token aktifnya.
     */
    public function getTables(): array
    {
        return array_map(function($table) {
            $table['token'] = $this->generateToken($table['id']);
            return $table;
        }, self::$tables);
    }
}
