<?php
session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    require_once __DIR__.'/../csrf.php';
    csrf_guard();
    if (!empty($_GET['pengaturan'])) {
        $nama= htmlentities($_POST['namatoko']);
        $alamat = htmlentities($_POST['alamat']);
        $kontak = htmlentities($_POST['kontak']);
        $pemilik = htmlentities($_POST['pemilik']);
        $id = '1';

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $kontak;
        $data[] = $pemilik;
        $data[] = $id;
        $sql = 'UPDATE toko SET nama_toko=?, alamat_toko=?, tlp=?, nama_pemilik=? WHERE id_toko = ?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=pengaturan&success=edit-data"</script>';
    }

    if (!empty($_GET['kategori'])) {
        $nama= htmlentities($_POST['kategori']);
        $id= htmlentities($_POST['id']);
        $data[] = $nama;
        $data[] = $id;
        $sql = 'UPDATE kategori SET  nama_kategori=? WHERE id_kategori=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=kategori&uid='.$id.'&success-edit=edit-data"</script>';
    }

    if (!empty($_GET['stok'])) {
        $restok = htmlentities($_POST['restok']);
        $id = htmlentities($_POST['id']);
        $dataS[] = $id;
        $sqlS = 'select*from barang WHERE id_barang=?';
        $rowS = $config -> prepare($sqlS);
        $rowS -> execute($dataS);
        $hasil = $rowS -> fetch();

        $stok = $restok + $hasil['stok'];

        $data[] = $stok;
        $data[] = $id;
        $sql = 'UPDATE barang SET stok=? WHERE id_barang=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=barang&success-stok=stok-data"</script>';
    }

    if (!empty($_GET['barang'])) {
        $id = htmlentities($_POST['id']);
        $kategori = htmlentities($_POST['kategori']);
        $nama = htmlentities($_POST['nama']);
        $merk = htmlentities($_POST['merk']);
        $beli = htmlentities($_POST['beli']);
        $jual = htmlentities($_POST['jual']);
        $satuan = htmlentities($_POST['satuan']);
        $stok = htmlentities($_POST['stok']);
        $tgl = htmlentities($_POST['tgl']);

        $data[] = $kategori;
        $data[] = $nama;
        $data[] = $merk;
        $data[] = $beli;
        $data[] = $jual;
        $data[] = $satuan;
        $data[] = $stok;
        $data[] = $tgl;
        $data[] = $id;
        $sql = 'UPDATE barang SET id_kategori=?, nama_barang=?, merk=?, 
				harga_beli=?, harga_jual=?, satuan_barang=?, stok=?, tgl_update=?  WHERE id_barang=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=barang/edit&barang='.$id.'&success=edit-data"</script>';
    }

    if (!empty($_GET['gambar'])) {
        $id = htmlentities($_POST['id']);
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
            $foto2 = isset($_POST['foto2']) ? basename((string) $_POST['foto2']) : '';
            if ($foto2 !== '') {
                $oldFile = $uploadDir.$foto2;
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

            $data[] = $name;
            $data[] = $id;
            $sql = 'UPDATE member SET gambar=?  WHERE member.id_member=?';
            $row = $config -> prepare($sql);
            $row -> execute($data);
            echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
        } else {
            echo '<script>alert("Masukan Gambar !");window.location="../../index.php?page=user"</script>';
            exit;
        }
    }

    if (!empty($_GET['profil'])) {
        $id = htmlentities($_POST['id']);
        $nama = htmlentities($_POST['nama']);
        $alamat = htmlentities($_POST['alamat']);
        $tlp = htmlentities($_POST['tlp']);
        $email = htmlentities($_POST['email']);
        $nik = htmlentities($_POST['nik']);

        $data[] = $nama;
        $data[] = $alamat;
        $data[] = $tlp;
        $data[] = $email;
        $data[] = $nik;
        $data[] = $id;
        $sql = 'UPDATE member SET nm_member=?,alamat_member=?,telepon=?,email=?,NIK=? WHERE id_member=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }
    
    if (!empty($_GET['pass'])) {
        $id = htmlentities($_POST['id']);
        $user = htmlentities($_POST['user']);
        $pass = htmlentities($_POST['pass']);

        $data[] = $user;
        $data[] = $pass;
        $data[] = $id;
        $sql = 'UPDATE login SET user=?,pass=md5(?) WHERE id_member=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=user&success=edit-data"</script>';
    }

    if (!empty($_GET['jual'])) {
        $id = htmlentities($_POST['id']);
        $id_barang = htmlentities($_POST['id_barang']);
        $jumlah = htmlentities($_POST['jumlah']);

        $sql_tampil = "select *from barang where barang.id_barang=?";
        $row_tampil = $config -> prepare($sql_tampil);
        $row_tampil -> execute(array($id_barang));
        $hasil = $row_tampil -> fetch();

        if ($hasil['stok'] > $jumlah) {
            $jual = $hasil['harga_jual'];
            $total = $jual * $jumlah;
            $data1[] = $jumlah;
            $data1[] = $total;
            $data1[] = $id;
            $sql1 = 'UPDATE penjualan SET jumlah=?,total=? WHERE id_penjualan=?';
            $row1 = $config -> prepare($sql1);
            $row1 -> execute($data1);
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
