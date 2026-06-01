# RBAC Implementation Walkthrough

Semua pengaturan RBAC (Role-Based Access Control) telah berhasil diimplementasikan ke dalam project **Warung Seblak Digital** menggunakan library `spatie/laravel-permission` dengan pendekatan *Clean Architecture*.

## 1. Class Constants `Permissions`
Semua *permission* telah didaftarkan dalam `App\Enums\Permissions.php` untuk meminimalisasi salah ketik (*typo*) saat mengecek otorisasi, misal menggunakan `Permissions::MANAGE_MENUS` alih-alih mengetik string statis `'manage menus'`.

## 2. Default Users, Roles, & Permissions Seeded
Skrip `RoleAndPermissionSeeder` berhasil dieksekusi selama proses `php artisan migrate:fresh --seed`. Proses seeder ini mencakup:
- Pembuatan *permissions* untuk 4 level otorisasi: `Admin`, `Kasir`, `Dapur`, dan `Driver`.
- Pemberian hak akses secara spesifik ke masing-masing *role*.
- Penciptaan 4 *user default* yang bisa langsung digunakan untuk mencoba fungsi log-in:
  - `admin@warungseblak.id` (Role: Admin)
  - `kasir@warungseblak.id` (Role: Kasir)
  - `dapur@warungseblak.id` (Role: Dapur)
  - `driver@warungseblak.id` (Role: Driver)
  *(Password untuk semua user adalah `password123`)*

## 3. Route Protection
Di file `routes/web.php`, *group routes* telah dirancang ulang menggunakan middleware bawaan Spatie: `['auth', 'role:Admin']`, `['auth', 'role:Admin|Kasir']`, dsb. 
Hal ini menjamin bahwa tidak ada pengunjung (atau *user* dengan hak akses lebih rendah) yang dapat memotong jalan (*bypass*) ke halaman manajemen milik *role* di atasnya.

## 4. Livewire Components
Trait tambahan `App\Traits\HasRoleAuthorization` telah dibuat untuk keperluan cek otorisasi per-method atau pada fase inisialisasi di *Livewire Component*.
Sebagai contoh, komponen `AdminDashboard` sekarang telah dilengkapi proteksi tingkat komponen menggunakan trait ini di dalam *method* `mount()`.

## Status Database
Basis data PostgreSQL Anda saat ini *up-to-date* dan berisi seluruh gabungan data model *Core Domain* dari tugas sebelumnya beserta pengguna dan level otoritas (*RBAC*) dari tugas ini.
