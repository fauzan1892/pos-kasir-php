# POS Codekop v2.2
## Deskripsi Umum
POS Codekop v2.0 adalah aplikasi kasir (point of sale) berbasis PHP dan MySQL yang dirancang untuk membantu usaha ritel skala kecil mengelola penjualan harian. Kode sumber ini terbuka untuk dipelajari, dimodifikasi, dan disesuaikan sehingga cocok digunakan sebagai bahan belajar pengembangan web maupun pondasi proyek POS ringan.

## Status Proyek
Pengembangan aktif aplikasi ini telah dihentikan. Namun, repositori tetap dibuka untuk kontribusi komunitas. Silakan ajukan _pull request_ apabila ingin menambahkan fitur baru, melakukan pemeliharaan, atau memperbaiki permasalahan lainnya...

Dibutuhkan **PHP 8.2** ke atas beserta ekstensi `pdo_mysql` dan `fileinfo`. Kode telah ditinjau dan diuji jalan pada PHP 8.2.

## Butuh Versi yang Lebih Lengkap?
Repositori ini adalah edisi belajar/opensource dan tidak lagi dikembangkan aktif. Jika butuh aplikasi POS yang terus diperbarui, lengkap dengan dukungan teknis, silakan lihat produk resmi dari Codekop:

- **POS Kasir PHP & MySQL (Full Version)** – <https://www.codekop.com/products/source-code-aplikasi-pos-penjualan-barang-kasir-dengan-php-mysql-3.html>
- **Varian POS Kasir lainnya** – <https://www.codekop.com/products/pos-kasir.html>

## Klarifikasi Keamanan CVE Fixed
Sebagai tindak lanjut atas laporan kerentanan yang telah dipublikasikan, versi terbaru repositori ini telah mendapatkan perbaikan keamanan dari tim Codex dengan cakupan berikut:

- **CVE-2023-36345** – Penambahan perlindungan CSRF pada seluruh alur perubahan data penting.
- **CVE-2023-36346** – Sanitasi dan _escaping_ parameter untuk mencegah serangan XSS.
- **CVE-2023-36347** – Pembatasan akses terhadap berkas ekspor agar hanya dapat diunduh oleh pengguna yang sah.
- **CVE-2023-36348** – Validasi berlapis pada unggahan berkas untuk mencegah eksekusi kode dari file yang tidak sah.

Harap selalu memperbarui instalasi Anda dengan perubahan terbaru dan meninjau ulang konfigurasi server sebelum digunakan di lingkungan produksi.

## Daftar Temuan Keamanan

| Kode Bug | Deskripsi | Temuan / Dampak | Rekomendasi | Status |
|---|---|---|---|---|
| BUGSEC-01 | CSRF (Cross-Site Request Forgery) | Penyerang bisa mengubah data (hapus barang/user) tanpa izin pemilik akun. | Implementasikan CSRF Token pada setiap form POST. | ✅ Sudah diperbaiki — token CSRF (`fungsi/csrf.php`) diverifikasi dengan `hash_equals()` pada seluruh alur tambah/ubah/hapus data. |
| BUGSEC-02 | XSS (Cross-Site Scripting) | Injeksi skrip berbahaya melalui input nama barang, kategori, nama toko, dsb. | Gunakan fungsi `htmlspecialchars()` atau sanitasi parameter. | ✅ Sudah diperbaiki — seluruh output data dinamis di-escape dengan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`, termasuk celah pada `admin/template/sidebar.php` yang baru ditutup. |
| BUGSEC-03 | Broken Access Control | Pengguna yang tidak sah dapat mengunduh berkas laporan ekspor. | Tambahkan validasi session pada file unduhan. | ✅ Sudah diperbaiki — `excel.php` dan `print.php` memvalidasi `$_SESSION['admin']` sebelum mengeluarkan konten. |
| BUGLOG-04 | Session Timeout | Sesi pengguna tidak berakhir otomatis setelah idle lama. | Atur durasi `session.gc_maxlifetime` di server/aplikasi. | ✅ Sudah diperbaiki — ditambahkan pengecekan idle timeout di level aplikasi (`session_enforce_timeout()` pada `fungsi/csrf.php`, dipanggil dari `index.php`, `excel.php`, `print.php`, dan `fungsi/edit/edit.php`) yang men-*destroy* sesi setelah 30 menit tanpa aktivitas, tidak hanya bergantung pada `gc_maxlifetime` server. |
| BUGVAL-05 | File Upload Vulnerability | Risiko eksekusi kode melalui unggahan file ilegal. | Batasi ekstensi file hanya untuk `.jpg` atau `.png`. | ✅ Sudah diperbaiki — validasi tipe file dilakukan berdasarkan *MIME* asli (`finfo_file`, bukan sekadar ekstensi), dibatasi maks. 4 MB, disimpan dengan nama acak, dan direktori unggahan (`assets/img/user`) menonaktifkan eksekusi PHP lewat `.htaccess`. |

Selain lima temuan di atas, perbaikan tambahan juga diterapkan: hashing kata sandi dimigrasikan dari MD5 ke `password_hash()`/bcrypt (dengan verifikasi mundur otomatis untuk akun lama), `session_regenerate_id()` dipanggil setelah login untuk mencegah *session fixation*, serta direktori `.git` dan berkas `db_toko.sql` diblokir dari akses HTTP langsung lewat `.htaccess`.

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
- **21 Agustus 2026**
  - Kasir (`admin/module/jual/index.php`) dirombak jadi layout dua kolom (cari barang & keranjang) yang hidup lewat AJAX (`fungsi/kasir/kasir.php`) — tanpa reload halaman, kembalian dihitung langsung saat mengetik.
  - Setiap transaksi kini punya nomor transaksi (`no_transaksi`, format `YYYYMMDD-XXX`) yang menghubungkan seluruh item dalam satu nota; struk bisa dicetak ulang kapan saja lewat `print.php?notrx=...`.
  - Laporan (`admin/module/laporan/index.php`) dikelompokkan per transaksi, dengan modal **Detail** untuk rincian item, tombol **Cetak Ulang**, dan **Hapus** (membatalkan transaksi sekaligus mengembalikan stok barang).
  - `excel.php` menambahkan kolom No Transaksi pada ekspor data (tetap per-item, tidak dikelompokkan).
  - Migrasi hashing kata sandi dari MD5 ke `password_hash()`/bcrypt, dengan upgrade otomatis untuk akun lama saat login.
  - Penambahan `session_regenerate_id()` setelah login untuk mencegah *session fixation*.
  - Penerapan idle session timeout 30 menit di seluruh endpoint admin (`index.php`, `excel.php`, `print.php`, `fungsi/edit/edit.php`, `fungsi/kasir/kasir.php`).
  - Penutupan celah XSS pada `admin/template/sidebar.php` dengan `htmlspecialchars()`.
  - Pemblokiran akses langsung ke `.git`, `.sql`, `.env`, `.log`, `.md` lewat `.htaccess`.
  - Kompatibilitas dan pengujian dilakukan pada PHP 8.2.
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
