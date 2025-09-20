<?php

session_start();
if (!empty($_SESSION['admin'])) {
    require '../../config.php';
    require_once __DIR__.'/../csrf.php';
    $csrfToken = filter_input(
        INPUT_GET,
        'csrf_token',
        FILTER_UNSAFE_RAW,
        ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]
    );
    csrf_require_token($csrfToken ?? '');
    if (!function_exists('sanitize_scalar_input')) {
        function sanitize_scalar_input($value, bool $allowNewlines = false): string
        {
            $stringValue = trim((string) $value);
            $pattern = $allowNewlines ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u' : '/[\x00-\x1F\x7F]/u';
            $cleaned = preg_replace($pattern, '', $stringValue);

            return $cleaned === null ? '' : trim($cleaned);
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

    if (get_get_param('kategori') !== '') {
        $id = get_get_param('id');
        if ($id === '' || !ctype_digit($id)) {
            echo '<script>alert("Data kategori tidak valid");history.go(-1);</script>';
            exit;
        }

        $sql = 'DELETE FROM kategori WHERE id_kategori=?';
        $row = $config->prepare($sql);
        $row->execute([$id]);
        echo '<script>window.location="../../index.php?page=kategori&&remove=hapus-data"</script>';
    }

    if (get_get_param('barang') !== '') {
        $id = get_get_param('id');
        if ($id === '' || !preg_match('/^[A-Za-z0-9-]+$/', $id)) {
            echo '<script>alert("Data barang tidak valid");history.go(-1);</script>';
            exit;
        }

        $sql = 'DELETE FROM barang WHERE id_barang=?';
        $row = $config->prepare($sql);
        $row->execute([$id]);
        echo '<script>window.location="../../index.php?page=barang&&remove=hapus-data"</script>';
    }

    if (get_get_param('jual') !== '') {
        $barangId = get_get_param('brg');
        $penjualanId = get_get_param('id');
        if ($barangId === '' || !preg_match('/^[A-Za-z0-9-]+$/', $barangId) || $penjualanId === '' || !ctype_digit($penjualanId)) {
            echo '<script>alert("Data penjualan tidak valid");history.go(-1);</script>';
            exit;
        }

        $sqlI = 'select*from barang where id_barang=?';
        $rowI = $config->prepare($sqlI);
        $rowI->execute([$barangId]);
        $rowI->fetch();

        $sql = 'DELETE FROM penjualan WHERE id_penjualan=?';
        $row = $config->prepare($sql);
        $row->execute([$penjualanId]);
        echo '<script>window.location="../../index.php?page=jual"</script>';
    }

    if (get_get_param('penjualan') !== '') {
        $sql = 'DELETE FROM penjualan';
        $row = $config->prepare($sql);
        $row->execute();
        echo '<script>window.location="../../index.php?page=jual"</script>';
    }

    if (get_get_param('laporan') !== '') {
        $sql = 'DELETE FROM nota';
        $row = $config->prepare($sql);
        $row->execute();
        echo '<script>window.location="../../index.php?page=laporan&remove=hapus"</script>';
    }
}
