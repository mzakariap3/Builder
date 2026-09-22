# Mapping tampilan desain -> kode

| Tampilan desain | Implementasi |
|---|---|
| Login | `resources/views/auth/login.blade.php` |
| Dashboard | `resources/views/dashboard/index.blade.php` |
| Admin Dashboard | route `/admin/dashboard`, memakai dashboard dengan mode admin |
| Financial Data | `resources/views/transactions/index.blade.php` |
| Add Financial Entry | `resources/views/transactions/create.blade.php` + `_form.blade.php` |
| Edit Entry | `resources/views/transactions/edit.blade.php` + `_form.blade.php` |
| Financial Reports | `resources/views/reports/index.blade.php` |
| Master Data | `resources/views/masters/index.blade.php` |

## Fitur tambahan

1. Category ditempatkan sebelum tanggal dan nominal pada form.
2. Tab Income dan Expense.
3. Sumber pendapatan hanya ditampilkan saat Income.
4. Quantity x Unit Price menghasilkan Total otomatis.
5. Tourism Place menjadi dropdown terpisah dan dapat diedit.
6. Bukti bayar memiliki preview.
7. Transactions bisa difilter berdasarkan tipe, bulan, tahun, kategori, tempat wisata, sumber pendapatan, dan kata kunci.
8. Reports mengelompokkan expense berdasarkan kategori.
9. Nominal memakai format Rupiah Indonesia.
10. Edit transaction digunakan untuk memperbaiki kesalahan input.
