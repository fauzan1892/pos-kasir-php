<?php 
	@ob_start();
	session_start();
	if(!empty($_SESSION['admin'])){ }else{
		echo '<script>window.location="login.php";</script>';
        exit;
	}
	require 'config.php';
        include $view;
        $lihat = new view($config);
        $toko = $lihat -> toko();
        $hsl = $lihat -> penjualan();
        $nmMember = (string) filter_input(INPUT_GET, 'nm_member', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
        $kasir = htmlspecialchars($nmMember, ENT_QUOTES, 'UTF-8');
        $bayarInput = filter_input(INPUT_GET, 'bayar', FILTER_VALIDATE_FLOAT);
        $kembaliInput = filter_input(INPUT_GET, 'kembali', FILTER_VALIDATE_FLOAT);
        $bayarNominal = $bayarInput !== false && $bayarInput !== null ? (float) $bayarInput : 0.0;
        $kembaliNominal = $kembaliInput !== false && $kembaliInput !== null ? (float) $kembaliInput : 0.0;
?>
<html>
        <head>
                <title>print</title>
                <style>
                        @page {
                                size: 80mm auto;
                                margin: 4mm;
                        }

                        body {
                                font-family: "Courier New", Courier, monospace;
                                font-size: 12px;
                                margin: 0;
                                display: flex;
                                justify-content: center;
                                background: #fff;
                        }

                        .receipt {
                                width: 80mm;
                                max-width: 100%;
                                padding: 8px;
                        }

                        .receipt-header,
                        .receipt-footer {
                                text-align: center;
                                margin-bottom: 12px;
                        }

                        .receipt-header p,
                        .receipt-footer p {
                                margin: 2px 0;
                        }

                        .meta {
                                margin-bottom: 8px;
                        }

                        .meta-row {
                                display: flex;
                                justify-content: space-between;
                        }

                        table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 10px;
                        }

                        th,
                        td {
                                padding: 4px 0;
                        }

                        th {
                                border-bottom: 1px dashed #000;
                                text-align: left;
                        }

                        td.qty,
                        td.price,
                        td.total {
                                text-align: right;
                        }

                        .totals {
                                border-top: 1px dashed #000;
                                padding-top: 6px;
                        }

                        .totals-row {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 4px;
                        }

                        @media print {
                                body {
                                        background: #fff;
                                }

                                .receipt {
                                        box-shadow: none;
                                }
                        }
                </style>
        </head>
        <body>
                <script>window.print();</script>
                <div class="receipt">
                        <div class="receipt-header">
                                <p><strong><?php echo htmlspecialchars($toko['nama_toko'], ENT_QUOTES, 'UTF-8');?></strong></p>
                                <p><?php echo nl2br(htmlspecialchars($toko['alamat_toko'], ENT_QUOTES, 'UTF-8'));?></p>
                        </div>
                        <div class="meta">
                                <div class="meta-row">
                                        <span>Tanggal</span>
                                        <span><?php echo date("d/m/Y H:i");?></span>
                                </div>
                                <div class="meta-row">
                                        <span>Kasir</span>
                                        <span><?php echo $kasir;?></span>
                                </div>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                                <th>Barang</th>
                                                <th class="qty">Qty</th>
                                                <th class="price">Harga</th>
                                                <th class="total">Subtotal</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php foreach ($hsl as $isi) {
                                                $jumlah = isset($isi['jumlah']) ? (int) $isi['jumlah'] : 0;
                                                $total = isset($isi['total']) ? (float) $isi['total'] : 0.0;
                                                $hargaSatuan = $jumlah > 0 ? $total / $jumlah : $total;
                                        ?>
                                        <tr>
                                                <td><?php echo htmlspecialchars($isi['nama_barang'], ENT_QUOTES, 'UTF-8');?></td>
                                                <td class="qty"><?php echo $jumlah;?></td>
                                                <td class="price">Rp<?php echo number_format($hargaSatuan);?></td>
                                                <td class="total">Rp<?php echo number_format($total);?></td>
                                        </tr>
                                        <?php }?>
                                </tbody>
                        </table>
                        <?php $hasil = $lihat -> jumlah(); ?>
                        <div class="totals">
                                <div class="totals-row">
                                        <span>Total</span>
                                        <span>Rp<?php echo number_format((float) $hasil['bayar']);?></span>
                                </div>
                                <div class="totals-row">
                                        <span>Bayar</span>
                                        <span>Rp<?php echo number_format($bayarNominal);?></span>
                                </div>
                                <div class="totals-row">
                                        <span>Kembali</span>
                                        <span>Rp<?php echo number_format($kembaliNominal);?></span>
                                </div>
                        </div>
                        <div class="receipt-footer">
                                <p>Terima kasih telah berbelanja!</p>
                        </div>
                </div>
        </body>
</html>
