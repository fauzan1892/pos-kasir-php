<?php
session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    require_once __DIR__.'/../csrf.php';
    csrf_guard();
    if (!function_exists('sanitize_scalar_input')) {
        function sanitize_scalar_input($value, bool $allowNewlines = false): string
        {
            $stringValue = trim((string) $value);
            $pattern = $allowNewlines ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u' : '/[\x00-\x1F\x7F]/u';
            $cleaned = preg_replace($pattern, '', $stringValue);

            return $cleaned === null ? '' : trim($cleaned);
        }
    }

    if (!function_exists('get_post_string')) {
        function get_post_string(string $key, bool $allowNewlines = false): string
        {
            $value = filter_input(
                INPUT_POST,
                $key,
                FILTER_UNSAFE_RAW,
                ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
            );

            if ($value === null) {
                return '';
            }

            return sanitize_scalar_input($value, $allowNewlines);
        }
    }

    if (!function_exists('get_post_float')) {
        function get_post_float(string $key): float
        {
            $value = filter_input(
                INPUT_POST,
                $key,
                FILTER_SANITIZE_NUMBER_FLOAT,
                ['flags' => FILTER_FLAG_ALLOW_FRACTION]
            );

            if ($value === null || $value === false || $value === '') {
                return 0.0;
            }

            return (float) $value;
        }
    }

    if (!function_exists('get_post_int')) {
        function get_post_int(string $key): int
        {
            $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
            if ($value === null || $value === false) {
                return 0;
            }

            return (int) $value;
        }
    }

    if (!function_exists('get_get_param')) {
        function get_get_param(string $key): string
        {
            $value = filter_input(
                INPUT_GET,
                $key,
                FILTER_UNSAFE_RAW,
                ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
            );

            if ($value === null) {
                return '';
            }

            return sanitize_scalar_input($value);
        }
    }

    if (!function_exists('get_post_array')) {
        function get_post_array(string $key): array
        {
            $value = filter_input(
                INPUT_POST,
                $key,
                FILTER_DEFAULT,
                ['flags' => FILTER_REQUIRE_ARRAY]
            );
            if (!is_array($value)) {
                return [];
            }

            return array_map(static function ($item): string {
                return sanitize_scalar_input($item, true);
            }, $value);
        }
    }
    if (get_get_param('pengaturan') !== '') {
        $nama = get_post_string('namatoko');
        $alamat = get_post_string('alamat');
        $kontak = get_post_string('kontak');
        $pemilik = get_post_string('pemilik');
        $id = '1';

        $data = [$nama, $alamat, $kontak, $pemilik, $id];
        $sql = 'UPDATE toko SET nama_toko=?, alamat_toko=?, tlp=?, nama_pemilik=? WHERE id_toko = ?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=pengaturan&success=edit-data"</script>';
    }

    if (get_get_param('kategori') !== '') {
        $nama = get_post_string('kategori');
        $id = get_post_string('id');
        if ($id === '' || !ctype_digit($id)) {
            echo '<script>alert("Data kategori tidak valid");history.go(-1);</script>';
            exit;
        }

        $data = [$nama, $id];
        $sql = 'UPDATE kategori SET  nama_kategori=? WHERE id_kategori=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&uid='.$id.'&success-edit=edit-data"</script>';
    }

    if (get_get_param('stok') !== '') {
        $restok = get_post_int('restok');
        $id = get_post_string('id');
        if ($id === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id)) {
            echo '<script>alert("Barang tidak valid");history.go(-1);</script>';
            exit;
        }

        $sqlS = 'select*from barang WHERE id_barang=?';
        $rowS = $config->prepare($sqlS);
        $rowS->execute([$id]);
        $hasil = $rowS->fetch();

        $stok = $restok + (int) ($hasil['stok'] ?? 0);

        $sql = 'UPDATE barang SET stok=? WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute([$stok, $id]);
        echo '<script>window.location="../../index.php?page=barang&success-stok=stok-data"</script>';
    }

    if (get_get_param('barang') !== '') {
        $id = get_post_string('id');
        if ($id === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id)) {
            echo '<script>alert("Barang tidak valid");history.go(-1);</script>';
            exit;
        }

        $kategori = get_post_string('kategori');
        $nama = get_post_string('nama');
        $merk = get_post_string('merk');
        $beli = get_post_float('beli');
        $jual = get_post_float('jual');
        $satuan = get_post_string('satuan');
        $stok = get_post_int('stok');
        $tgl = get_post_string('tgl');

        $data = [$kategori, $nama, $merk, $beli, $jual, $satuan, $stok, $tgl, $id];
        $sql = 'UPDATE barang SET id_kategori=?, nama_barang=?, merk=?,
                                harga_beli=?, harga_jual=?, satuan_barang=?, stok=?, tgl_update=?  WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang/edit&barang='.$id.'&success=edit-data"</script>';
    }

    if (get_get_param('gambar') !== '') {
        $id = get_post_int('id');
        if ($id <= 0) {
            echo '<script>alert("Data pengguna tidak valid");history.go(-1);</script>';
            exit;
        }
        set_time_limit(0);
        if (!isset($_FILES['foto']) || !is_uploaded_file($_FILES['foto']['tmp_name'])) {
            echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
            exit;
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        }

        $allowedTypes = [
            'image/png'   => 'png',
            'image/jpeg'  => 'jpg',
            'image/gif'   => 'gif',
            'image/jpg'   => 'jpeg',
            'image/webp'  => 'webp'
        ];

        $tmpName = $_FILES['foto']['tmp_name'];
        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!$fileinfo) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        }

        $filetype = finfo_file($fileinfo, $tmpName);
        finfo_close($fileinfo);

        if (!isset($allowedTypes[$filetype])) {
            echo '<script>alert("You can only upload JPG, PNG and GIF file");window.location="../../index.php?page=user"</script>';
            exit;
        }

        if (round($_FILES['foto']["size"] / 1024) > 4096) {
            echo '<script>alert("WARNING !!! Besar Gambar Tidak Boleh Lebih Dari 4 MB");window.location="../../index.php?page=user"</script>';
            exit;
        }

        $uploadDir = realpath(__DIR__.'/../../assets/img/user');
        if ($uploadDir === false) {
            echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
            exit;
        }

        $uploadDir .= DIRECTORY_SEPARATOR;
        $name = time().'_'.bin2hex(random_bytes(8)).'.'.$allowedTypes[$filetype];

        if (move_uploaded_file($tmpName, $uploadDir.$name)) {
            $foto2Raw = get_post_string('foto2');
            $foto2 = $foto2Raw !== '' ? basename($foto2Raw) : '';
            if ($foto2 !== '') {
                $oldFile = $uploadDir.$foto2;
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

            $data = [$name, $id];
            $sql = 'UPDATE member SET gambar=?  WHERE member.id_member=?';
            $row = $config->prepare($sql);
            $row->execute($data);
            echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
        } else {
            echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
            exit;
        }
    }

    if (get_get_param('profil') !== '') {
        $id = get_post_int('id');
        if ($id <= 0) {
            echo '<script>alert("Data pengguna tidak valid");history.go(-1);</script>';
            exit;
        }

        $nama = get_post_string('nama');
        $alamat = get_post_string('alamat', true);
        $tlp = get_post_string('tlp');
        $email = get_post_string('email');
        $nik = get_post_string('nik');

        $data = [$nama, $alamat, $tlp, $email, $nik, $id];
        $sql = 'UPDATE member SET nm_member=?,alamat_member=?,telepon=?,email=?,NIK=? WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }
    
    if (get_get_param('pass') !== '') {
        $id = get_post_int('id');
        if ($id <= 0) {
            echo '<script>alert("Data pengguna tidak valid");history.go(-1);</script>';
            exit;
        }

        $user = get_post_string('user');
        $pass = get_post_string('pass');

        $data = [$user, $pass, $id];
        $sql = 'UPDATE login SET user=?,pass=md5(?) WHERE id_member=?';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    if (get_get_param('jual') !== '') {
        $id = get_post_int('id');
        $id_barang = get_post_string('id_barang');
        $jumlah = get_post_int('jumlah');

        if ($id <= 0 || $id_barang === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id_barang) || $jumlah <= 0) {
            echo '<script>alert("Data penjualan tidak valid");history.go(-1);</script>';
            exit;
        }

        $sql_tampil = 'select *from barang where barang.id_barang=?';
        $row_tampil = $config->prepare($sql_tampil);
        $row_tampil->execute([$id_barang]);
        $hasil = $row_tampil->fetch();

        if ($hasil && (int) $hasil['stok'] > $jumlah) {
            $jual = (float) $hasil['harga_jual'];
            $total = $jual * $jumlah;
            $data1 = [$jumlah, $total, $id];
            $sql1 = 'UPDATE penjualan SET jumlah=?,total=? WHERE id_penjualan=?';
            $row1 = $config->prepare($sql1);
            $row1->execute($data1);
            echo '<script>window.location="../../index.php?page=jual#keranjang"</script>';
        } else {
            echo '<script>alert("Keranjang Melebihi Stok Barang Anda !");
                                        window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }

    if (!empty($_GET['cari_barang'])) {
        $cari = trim((string) filter_input(INPUT_POST, 'keyword', FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES));
        if ($cari !== '') {
            $param = "%{$cari}%";
            $sql = "select barang.*, kategori.id_kategori, kategori.nama_kategori
                                        from barang inner join kategori on barang.id_kategori = kategori.id_kategori
                                        where barang.id_barang like ? or barang.nama_barang like ? or barang.merk like ?";
            $row = $config -> prepare($sql);
            $row -> execute(array($param, $param, $param));
            $hasil1= $row -> fetchAll();
            ?>
                <table class="table table-stripped" width="100%" id="example2">
                        <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Merk</th>
                                <th>Harga Jual</th>
                                <th>Aksi</th>
                        </tr>
                <?php foreach ($hasil1 as $hasil) {?>
                        <tr>
                                <td><?php echo htmlspecialchars($hasil['id_barang'], ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($hasil['nama_barang'], ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($hasil['merk'], ENT_QUOTES, 'UTF-8');?></td>
                                <td><?php echo htmlspecialchars($hasil['harga_jual'], ENT_QUOTES, 'UTF-8');?></td>
                                <td>
                                <a href="fungsi/tambah/tambah.php?jual=jual&id=<?php echo urlencode($hasil['id_barang']);?>&id_kasir=<?php echo urlencode($_SESSION['admin']['id_member']);?>&csrf_token=<?php echo urlencode(csrf_get_token());?>"
                                        class="btn btn-success">
                                        <i class="fa fa-shopping-cart"></i></a></td>
                        </tr>
                <?php }?>
                </table>
<?php
        }
    }
}
