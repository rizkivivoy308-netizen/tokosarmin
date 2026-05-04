# 🏪 Toko Kelontong — Laravel 10

Aplikasi manajemen toko kelontong berbasis Laravel 10 dengan fitur penjualan, hutang pelanggan, dan notifikasi WhatsApp.

---

## ✅ Fitur Aplikasi

- Login Admin (Laravel Breeze)
- Dashboard statistik (pelanggan, barang, transaksi, hutang)
- CRUD Pelanggan + nomor WhatsApp
- CRUD Barang + stok otomatis berkurang
- Transaksi penjualan (pilih banyak barang, hitung total otomatis)
- Metode pembayaran: Cash / Hutang
- Hutang otomatis dibuat jika metode = hutang
- Jatuh tempo otomatis +7 hari
- Pembayaran hutang / cicilan
- Status hutang otomatis lunas jika sisa = 0
- Notifikasi WhatsApp via link wa.me
- Laporan Transaksi, Hutang, dan Pembayaran

---

## 🚀 Cara Menjalankan

### 1. Persiapan Software
- PHP 8.1+
- Composer
- Node.js
- MySQL (via XAMPP / Laragon)

### 2. Buat Project Laravel 10
```bash
composer create-project laravel/laravel toko-kelontong "10.*"
cd toko-kelontong
```

### 3. Install Laravel Breeze
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

### 4. Copy File dari ZIP ini
Salin semua file dari ZIP ke dalam folder project sesuai strukturnya.

### 5. Buat Database
Buka phpMyAdmin → Buat database: `toko_kelontong`

### 6. Setting .env
```
DB_DATABASE=toko_kelontong
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migration & Seeder
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 8. Jalankan Server
```bash
php artisan serve
```

### 9. Akses Aplikasi
```
URL      : http://localhost:8000
Email    : admin@toko.com
Password : password123
```

---

## 📁 Struktur File dalam ZIP

```
toko-kelontong/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── CustomerController.php
│   │   ├── BarangController.php
│   │   ├── TransaksiController.php
│   │   ├── HutangController.php
│   │   └── PembayaranController.php
│   ├── Models/
│   │   ├── Customer.php
│   │   ├── Barang.php
│   │   ├── Transaksi.php
│   │   ├── DetailTransaksi.php
│   │   ├── Hutang.php
│   │   └── Pembayaran.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/  (6 file migration)
│   └── seeders/     (4 file seeder)
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── components/alert.blade.php
│   ├── dashboard.blade.php
│   ├── customers/   (index, create, edit, show)
│   ├── barangs/     (index, create, edit)
│   ├── transaksis/  (index, create, show)
│   ├── hutangs/     (index, show)
│   ├── pembayarans/ (index, create)
│   └── laporan/     (transaksi, hutang, pembayaran)
├── routes/
│   └── web.php
└── README.md
```

---

## 🐛 Troubleshooting

| Error | Solusi |
|-------|--------|
| Class not found | `composer dump-autoload` |
| Tabel tidak ada | `php artisan migrate:fresh --seed` |
| Error 500 | `php artisan cache:clear` |
| Pagination tidak Bootstrap | Cek AppServiceProvider |

---

Dibuat untuk keperluan Kerja Praktik (KP) Mahasiswa.
