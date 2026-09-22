# Instalasi OdeonMoney di VS Code + Laragon

## 1. Siapkan Laragon

Install dan buka Laragon. Klik **Start All** supaya web server dan MySQL aktif.

## 2. Buat project Laravel 13 fresh

Buka **Laragon > Terminal**, lalu:

```powershell
cd C:\laragon\www
composer create-project laravel/laravel:^13.0 odeonmoney
cd odeonmoney
```

Laravel 13 saat ini mensyaratkan PHP 8.3 atau lebih tinggi. Composer dan Node/NPM juga dibutuhkan untuk instalasi dan build asset.

## 3. Buka di VS Code

```powershell
code .
```

Jangan membuat project Laravel baru kedua. Folder `odeonmoney` dari langkah 2 menjadi project utama.

## 4. Salin kode dari ZIP ini

Ekstrak ZIP hasil dari ChatGPT. Salin/merge folder berikut ke project Laravel yang baru dibuat:

```text
app/
database/
resources/
routes/
public/assets/
vite.config.js
package.json
```

Tambahkan `.env.example` hanya sebagai referensi. **Jangan menimpa `.env` yang sudah ada** tanpa melihat konfigurasi database lokal.

## 5. Install dependency frontend

Dari terminal project:

```powershell
npm install
```

## 6. Buat database

Melalui phpMyAdmin/DBeaver buat:

```text
Database: odeonmoney
Username: root
Password: kosong (default Laragon, jika belum diubah)
```

Kemudian buka `.env` dan pastikan:

```env
APP_NAME=OdeonMoney
APP_URL=http://odeonmoney.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=odeonmoney
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
FILESYSTEM_DISK=public
```

## 7. Generate application key

```powershell
php artisan key:generate
```

## 8. Jalankan migration + seeder

```powershell
php artisan migrate --seed
```

Seeder membuat kategori, tempat wisata, sumber pendapatan, dan beberapa transaksi contoh.

## 9. Aktifkan storage untuk bukti bayar

```powershell
php artisan storage:link
```

Bukti bayar disimpan pada `storage/app/public/payment_proofs`.

## 10. Jalankan aplikasi

Terminal 1:

```powershell
npm run dev
```

Terminal 2:

```powershell
php artisan serve
```

Buka:

```text
http://localhost:8000
```

Atau Pretty URL Laragon:

```text
http://odeonmoney.test
```

## 11. Login demo

```text
Email    : manager@odeon.com
Password : password
```

## 12. Alur pengerjaan di VS Code

### Layout
`resources/views/layouts/app.blade.php` adalah layout induk. Sidebar dan topbar ada di `resources/views/partials/`.

### Dashboard
`DashboardController` menghitung total pemasukan, total pengeluaran, net balance, margin, grafik mingguan, dan transaksi terbaru. View: `resources/views/dashboard/index.blade.php`.

### Transaksi
`TransactionController` menangani daftar, filter, tambah, edit, hapus, validasi, dan upload bukti. View ada di `resources/views/transactions/`.

### Reports
`ReportController` membuat rekapan per bulan/tahun, tipe, dan distribusi pengeluaran per kategori.

### Master Data
`MasterController` membuat dropdown yang bisa dikelola untuk kategori, tempat wisata, dan sumber pendapatan. Akses melalui ikon gear atau `/master-data`.

## 13. Menambah tempat wisata

Buka **Master Data > Tempat Wisata**, isi nama dan keterangan, klik **Tambah**. Item langsung masuk ke dropdown transaksi dan filter.

## 14. Memperbaiki kesalahan input

Buka **Transactions > Edit (✎)**. Semua field transaksi dapat diperbaiki. Validasi Laravel akan menampilkan pesan error pada field yang bermasalah.

## 15. Perhitungan nominal

```text
Total = Jumlah x Harga Satuan
```

Contoh:

```text
Jumlah       = 10
Harga satuan = Rp 150.000
Total        = Rp 1.500.000
```

Total dihitung di browser untuk UX dan dihitung ulang di controller untuk keamanan data.

## 16. Export

- **Download PDF**: `window.print()` dan simpan sebagai PDF dari browser.
- **Export to Excel**: membuat CSV yang bisa dibuka di Microsoft Excel.

## 17. Troubleshooting

### `composer` tidak ditemukan
Gunakan Laragon Terminal. Dokumentasi Laragon menjelaskan bahwa PHP, Composer, Node, dan npm tersedia melalui tooling Laragon.

### Vite error

```powershell
npm install
npm run dev
```

### Database error
Pastikan MySQL aktif dan `.env` sesuai.

### Bukti bayar tidak tampil

```powershell
php artisan storage:link
```
