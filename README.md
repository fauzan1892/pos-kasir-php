# POS Codekop v2.0 – CVE Fixed & Secure

## Ringkasan
POS Codekop v2.0 adalah aplikasi kasir (point of sale) berbasis PHP dan MySQL yang dirancang untuk membantu usaha ritel skala kecil mengelola penjualan harian. Kode sumber ini terbuka untuk dipelajari, dimodifikasi, dan disesuaikan sehingga cocok digunakan sebagai bahan belajar pengembangan web maupun pondasi proyek POS ringan.

## Keterangan Penggunaan
- **Tujuan utama:** media pembelajaran dan eksperimen pengembangan aplikasi kasir dengan stack PHP, MySQL, dan Bootstrap.
- **Pemakaian produksi ringan:** dapat dipakai sebagai solusi penjualan sederhana setelah menyesuaikan kebutuhan bisnis (barang, kategori, kasir, dan laporan).
- **Dukungan komunitas:** kontribusi berupa _issue_ atau _pull request_ sangat dianjurkan untuk menjaga stabilitas dan keamanan proyek.

## Catatan Depresiasi
Versi POS Codekop sebelum tahun 2023 telah dinyatakan _deprecated_ karena mengandung kerentanan **CVE-2023-36347**. Harap **tidak** menggunakan rilis lama tersebut untuk lingkungan produksi.

## Klarifikasi Keamanan
Rilis ini sudah memperbaiki kerentanan yang dilaporkan, termasuk CVE-2023-36347. Seluruh modul kritikal kini memiliki validasi input yang ketat, proteksi CSRF, pembatasan akses terhadap ekspor data, serta filter terhadap unggahan berkas. Pastikan Anda selalu menggunakan rilis terbaru untuk mendapatkan perbaikan keamanan tersebut.

## Fitur Utama
- **Penjualan Kasir Real-time:** proses transaksi cepat dengan keranjang belanja, kalkulasi kembalian, dan cetak struk yang aman.
- **Master Barang & Kategori:** kelola daftar produk, harga beli/jual, satuan, serta klasifikasi kategori.
- **Manajemen Stok:** peringatan stok menipis, penambahan stok ulang, dan histori penyesuaian.
- **Laporan Penjualan:** laporan periodik harian/bulanan lengkap dengan ringkasan keuntungan.
- **Export Excel Aman:** ekspor laporan dengan otorisasi pengguna dan sanitasi parameter untuk mencegah injeksi.

## Mulai Cepat
1. Import `db_toko.sql` ke database MySQL Anda.
2. Salin `config.php` dan sesuaikan kredensial koneksi (host, database, username, password).
3. Akses aplikasi via browser dan masuk menggunakan kredensial demo:
   - **Username:** `admin`
   - **Password:** `123`
4. Sesuaikan data master barang, kategori, dan informasi toko melalui menu pengaturan.

## Referensi dan Dukungan
- Dokumentasi & artikel Codekop: <https://www.codekop.com/>
- Dukungan pengembangan: <https://saweria.co/fauzan1892>

Gunakan aplikasi ini secara bijak, lakukan penyesuaian keamanan tambahan sesuai kebutuhan infrastruktur Anda, dan selamat belajar membangun aplikasi kasir dengan POS Codekop!
