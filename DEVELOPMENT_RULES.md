# DEVELOPMENT RULES — Warung Seblak Ibun Balqis

> **Versi:** 2.0  
> **Update Terakhir:** 14 Juni 2026  
> **Target Pembaca:** AI Coding Agent (Qoder, Antigravity, Cursor) & Developer Manusia  
> **Instruksi Kunci:** File ini adalah **panduan utama** sebelum memulai tugas pengembangan apa pun.  
> **Wajib dibaca dan dipahami** sebelum menulis kode.

---

## DAFTAR ISI

1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Stack Teknologi & Versi](#2-stack-teknologi--versi)
3. [Arsitektur Sistem](#3-arsitektur-sistem)
4. [Struktur Direktori](#4-struktur-direktori)
5. [Aturan Kode Wajib](#5-aturan-kode-wajib)
6. [Representasi Uang (KRITIS)](#6-representasi-uang-kritis)
7. [Keamanan](#7-keamanan)
8. [State Management & Session](#8-state-management--session)
9. [Alur Bisnis Utama](#9-alur-bisnis-utama)
10. [Daftar Model & Relasi](#10-daftar-model--relasi)
11. [Daftar Rute](#11-daftar-rute)
12. [Daftar Komponen Livewire](#12-daftar-komponen-livewire)
13. [Konvensi Penamaan](#13-konvensi-penamaan)
14. [Panduan Membuat Fitur Baru](#14-panduan-membuat-fitur-baru)
15. [Larangan & Pantangan](#15-larangan--pantangan)
16. [Daftar Helper & Utility](#16-daftar-helper--utility)
17. [Daftar Middleware](#17-daftar-middleware)

---

## 1. Ringkasan Proyek

**Warung Seblak Ibun Balqis** adalah sistem pemesanan dan manajemen restoran digital untuk warung seblak di Bandung. Sistem ini memungkinkan:

- **Pelanggan** memindai QR code di meja, melihat menu, menyesuaikan pesanan (topping, level pedas), dan membayar online via Midtrans atau di kasir.
- **Kasir** melayani pembeli walk-in melalui Point of Sale (POS).
- **Staf Dapur** melihat pesanan masuk secara real-time di Kitchen Display System (KDS) dan memperbarui status pesanan.
- **Admin** mengelola menu, meja, pengguna, dan melihat laporan.

**Status Proyek:** 55-60% siap produksi. Core ordering flow berfungsi. Delivery, promos, dan laporan lanjutan masih dalam pengembangan.

---

## 2. Stack Teknologi & Versi

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Bahasa | PHP | ^8.3 |
| Framework | Laravel | ^13.x |
| Frontend Interaktif | Livewire | ^4.x |
| JavaScript Utility | Alpine.js | ^3.x |
| CSS Framework | Tailwind CSS | ^4.x |
| Database | PostgreSQL | - |
| Queue | Database (development) | - |
| Pembayaran | Midtrans (Snap) | ^2.6 |
| PDF | barryvdh/laravel-dompdf | ^3.x |
| QR Code | simplesoftwareio/simple-qrcode | ^4.x |
| Gambar | intervention/image | ^2.7 |
| Izin Akses | spatie/laravel-permission | ^7.x |
| Hash ID | hashids/hashids | ^5.x |
| API Auth | Laravel Sanctum | ^4.x |

---

## 3. Arsitektur Sistem

### Pola Arsitektur
**Layered Architecture + Action Classes** (Clean Architecture-lite).

```
Routes → Livewire Components (Presentation)
           → Actions (Application)
              → Models (Domain)
                 → Eloquent (Infrastructure)
```

### Penjelasan Layer

| Layer | Direktori | Tanggung Jawab | Contoh |
|-------|-----------|----------------|--------|
| **Presentation** | `app/Presentation/Livewire/` | Menangani UI, interaksi pengguna, validasi input, state management. | `CustomerMenu.php`, `PosScreen.php` |
| **Application** | `app/Application/Actions/` | Logika bisnis use-case spesifik. Satu class = satu aksi. | `PlaceOrderAction.php`, `CreateMenuAction.php` |
| **Domain** | `app/Domain/Models/` | Model Eloquent, relasi, casting. | `Order.php`, `Menu.php` |
| **Infrastructure** | `app/Infrastructure/` | Integrasi eksternal, repository, payment gateway, jobs. | `MidtransGateway.php` |

### Prinsip Penting
- **Presentation tidak boleh langsung query database kompleks.** Gunakan Action atau Model scope.
- **Actions bersifat stateless.** Terima input → proses → return output.
- **Tidak menggunakan Repository Pattern** (ditinggalkan setengah jalan). Gunakan Eloquent Model langsung.
- **DTO belum diterapkan.** Direktori `app/Application/DTOs/` kosong. Jangan membuat DTO baru tanpa diskusi.

---

## 4. Struktur Direktori

```
app/
├── Application/
│   ├── Actions/
│   │   ├── Menu/          # ListMenusAction, CreateMenuAction, UpdateMenuAction, DeleteMenuAction
│   │   ├── Order/         # PlaceOrderAction
│   │   └── Table/         # CreateTableAction, UpdateTableAction, DeleteTableAction
│   └── DTOs/              # Kosong — JANGAN digunakan
├── Domain/
│   └── Models/
│       ├── Outlet.php
│       ├── Menu.php
│       ├── Category.php
│       ├── Topping.php
│       ├── SpicinessLevel.php
│       ├── Table.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── OrderItemTopping.php
│       └── DeliverySetting.php
├── Infrastructure/
│   ├── Payment/
│   │   └── MidtransGateway.php    # Static methods — perlu refactor ke interface
│   └── Jobs/
│       └── ProcessNewOrder.php    # Belum di-dispatch
├── Presentation/
│   └── Livewire/
│       ├── Admin/
│       │   ├── AdminDashboard.php
│       │   ├── Menu/              # ListMenu, MenuForm
│       │   ├── Table/             # ListTable, TableForm
│       │   └── User/              # ListUser (jika sudah dibuat)
│       ├── Customer/
│       │   ├── CustomerMenu.php
│       │   ├── Cart.php
│       │   ├── CartBadge.php
│       │   ├── Checkout.php
│       │   ├── Payment.php
│       │   ├── PaymentCallback.php
│       │   ├── OrderLookup.php
│       │   └── OrderTracking.php
│       ├── Pos/
│       │   ├── PosScreen.php
│       │   └── PosHistory.php
│       ├── Kitchen/
│       │   └── KitchenDisplay.php
│       └── Auth/
│           └── Login.php
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── PaymentNotificationController.php
│   │   ├── CustomerMenuController.php
│   │   └── TablePrintController.php
│   └── Middleware/
│       ├── ContentSecurityPolicy.php
│       ├── SanitizeInput.php
│       └── WhitelistMidtransIP.php
├── helpers.php            # formatRupiah(), generateTrackingCode()
└── Traits/
    └── HasRoleAuthorization.php

resources/views/
├── layouts/
│   ├── customer.blade.php    # Mobile-first, bottom nav
│   ├── admin.blade.php       # Sidebar responsive
│   ├── kitchen.blade.php     # Full screen dark theme
│   └── guest.blade.php       # Login page
├── livewire/
│   ├── admin/                # View untuk komponen Admin
│   ├── customer/             # View untuk komponen Customer
│   ├── pos/                  # View untuk POS
│   ├── kitchen/              # View untuk Kitchen
│   └── auth/                 # View untuk Login
├── components/
│   └── icons/                # Blade icon components (SVG)
├── customer/                 # Halaman statis customer
├── admin/tables/             # Print label view
└── pos/                      # Receipt view

routes/
├── web.php    # Rute web
└── api.php    # Rute API (webhook Midtrans)

database/
├── migrations/
└── seeders/   # OutletSeeder, UserSeeder, MenuSeeder, dll.
```

---

## 5. Aturan Kode Wajib

### PHP
- **`declare(strict_types=1);`** di **setiap** file PHP.
- Gunakan **PHP 8.3 typed properties** di semua kelas.
- **Nama kelas & method** dalam Bahasa Inggris deskriptif, contoh: `PlaceOrderAction`, `formatRupiah`.
- **Komentar** untuk menjelaskan **kenapa (why)**, bukan apa (what). Gunakan `// TODO: deskripsi` untuk penanda pekerjaan belum selesai.
- Setiap method maksimal **30 baris** (usahakan), jika lebih, pecah.
- Hindari **magic numbers** — gunakan konstanta atau properti kelas.

### Livewire
- Semua komponen Livewire menggunakan namespace **`App\Presentation\Livewire`**.
- Nama komponen: **PascalCase** ( `CustomerMenu`, `PosScreen`).
- View komponen: **kebab-case** ( `customer-menu.blade.php`, `pos-screen.blade.php`).
- Gunakan `#[Layout('layouts.nama_layout')]` untuk layout.
- Gunakan `wire:poll.5s` untuk auto-refresh di KDS dan tracking.
- Gunakan `wire:key` pada setiap item dalam loop.

### Blade
- Layout customer: **mobile-first**, `max-w-md mx-auto`, bottom navigation fixed.
- Layout admin: sidebar + konten, responsive collapse.
- Semua teks user-facing dalam **Bahasa Indonesia**.
- Gunakan komponen `<x-icons.nama />` untuk ikon, jangan embed SVG langsung.
- Setiap `@foreach` harus punya `wire:key` atau `key` unik.

### Tailwind CSS
- Gunakan **mobile-first breakpoints**: `sm:`, `md:`, `lg:`.
- Warna aksen utama: **`orange-500` / `orange-600`**.
- Tombol interaktif: **minimal 44x44px** (`min-h-[44px] min-w-[44px]`).
- Modal: `z-50` agar di atas elemen lain.

---

## 6. Representasi Uang (KRITIS)

**Aturan Paling Penting:** Semua nilai uang di database, model, dan logika bisnis disimpan dalam **satuan sen (Rupiah × 100)** sebagai **integer**.

### Mengapa?
Aritmatika float (desimal) menyebabkan kesalahan pembulatan, misal `0.10 + 0.20 = 0.30000000000000004`. Tidak dapat diterima dalam perhitungan keuangan.

### Aturan:
- **Database:** Semua kolom uang menggunakan tipe `bigint` (menyimpan nilai dalam sen).
- **Model Casting:** `'price' => 'integer'`, `'subtotal' => 'integer'`, `'total' => 'integer'`, dst.
- **Tampilan:** Gunakan helper `formatRupiah(int $amount): string` dari `app/helpers.php`.
- **Input dari Pengguna (Rupiah → Sen):** Konversi dengan `(int) round($input * 100)`.
- **Semua operasi matematika uang (+, -, *, /) harus dalam integer.**

### Helper Tersedia
```php
// app/helpers.php
function formatRupiah(int $amount): string
{
    return 'Rp ' . number_format($amount / 100, 0, ',', '.');
}
```

### JANGAN PERNAH:
- Menyimpan uang sebagai `float` atau `decimal` di model casting.
- Menggunakan `number_format()` langsung di view — selalu lewat helper.
- Membagi atau mengali dengan 100 di sembarang tempat — konversi hanya di batas input/output.

---

## 7. Keamanan

### Kode Pesanan Publik
- **`tracking_code`** (8 karakter acak, unik) digunakan untuk semua interaksi publik.
- **`order_number`** (format `INV-YYYYMMDD-XXXX`) hanya untuk internal (admin, laporan).
- Rute publik hanya menerima `tracking_code`, bukan `order_number`.
- Helper: `generateTrackingCode()` di `app/helpers.php`.

### Rate Limiting
- **Login:** 5 percobaan per menit per IP (middleware throttle + RateLimiter di `Login.php`).
- **Webhook Midtrans:** 10 request per menit per IP (middleware throttle di `api.php`).

### Webhook Midtrans
- Dilindungi oleh:
  1. **Signature verification** (hash SHA-512 dari order_id + status_code + gross_amount + server_key).
  2. **IP Whitelist** (middleware `WhitelistMidtransIP`).
  3. **Rate limiting** (throttle).

### Input Sanitasi
- Middleware `SanitizeInput` otomatis `strip_tags()` semua input string.
- Field yang dikecualikan (misal `description`) bisa ditambahkan di `$except`.

### CSP Header
- Middleware `ContentSecurityPolicy` menambahkan header Content-Security-Policy.
- Dinamis untuk Midtrans sandbox/production.

### Session
- `SESSION_HTTP_ONLY=true` (cookie tidak bisa dibaca JavaScript).
- `SESSION_SAME_SITE=lax` (kompatibel dengan redirect Midtrans).
- `SESSION_SECURE_COOKIE=true` di production.

---

## 8. State Management & Session

### Key Session yang Digunakan

| Key | Konten | Tempat Digunakan |
|-----|--------|------------------|
| `cart` | Array item keranjang (menu_id, name, price, qty, toppings, spiciness, subtotal) | Customer ordering flow |
| `table_id` | ID meja yang dipindai | Setelah scan QR |
| `admin.tables` | Data dummy meja (admin) | Halaman print label |
| `admin.orders` | Data dummy pesanan (admin) | Dashboard & Order List |
| `admin.menus` | Data dummy menu (admin) | Admin Menu List |
| `admin.toppings` | Data dummy topping (admin) | Admin Topping List |
| `admin.spiciness` | Data dummy level pedas (admin) | Admin Spiciness List |
| `admin.categories` | Data dummy kategori (admin) | Admin Category List |

### Aturan Session
- **Keranjang (`cart`):** Dihapus setelah transaksi selesai.
  - **Pembayaran Online:** Di `PaymentNotificationController` setelah webhook sukses.
  - **Pembayaran Offline/Kasir:** Di `PosScreen` setelah konfirmasi pembayaran.
  - **Self-Service Dine-in (Bayar di Kasir):** Di `PlaceOrderAction` setelah order dibuat.
- **Jangan hapus keranjang sebelum waktunya!** Pelanggan bisa kehilangan pesanan.

---

## 9. Alur Bisnis Utama

### A. Dine-in via QR (Pelanggan Self-Service)
1. Pelanggan scan QR di meja → akses `/menu/{token}`.
2. Token didekripsi oleh `TableTokenService` → `table_id` disimpan di session.
3. Pelanggan diarahkan ke halaman menu (`customer.menu`).
4. Pelanggan memilih menu, topping, level pedas, kuantitas → tambah ke keranjang.
5. Keranjang disimpan di `session('cart')`.
6. Pelanggan checkout:
   - **Bayar Online:** Midtrans Snap popup → webhook update status ke `paid`.
   - **Bayar di Kasir:** Pesanan dibuat dengan status `pending`.
7. Keranjang dihapus setelah transaksi selesai.

### B. POS (Kasir)
1. Kasir login → akses `/pos`.
2. Pilih tipe: Dine-in (pilih meja) atau Takeaway.
3. Pilih menu, topping, pedas → tambah ke keranjang POS.
4. Checkout:
   - **Tunai:** Input uang diterima, sistem hitung kembalian.
   - **Non-tunai:** QRIS via Midtrans.
5. Konfirmasi → order tersimpan dengan status `paid` (tunai) atau `payment_pending` (non-tunai).
6. Cetak struk PDF (jika diperlukan).
7. Keranjang POS dihapus.

### C. Dapur (KDS)
1. Staf dapur login → akses `/kitchen`.
2. Layar auto-refresh `wire:poll.5s`.
3. Menampilkan pesanan dengan status: `paid`, `confirmed`, `preparing`, `ready`.
4. Kartu pesanan berubah warna berdasarkan waktu tunggu:
   - < 5 menit: Putih
   - 5-15 menit: Kuning
   - > 15 menit: Merah
5. Staf mengubah status:
   - **Terima** → `preparing`
   - **Siap** → `ready`
   - **Selesai** → `completed`

### D. Admin
1. Dashboard: Lihat metrik hari ini.
2. Kelola menu, kategori, topping, level pedas.
3. Kelola meja, generate QR, cetak label.
4. Kelola pengguna (admin, kasir, dapur).
5. Lihat daftar pesanan, ubah status.

---

## 10. Daftar Model & Relasi

| Model | Tabel | Kolom Penting |
|-------|-------|---------------|
| `Outlet` | `outlets` | `id`, `name`, `tax_rate` |
| `Menu` | `menus` | `id`, `outlet_id`, `category_id`, `name`, `price` (int/sen), `is_available`, `stock_quantity` |
| `Category` | `menu_categories` | `id`, `name`, `slug`, `outlet_id` |
| `Topping` | `toppings` | `id`, `name`, `price` (int/sen), `outlet_id` |
| `SpicinessLevel` | `spiciness_levels` | `id`, `name`, `level` |
| `Table` | `tables` | `id`, `outlet_id`, `table_number`, `token`, `is_active`, `qr_code_image_path` |
| `Order` | `orders` | `id`, `order_number`, `tracking_code`, `outlet_id`, `type`, `table_id`, `status`, `subtotal`, `tax`, `delivery_fee`, `discount`, `total`, `customer_name`, `customer_phone`, `midtrans_transaction_id`, `snap_token` |
| `OrderItem` | `order_items` | `id`, `order_id`, `menu_id`, `item_name_snapshot`, `price`, `quantity`, `subtotal`, `spiciness_level_id` |
| `OrderItemTopping` | `order_item_toppings` | `id`, `order_item_id`, `topping_id`, `topping_name`, `price` |
| `DeliverySetting` | `delivery_settings` | `id`, `outlet_id`, `base_rate_per_km`, `minimum_charge`, `free_delivery_min_order`, `max_delivery_distance` |

### Relasi Penting
- `Outlet` has many: `Menu`, `Category`, `Topping`, `Table`, `Order`.
- `Menu` belongs to: `Category`. Belongs to many: `Topping`, `SpicinessLevel` (via pivot).
- `Order` has many: `OrderItem`.
- `OrderItem` belongs to: `Order`, `Menu`. Has many: `OrderItemTopping`.
- `OrderItemTopping` belongs to: `OrderItem`, `Topping`.

### Catatan Model
- Semua kolom moneter menggunakan **integer casting**.
- `Order.status`: enum string (pending, payment_pending, paid, confirmed, preparing, ready, completed, cancelled).
- `Order.type`: enum string (dine_in, takeaway, delivery, pos).
- `Table.token`: dienkripsi dengan `Crypt::encryptString` + URL-safe Base64.

---

## 11. Daftar Rute

### Rute Publik (Customer)
| URL | Nama | Handler | Deskripsi |
|-----|------|---------|-----------|
| `/` | - | Redirect → `/menu` | Landing page |
| `/menu` | `customer.menu` | View `customer.menu` | Halaman menu utama |
| `/menu/{token}` | `customer.scan` | `CustomerMenuController@scan` | Scan QR meja |
| `/checkout` | `customer.checkout` | `Checkout` Livewire | Halaman checkout |
| `/payment/{trackingCode}` | `customer.payment` | `Payment` Livewire | Halaman pembayaran |
| `/payment/callback` | `customer.payment.callback` | `PaymentCallback` Livewire | Callback Midtrans |
| `/order/tracking/{trackingCode}` | `customer.order.tracking` | `OrderTracking` Livewire | Pelacakan pesanan |
| `/orders` | `customer.orders` | `OrderLookup` Livewire | Form cari pesanan |

### Rute Admin
| URL | Nama | Handler | Middleware |
|-----|------|---------|------------|
| `/admin/dashboard` | `admin.dashboard` | `AdminDashboard` | `role:Admin` |
| `/admin/menus` | `admin.menus.index` | `ListMenu` | `role:Admin` |
| `/admin/categories` | `admin.categories.index` | `CategoryList` | `role:Admin` |
| `/admin/toppings` | `admin.toppings.index` | `ToppingList` | `role:Admin` |
| `/admin/spiciness` | `admin.spiciness.index` | `SpicinessLevelList` | `role:Admin` |
| `/admin/tables` | `admin.tables.index` | `ListTable` | `role:Admin` |
| `/admin/tables/{id}/print` | `admin.tables.print` | Closure → View Print | `role:Admin` |
| `/admin/orders` | `admin.orders.index` | `OrderList` | `role:Admin,Kasir` |
| `/admin/users` | `admin.users.index` | `ListUser` | `role:Admin` |
| `/pos` | `pos.index` | `PosScreen` | `role:Admin,Kasir` |
| `/pos/history` | `pos.history` | `PosHistory` | `role:Admin,Kasir` |
| `/pos/receipt/{orderId}` | `pos.receipt` | Closure → PDF | `role:Admin,Kasir` |
| `/kitchen` | `kitchen.index` | `KitchenDisplay` | `role:Admin,Dapur` |

### Rute API
| URL | Nama | Handler | Middleware |
|-----|------|---------|------------|
| `/api/payment/notification` | `payment.notification` | `PaymentNotificationController` | `throttle:10,1`, `whitelist.midtrans.ip` |

---

## 12. Daftar Komponen Livewire

### Customer
- `CustomerMenu` — Grid menu, filter kategori, add to cart.
- `Cart` — Manajemen keranjang (session).
- `CartBadge` — Badge jumlah item di bottom nav.
- `Checkout` — Ringkasan pesanan, form checkout.
- `Payment` — Integrasi Midtrans Snap.
- `PaymentCallback` — Halaman setelah pembayaran.
- `OrderLookup` — Form pencarian pesanan.
- `OrderTracking` — Pelacakan status pesanan real-time.

### Admin
- `AdminDashboard` — Metrik & ringkasan.
- `ListMenu` / `MenuForm` — CRUD menu.
- `CategoryList` — CRUD kategori.
- `ToppingList` — CRUD topping.
- `SpicinessLevelList` — CRUD level pedas.
- `ListTable` / `TableForm` — CRUD meja + QR.
- `OrderList` — Daftar pesanan + ubah status.
- `ListUser` — CRUD pengguna (jika sudah dibuat).

### POS
- `PosScreen` — Layar POS utama.
- `PosHistory` — Riwayat transaksi POS.

### Kitchen
- `KitchenDisplay` — Tampilan dapur (KDS).

### Auth
- `Login` — Login & redirect berdasarkan peran.

---

## 13. Konvensi Penamaan

| Elemen | Pola | Contoh |
|--------|------|--------|
| Livewire Component Class | PascalCase | `CustomerMenu`, `PosScreen` |
| Livewire View | kebab-case | `customer-menu.blade.php` |
| Action Class | PascalCase + `Action` | `PlaceOrderAction`, `CreateMenuAction` |
| Model Class | PascalCase singular | `Order`, `OrderItem` |
| Tabel Database | snake_case plural | `orders`, `order_items` |
| Kolom Foreign Key | `nama_model_id` | `outlet_id`, `category_id` |
| Route Name | `modul.aksi` | `admin.menus.index`, `customer.scan` |
| Blade Icon Component | `<x-icons.nama />` | `<x-icons.home />`, `<x-icons.trash />` |
| Helper Function | camelCase | `formatRupiah()`, `generateTrackingCode()` |
| Session Key | snake_case | `table_id`, `admin.tables` |
| Method Livewire | camelCase | `addToCart()`, `removeItem()` |

---

## 14. Panduan Membuat Fitur Baru

1. **Baca file ini** dari awal sampai akhir sebagai konteks.
2. Pahami di layer mana kode akan ditulis:
   - **UI/Interaksi** → Livewire Component di `Presentation`.
   - **Logika Bisnis** → Action class di `Application`.
   - **Data** → Model di `Domain`.
3. Ikuti **semua aturan kode** di bagian 5.
4. Jika perlu database, buat migration dengan `php artisan make:migration`.
5. Daftarkan rute baru di `routes/web.php` atau `api.php`.
6. Jika membuat komponen Livewire baru, gunakan namespace `App\Presentation\Livewire`.
7. Jika menambah helper, masukkan ke `app/helpers.php`.
8. Verifikasi dengan:
   ```bash
   php -l app/NamaFile.php
   php artisan view:cache
   ```
9. Tandai bagian yang belum selesai dengan `// TODO: deskripsi`.

---

## 15. Larangan & Pantangan

### JANGAN PERNAH:
- ❌ Menyimpan uang sebagai float.
- ❌ Menggunakan `number_format()` langsung di view (pakai `formatRupiah()`).
- ❌ Mengekspos `order_number` ke publik (pakai `tracking_code`).
- ❌ Menghapus session `cart` sebelum transaksi benar-benar selesai.
- ❌ Membuat komponen Livewire di luar namespace `App\Presentation\Livewire`.
- ❌ Menggunakan jQuery untuk logika baru (pakai Alpine.js).
- ❌ Hardcode `outlet_id = 1` (gunakan `auth()->user()->outlet_id ?? 1` atau fallback).
- ❌ Commit file `.env` atau kredensial.
- ❌ Mengabaikan tap target 44px untuk tombol di mobile.
- ❌ Mengabaikan verifikasi signature Midtrans.

---

## 16. Daftar Helper & Utility

| Helper | Lokasi | Fungsi |
|--------|--------|--------|
| `formatRupiah(int $amount): string` | `app/helpers.php` | Format uang dari sen ke Rupiah |
| `generateTrackingCode(): string` | `app/helpers.php` | Generate kode pelacakan unik 8 karakter |

---

## 17. Daftar Middleware

| Middleware | Fungsi | Terdaftar di |
|------------|--------|-------------|
| `ContentSecurityPolicy` | Menambahkan header CSP + keamanan | Grup `web` |
| `SanitizeInput` | Membersihkan input dari HTML | Grup `web` |
| `WhitelistMidtransIP` | Membatasi akses webhook ke IP Midtrans | Rute `api.php` |
| `throttle` | Rate limiting (bawaan Laravel) | Rute login & webhook |
| `role` | Spatie role middleware | Grup admin |

