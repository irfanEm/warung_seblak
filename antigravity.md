```text
# Aplikasi Warung Seblak Digital — Aturan Pengembangan (AI Coding Rules)

> **Tujuan** : Membangun aplikasi web production‑grade untuk operasional warung seblak dengan fitur pemesanan dine‑in via QR, delivery order, POS/kasir, manajemen menu & meja, integrasi pembayaran, dan dashboard admin.

---

## 1. Arsitektur Umum

- **Pola** : Modular Monolith dengan prinsip Clean Architecture.
- **Lapisan**:
  - **Domain** : Model Eloquent, Repository interfaces, Value Objects, Domain Services, Event.
  - **Application** : Action classes, DTO, Service classes (coordinator).
  - **Infrastructure** : Repository implementations, payment gateways, mapping adapters, queue jobs.
  - **Presentation** : Livewire Components, Controllers, Blade views.
- **Alasan** : Memisahkan logika bisnis dari framework, memudahkan testing, dan memungkinkan perubahan frontend di masa depan tanpa merombak inti.

---

## 2. Teknologi & Versi

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Backend | Laravel | 11.x |
| Database | MySQL | 8.0 |
| Frontend interaktif | Livewire | 3.x |
| Interaksi dinamis | Alpine.js | 3.x |
| CSS framework | Tailwind CSS | 3.x |
| DOM manipulation (legacy) | jQuery | 3.x (hanya jika benar-benar diperlukan) |
| Package manager | npm / Vite | |
| Payment Gateway | Midtrans (library `midtrans/midtrans-php`) | terbaru |

---

## 3. Struktur Folder Laravel (Direktori `app/`)

```
app/
├── Domain/
│   ├── Menu/
│   │   ├── Models/ (Menu, Category, Topping, SpicinessLevel, ...)
│   │   ├── Repositories/ (MenuRepositoryInterface, ...)
│   │   └── Services/ (MenuAvailabilityService)
│   ├── Order/
│   │   ├── Models/ (Order, OrderItem, ...)
│   │   ├── Events/ (OrderPlaced, OrderPaid)
│   │   └── Listeners/ (NotifyKitchen, UpdateTableStatus, UpdateStock)
│   ├── Table/ (Models/Table, Repositories/...)
│   ├── Delivery/
│   │   ├── Services/ (DistanceCalculatorInterface)
│   │   └── ValueObjects/ (DeliveryFee)
│   └── Promo/ (Models/Promo)
├── Application/
│   ├── Actions/ (PlaceOrderAction, CalculateDeliveryFeeAction, AssignDriverAction, ...)
│   └── DTOs/ (OrderData, CartItemData, ...)
├── Infrastructure/
│   ├── Persistence/Eloquent/ (MenuRepository, OrderRepository, ...)
│   ├── Payment/ (MidtransGateway implements PaymentGatewayInterface)
│   ├── Mapping/ (GoogleMapsDistanceCalculator, OsrmDistanceCalculator)
│   └── Jobs/ (ProcessPaymentNotification, SendOrderToKitchen, ...)
└── Livewire/
    ├── Customer/ (MenuList, MenuDetail, Cart, Checkout, OrderTracking)
    ├── Admin/ (Menu/CategoryList, Menu/MenuList, Table/TableList, Order/OrderList, Dashboard)
    ├── Pos/ (PosScreen, PosCart)
    └── Kitchen/ (KitchenBoard)
```

- **Livewire Components** menggunakan namespace `App\Livewire`.
- **Domain Models** tetap di `App\Domain\*`.
- **Controller** untuk API / webhook diletakkan di `App\Http\Controllers` (mis. `PaymentNotificationController`).

---

## 4. Pola Desain & Aturan Kode

### Service Layer
- Kelas koordinator yang menggabungkan beberapa Action dan Repository.
- Contoh: `OrderService::placeOrder(OrderData $data)` → memvalidasi stok, menghitung total, membuat order, memproses pembayaran.
- Jangan letakkan logika bisnis langsung di Controller atau Livewire Component.

### Action Class
- Satu kelas = satu use case spesifik.
- Contoh: `PlaceOrderAction`, `CalculateDeliveryFeeAction`.
- Terima DTO, jalankan logika, return hasil.
- Dapat di-test secara unit.

### Repository Pattern
- Abstraksi akses data dengan interface di Domain, implementasi Eloquent di Infrastructure.
- Contoh: `MenuRepositoryInterface::findAvailableByOutlet(int $outletId)`.
- Memungkinkan mocking untuk testing.

### DTO (Data Transfer Object)
- Objek sederhana tanpa behavior, hanya properti.
- Digunakan untuk mengirim data antar lapisan (dari Livewire ke Action/Service).
- Contoh: `OrderData` berisi `items: array of CartItemData`, `tableId: ?int`, `customerName: string`, dll.

### Event & Listener
- Gunakan untuk aksi asinkron setelah suatu kejadian.
- Contoh:
  - `OrderPlaced` → listener `NotifyKitchen`, `UpdateTableStatusToOccupied`.
  - `PaymentSuccess` → listener `ActivateOrder`, `SendReceipt`, `UpdateStock`.
  - `DeliveryAssigned` → listener `NotifyDriver`.
- Listener yang berat harus di-queue (implement `ShouldQueue`).

---

## 5. State Management dengan Livewire & Alpine

- **Keranjang belanja (Cart)** disimpan di session (`session()->put('cart', ...)`) agar persisten antar reload.
- Livewire Component `Cart` membaca dari session di `mount()`.
- Perubahan keranjang dilakukan melalui method Livewire, lalu disimpan ulang ke session.
- **Alpine.js** hanya untuk interaksi UI kecil: toggle modal, dropdown, animasi loading.
- **Hindari jQuery** untuk manipulasi DOM baru; gunakan `$wire` atau Alpine.
- Gunakan `wire:poll` untuk refresh data semi-real-time (KDS, tracking order), dengan interval 5-10 detik.

---

## 6. UI/UX Mobile‑First

- Framework: Tailwind CSS dengan mobile‑first breakpoints (`sm:`, `md:`, dll).
- Layout Customer: bottom navigation fixed (`fixed bottom-0`) dengan 4 menu utama: Home, Menu, Keranjang, Pesanan Saya.
- Tampilan Menu: grid 2 kolom (`grid-cols-2`), card dengan gambar, nama, harga.
- Detail Menu: full‑screen modal/overlay dengan pilihan topping (checkbox) dan level pedas (radio).
- Keranjang: bottom sheet di mobile, sidebar di desktop.
- Semua interaksi menggunakan komponen Livewire + Alpine, pastikan ukuran tap area cukup (min 44x44px).
- Gunakan ikon SVG ringan (Heroicons via `@bladeui/icons` atau inline).

---

## 7. Alur Kritis Pemesanan Dine‑in (QR)

1. QR meja → URL `/menu/{token}`.
2. `token` didekode (hashids / Crypt) → dapat `table_id`, simpan di session.
3. Pelanggan pilih menu, tambah ke keranjang.
4. Checkout: sistem otomatis tahu nomor meja, tampilkan ringkasan.
5. Pembayaran via Midtrans Snap (pop‑up).
6. Setelah sukses, webhook Midtrans mengubah status order menjadi `paid`.
7. Event `OrderPlaced` → KDS dapur terima notifikasi.
8. Dapur memproses, status di-update, pelanggan bisa tracking.

---

## 8. Pembayaran (Midtrans)

- Gunakan library `midtrans/midtrans-php`.
- Konfigurasi `server_key`, `client_key` di `.env`.
- Buat service `MidtransGateway` implement `PaymentGatewayInterface`.
- Untuk checkout: Livewire panggil service untuk dapat `snap_token`, lalu tampilkan Snap.js popup.
- Webhook: endpoint `POST /api/webhooks/midtrans`, verifikasi signature, update status order.
- Pastikan idempotensi: cek `transaction_id` sebelum update.

---

## 9. QR Meja

- Gunakan package `simplesoftwareio/simple-qrcode` untuk generate QR.
- Data QR adalah URL `route('customer.menu', ['token' => $table->token])`.
- Token dihasilkan dengan `hashids` (enkode `table_id`), bukan UUID agar pendek.
- Simpan gambar QR di `storage/app/public/qrcodes/`, path disimpan di `tables.qr_code_image_path`.
- Admin dapat mencetak label dengan ukuran kecil (10x10 cm) via `dompdf`.

---

## 10. Perhitungan Ongkir (Delivery)

- Tabel `delivery_settings` per outlet: `base_rate_per_km`, `minimum_charge`, `free_delivery_min_order`, `max_delivery_distance`.
- Gunakan interface `DistanceCalculatorInterface` dengan metode `calculateDistance($originLat, $originLon, $destLat, $destLon): float` (km).
- Implementasi awal: OSRM (self‑hosted) atau Google Distance Matrix (API key).
- Logika:
  1. Jika jarak > `max_delivery_distance` → tolak.
  2. `fee = jarak * base_rate_per_km`.
  3. `fee = max(minimum_charge, fee)`.
  4. Jika subtotal >= `free_delivery_min_order` → fee = 0.
- Action `CalculateDeliveryFeeAction` menerima koordinat toko dan alamat pengiriman, return `DeliveryFee` value object.

---

## 11. Database — Tabel Penting

(Singkat, detail di analisis)

- `menus`, `menu_categories`, `toppings`, `menu_topping`, `spiciness_levels`, `menu_spiciness_level`, `tables`, `promos`, `orders`, `order_items`, `order_item_toppings`, `delivery_addresses`, `delivery_settings`, `drivers`, `transactions`, `users` (dengan role).

---

## 12. Autentikasi & Hak Akses

- Package: `spatie/laravel-permission`.
- Roles: `admin`, `kasir`, `dapur`, `driver`, `customer` (opsional, registered user).
- Pelanggan dine‑in tidak perlu login (guest).
- Middleware `role:...` diterapkan di route group.

---

## 13. Testing

- **Unit Test** : Action classes, Service classes, Value objects.
- **Feature Test** : Livewire components (cek render, interaksi), API endpoint (webhook).
- **Integration Test** : Payment flow dengan fake gateway.
- Gunakan `RefreshDatabase` trait.
- Test naming: `test_it_calculates_delivery_fee_correctly`.

---

## 14. Performa & Optimasi

- Cache menu & kategori dengan Redis (`Cache::remember`).
- Eager loading (`with()`), indexing kolom yang sering dicari.
- Queue job untuk kirim email, notifikasi, generate QR, resize gambar.
- Gunakan `wire:lazy` untuk komponen berat.
- Gunakan `wire:poll` dengan interval yang tidak terlalu agresif.

---

## 15. Keamanan

- Validasi semua input dengan Form Request.
- Enkripsi token meja (jangan expose ID asli).
- Verifikasi signature webhook Midtrans.
- Rate limiting di route publik (checkout, API).
- CSRF, XSS, SQL injection dicegah oleh Laravel/Eloquent.

---

## 16. Aturan Penulisan Kode

- Gunakan PHP 8.1+ fitur: constructor property promotion, enum, named arguments.
- Gunakan strict typing (`declare(strict_types=1)`).
- Nama kelas dalam bahasa Inggris deskriptif (e.g., `CalculateDeliveryFeeAction`, bukan `HitungOngkir`).
- Komentar hanya untuk menjelaskan kenapa (why), bukan apa (what).
- Ikuti standar PSR-12.
- Setiap method maksimal 20 baris (sebisa mungkin).

---

## 17. Workflow Branching & Commit (Jika tim)

- Branch: `main` (production), `develop`, `feature/*`.
- Commit message konvensional: `feat: add cart component`, `fix: payment webhook idempotency`.
- Sebelum merge, pastikan test berjalan (CI).

---

## 18. Prioritas Pengembangan Frontend (MVP)

1. Setup project & layout mobile‑first.
2. Customer: menu list, detail, cart, checkout, tracking (halaman dine‑in).
3. Admin: CRUD menu & meja (agar data bisa diisi).
4. Admin: generate QR & cetak.
5. POS kasir.
6. Kitchen Display.
7. Delivery order (menyusul).

---

## 19. Catatan Penting

- **Jangan gunakan jQuery untuk logika baru**, kecuali untuk integrasi dengan plugin pihak ketiga (Midtrans Snap, Select2 jika benar‑benar terpaksa). Gunakan Alpine.js.
- **Gunakan Midtrans Snap** (pop‑up) untuk pembayaran, jangan API redirect.
- **Keranjang wajib survive page reload** → session.
- **Jangan hardcode** koordinat toko, ambil dari `outlets` table.
- **Testing harus ada** untuk setiap Action dan Service utama.

---

## 20. Contoh Implementasi

### Cart Livewire Component (kerangka)
```php
namespace App\Livewire\Customer;

use Livewire\Component;

class Cart extends Component
{
    public array $items = [];

    public function mount(): void
    {
        $this->items = session('cart.items', []);
    }

    public function addItem(array $item): void
    {
        $this->items[] = $item;
        session()->put('cart.items', $this->items);
    }

    // ... render, removeItem, etc.
}
```

### Route dekode token
```php
Route::get('/menu/{token}', function (string $token) {
    $tableId = \Hashids::decode($token)[0] ?? abort(404);
    $table = \App\Domain\Table\Models\Table::findOrFail($tableId);
    session(['table_id' => $table->id, 'table_number' => $table->table_number]);
    return redirect()->route('customer.menu');
})->name('customer.menu.token');
```

---

**Ikuti aturan ini dengan ketat agar konsistensi dan kualitas kode terjaga. Selalu rujuk dokumen Analisa & Perencanaan untuk konteks lebih dalam.**
```