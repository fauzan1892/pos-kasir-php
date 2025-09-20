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

        function get_post_int(string $key): int
        {
            $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
            if ($value === null || $value === false) {
                return 0;
            }

            return (int) $value;
        }

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

    $kategoriAction = get_get_param('kategori');
    if ($kategoriAction !== '') {
        $nama = get_post_string('kategori');
        if ($nama === '') {
            echo '<script>alert("Kategori tidak valid");history.go(-1);</script>';
            exit;
        }

        $tgl = date('j F Y, G:i');
        $data = [$nama, $tgl];
        $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=kategori&&success=tambah-data"</script>';
    }

    $barangAction = get_get_param('barang');
    if ($barangAction !== '') {
        $id = get_post_string('id');
        if ($id === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id)) {
            echo '<script>alert("ID barang tidak valid");history.go(-1);</script>';
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

        $data = [
            $id,
            $kategori,
            $nama,
            $merk,
            $beli,
            $jual,
            $satuan,
            $stok,
            $tgl,
        ];

        $sql = 'INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input)
                            VALUES (?,?,?,?,?,?,?,?,?) ';
        $row = $config->prepare($sql);
        $row->execute($data);
        echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
    }

    $jualAction = get_get_param('jual');
    if ($jualAction !== '') {
        csrf_require_token(get_get_param('csrf_token'));

        $id = get_get_param('id');
        if ($id === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id)) {
            echo '<script>alert("Barang tidak valid");window.location="../../index.php?page=jual"</script>';
            exit;
        }

        $kasir = get_get_param('id_kasir');
        if ($kasir === '' || !ctype_digit($kasir)) {
            echo '<script>alert("Kasir tidak valid");window.location="../../index.php?page=jual"</script>';
            exit;
        }

        // get tabel barang id_barang
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $row = $config->prepare($sql);
        $row->execute([$id]);
        $hsl = $row->fetch();

        if ($hsl && (int) $hsl['stok'] > 0) {
            $jumlah = 1;
            $total = (float) $hsl['harga_jual'];
            $tgl = date('j F Y, G:i');

            $data1 = [$id, $kasir, $jumlah, $total, $tgl];

            $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input) VALUES (?,?,?,?,?)';
            $row1 = $config->prepare($sql1);
            $row1->execute($data1);

            echo '<script>window.location="../../index.php?page=jual&success=tambah-data"</script>';
        } else {
            echo '<script>alert("Stok Barang Anda Telah Habis !");
                                        window.location="../../index.php?page=jual#keranjang"</script>';
        }
    }
}
