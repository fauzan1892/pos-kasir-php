# POS Codekop v2.0
## Deskripsi Umum
POS Codekop v2.0 adalah aplikasi kasir (point of sale) berbasis PHP dan MySQL yang dirancang untuk membantu usaha ritel skala kecil mengelola penjualan harian. Kode sumber ini terbuka untuk dipelajari, dimodifikasi, dan disesuaikan sehingga cocok digunakan sebagai bahan belajar pengembangan web maupun pondasi proyek POS ringan.

## Status Proyek
Pengembangan aktif aplikasi ini telah dihentikan. Namun, repositori tetap dibuka untuk kontribusi komunitas. Silakan ajukan _pull request_ apabila ingin menambahkan fitur baru, melakukan pemeliharaan, atau memperbaiki permasalahan lainnya.

## Klarifikasi Keamanan CVE Fixed
Sebagai tindak lanjut atas laporan kerentanan yang telah dipublikasikan, versi terbaru repositori ini telah mendapatkan perbaikan keamanan dari tim Codex dengan cakupan berikut:

- **CVE-2023-36345** – Penambahan perlindungan CSRF pada seluruh alur perubahan data penting.
- **CVE-2023-36346** – Sanitasi dan _escaping_ parameter untuk mencegah serangan XSS.
- **CVE-2023-36347** – Pembatasan akses terhadap berkas ekspor agar hanya dapat diunduh oleh pengguna yang sah.
- **CVE-2023-36348** – Validasi berlapis pada unggahan berkas untuk mencegah eksekusi kode dari file yang tidak sah.

Harap selalu memperbarui instalasi Anda dengan perubahan terbaru dan meninjau ulang konfigurasi server sebelum digunakan di lingkungan produksi.

## Donasi
Dukungan dapat diberikan melalui Saweria: <https://saweria.co/fauzan1892>

## Referensi
- Artikel sumber: <https://www.codekop.com/read/source-code-aplikasi-penjualan-barang-kasir-dengan-php-amp-mysql-gratis.html>
- Versi terbaru aplikasi POS: <https://www.codekop.com/products/source-code-aplikasi-pos-penjualan-barang-kasir-dengan-php-mysql-3.html>
- Aplikasi POS Cafe Resto: <https://www.codekop.com/products/source-code-aplikasi-pos-kasir-cafe-resto-berbasis-website-4.html>

_**Catatan:** Bagi pihak yang melakukan unggah ulang (_reupload_) sumber kode ini, mohon cantumkan sumber aslinya._

## Konfigurasi Basis Data
Sesuaikan kredensial koneksi pada `config.php` dengan nama basis data, pengguna, dan kata sandi yang digunakan pada server Anda.

## Kredensial Demo
- **Nama Pengguna:** `admin`
- **Kata Sandi:** `123`
- Login demo diperuntukkan untuk skenario _single user_.

## Tangkapan Layar

### Versi 2.0
- Halaman Masuk  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/1.png)
- Dasbor  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/2.png)
- Tabel Barang  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/3.png)
- Kategori  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/4.png)
- Keranjang / Transaksi  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/5.png)
- Laporan  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/6.png)
- Nama Toko  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/7.png)
- Pengaturan Pengguna  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/picv2/8.png)

### Versi 1.0
- Halaman Masuk  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/login.png)
- Dasbor  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/1.png)
- Tabel Barang  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/2.png)
- Keranjang / Transaksi  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/4.png)
- Laporan  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/5.png)
- Nama Toko  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/6.png)
- Pengaturan Pengguna  
  ![](https://raw.githubusercontent.com/fauzan1892/pos-kasir-php/master/assets/img/pic/7.png)

## Riwayat Perubahan
- **20 September 2025**
  - Pembaruan dokumentasi untuk menjelaskan status pemeliharaan dan klarifikasi keamanan terkini.
  - Penambahan mitigasi kerentanan CVE-2023-36345 hingga CVE-2023-36348 melalui validasi input, pembatasan akses, dan perlindungan CSRF.
  - Penyeragaman tampilan cetak struk agar kompatibel dengan printer thermal serta pengetatan sanitasi data cetak.
- **12 Desember 2022**
  - Rilis versi 2.0.
  - Migrasi ke template SB Admin 2 Bootstrap 4.
- **31 Januari 2021**  
  - Penambahan sortir stok kurang dari &ge; 3.  
  - Pencarian laporan per tanggal dan per bulan.  
  - Perbaikan perhitungan laporan.
- **06 Oktober 2020**  
  - Perbaikan galat sesi pada lingkungan hosting.  
  - Pembaruan tampilan login dan header tabel.  
  - Penyesuaian transaksi agar stok yang lebih kecil dari keranjang tidak dapat diproses.  
  - Penghapusan _trigger_ SQL dan pengurangan stok otomatis setelah transaksi bayar.
- **23 Agustus 2020**  
  - Revisi modul cetak.  
  - Penambahan pemberitahuan transaksi telah dibayar.
- **18 Juli 2020**  
  - Perbaikan fitur edit kategori dan formulir tambah barang.  
  - Perapihan formulir laporan.
- **29 Agustus 2019**  
  - Perbaikan tampilan laporan.  
  - Memastikan transaksi yang dibayar tercatat pada laporan.  
  - Pencarian barang otomatis dengan dukungan jQuery Ajax.  
  - Laporan dapat difilter per bulan dan tahun.

## Kontributor
- [Fauzan Falah](https://fauzan.codekop.com/)

Blog resmi: <https://www.codekop.com/>

Gunakan aplikasi dengan bijak dan selamat belajar.
