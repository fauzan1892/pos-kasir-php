<?php 
    declare(strict_types=1);
	@ob_start();
	session_start();
    if (empty($_SESSION['admin'])) {
        header('Location: login.php');
        exit;
    }
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=data-laporan-".date('Y-m-d').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); 

    require 'config.php';
    include $view;
    $lihat = new view($config);

    $bulan_tes =array(
        '01'=>"Januari",
        '02'=>"Februari",
        '03'=>"Maret",
        '04'=>"April",
        '05'=>"Mei",
        '06'=>"Juni",
        '07'=>"Juli",
        '08'=>"Agustus",
        '09'=>"September",
        '10'=>"Oktober",
        '11'=>"November",
        '12'=>"Desember"
    );

    $cariParamRaw = filter_input(INPUT_GET, 'cari', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
    $cariParam = is_string($cariParamRaw) ? trim($cariParamRaw) : '';
    $cariActive = in_array($cariParam, ['yes', 'ok'], true);

    $hariParamRaw = filter_input(INPUT_GET, 'hari', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
    $hariParam = is_string($hariParamRaw) ? trim($hariParamRaw) : '';
    $hariActive = ($hariParam === 'cek');

    $bulanRaw = filter_input(INPUT_GET, 'bln', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
    $bulanParam = (is_string($bulanRaw) && preg_match('/^(0[1-9]|1[0-2])$/', $bulanRaw)) ? $bulanRaw : '';

    $tahunRaw = filter_input(INPUT_GET, 'thn', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
    $tahunParam = (is_string($tahunRaw) && preg_match('/^\d{4}$/', $tahunRaw)) ? $tahunRaw : '';

    $tanggalRaw = filter_input(INPUT_GET, 'tgl', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
    $tanggalParam = (is_string($tanggalRaw) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalRaw)) ? $tanggalRaw : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <!-- view barang -->
    <!-- view barang -->
    <div class="modal-view">
        <h3 style="text-align:center;">
                <?php if($cariActive && $bulanParam !== '' && $tahunParam !== ''){ ?>
                    Data Laporan Penjualan <?= htmlspecialchars($bulan_tes[$bulanParam] ?? $bulanParam, ENT_QUOTES, 'UTF-8');?> <?= htmlspecialchars($tahunParam, ENT_QUOTES, 'UTF-8');?>
                <?php }elseif($hariActive && $tanggalParam !== ''){?>
                    Data Laporan Penjualan <?= htmlspecialchars($tanggalParam, ENT_QUOTES, 'UTF-8');?>
                <?php }else{?>
                    Data Laporan Penjualan <?= htmlspecialchars($bulan_tes[date('m')], ENT_QUOTES, 'UTF-8');?> <?= date('Y');?>
                <?php }?>
        </h3>
        <table border="1" width="100%" cellpadding="3" cellspacing="4">
            <thead>
                <tr bgcolor="yellow">
                    <th> No</th>
                    <th> ID Barang</th>
                    <th> Nama Barang</th>
                    <th style="width:10%;"> Jumlah</th>
                    <th style="width:10%;"> Modal</th>
                    <th style="width:10%;"> Total</th>
                    <th> Kasir</th>
                    <th> Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no=1;
                    if($cariActive && $bulanParam !== '' && $tahunParam !== ''){
                        $periode = $bulanParam.'-'.$tahunParam;
                        $jumlah = 0;
                        $bayar = 0;
                        $hasil = $lihat->periode_jual($periode);
                    }elseif($hariActive && $tanggalParam !== ''){
                        $jumlah = 0;
                        $bayar = 0;
                        $hasil = $lihat->hari_jual($tanggalParam);
                    }else{
                        $hasil = $lihat->jual();
                    }
                ?>
                <?php 
                    $bayar = 0;
                    $jumlah = 0;
                    $modal = 0;
                    foreach($hasil as $isi){ 
                        $bayar += $isi['total'];
                        $modal += $isi['harga_beli'] * $isi['jumlah'];
                        $jumlah += $isi['jumlah'];
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?= htmlspecialchars($isi['id_barang'], ENT_QUOTES, 'UTF-8');?></td>
                    <td><?= htmlspecialchars($isi['nama_barang'], ENT_QUOTES, 'UTF-8');?></td>
                    <td><?= htmlspecialchars($isi['jumlah'], ENT_QUOTES, 'UTF-8');?> </td>
                    <td>Rp.<?php echo number_format($isi['harga_beli']* $isi['jumlah']);?>,-</td>
                    <td>Rp.<?php echo number_format($isi['total']);?>,-</td>
                    <td><?= htmlspecialchars($isi['nm_member'], ENT_QUOTES, 'UTF-8');?></td>
                    <td><?= htmlspecialchars($isi['tanggal_input'], ENT_QUOTES, 'UTF-8');?></td>
                </tr>
                <?php $no++; }?>
                <tr>
                    <td>-</td>
                    <td>-</td>
                    <td><b>Total Terjual</b></td>
                    <td><b><?php echo $jumlah;?></b></td>
                    <td><b>Rp.<?php echo number_format($modal);?>,-</b></td>
                    <td><b>Rp.<?php echo number_format($bayar);?>,-</b></td>
                    <td><b>Keuntungan</b></td>
                    <td><b>
                        Rp.<?php echo number_format($bayar-$modal);?>,-</b></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
