<?php
declare(strict_types=1);
@ob_start();
session_start();

if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

require 'config.php';
include $view;

$lihat = new view($config);
$toko  = $lihat->toko();
$hsl   = $lihat->penjualan();
$hasil = $lihat->jumlah();

function rupiah(float $n): string {
    return 'Rp ' . number_format($n, 0, ',', '.');
}

$nmMember       = (string) filter_input(INPUT_GET, 'nm_member', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
$kasir          = htmlspecialchars($nmMember, ENT_QUOTES, 'UTF-8');

$bayarInput     = filter_input(INPUT_GET, 'bayar', FILTER_VALIDATE_FLOAT);
$kembaliInput   = filter_input(INPUT_GET, 'kembali', FILTER_VALIDATE_FLOAT);
$bayarNominal   = ($bayarInput !== false && $bayarInput !== null) ? (float) $bayarInput : 0.0;
$kembaliNominal = ($kembaliInput !== false && $kembaliInput !== null) ? (float) $kembaliInput : 0.0;

$totalBayar = isset($hasil['bayar']) ? (float) $hasil['bayar'] : 0.0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Struk Pembelian</title>
    <style>
    @page {
        margin: 2mm;
        /* biar ada ruang tipis di kiri/kanan */
    }

    html,
    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: "Courier New", Courier, monospace;
        font-size: 12px;
        color: #000;
    }

    .receipt {
        width: 100%;
        /* penuh, fleksibel */
        margin: 0 auto;
    }

    .center {
        text-align: center;
    }

    .sep {
        border-top: 1px dashed #000;
        margin: 6px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        text-align: left;
        padding: 2px 0;
        vertical-align: top;
    }

    thead th {
        border-bottom: 1px dashed #000;
        font-weight: bold;
    }

    .ta-r {
        text-align: right;
    }

    .item-sep td {
        border-bottom: 1px dashed #000;
        padding-top: 4px;
    }

    .totals .row {
        display: grid;
        grid-template-columns: 1fr auto;
        margin: 2px 0;
    }
    </style>

</head>

<body onload="window.print()" onafterprint="window.close()">
    <div class="receipt">
        <!-- Header toko -->
        <div class="header center mb-8">
            <p><strong><?= htmlspecialchars($toko['nama_toko'] ?? 'Toko', ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <?php if (!empty($toko['alamat_toko'])): ?>
            <p><?= nl2br(htmlspecialchars($toko['alamat_toko'], ENT_QUOTES, 'UTF-8')); ?></p>
            <?php endif; ?>
        </div>

        <!-- Meta -->
        <div class="meta mb-8">
            <div>Tanggal</div>
            <div><?= date('d/m/Y H:i'); ?></div>
            <div>Kasir</div>
            <div><?= $kasir !== '' ? $kasir : '-'; ?></div>
        </div>

        <!-- Daftar item -->
        <table class="mb-6">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th class="ta-r">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_iterable($hsl)): ?>
                <?php
            $first = true;
            foreach ($hsl as $isi):
              $nama   = htmlspecialchars((string)($isi['nama_barang'] ?? ''), ENT_QUOTES, 'UTF-8');
              $jumlah = (int)($isi['jumlah'] ?? 0);
              $total  = (float)($isi['total'] ?? 0.0);
              $hargaSatuan = $jumlah > 0 ? $total / $jumlah : $total;
          ?>
                <?php if (!$first): ?>
                <tr class="item-sep">
                    <td colspan="2"></td>
                </tr>
                <?php endif; $first = false; ?>
                <tr>
                    <td class="item-name"><?= $nama; ?></td>
                    <td class="ta-r"><?= rupiah($total); ?></td>
                </tr>
                <tr>
                    <td><?= $jumlah; ?> × <?= rupiah($hargaSatuan); ?></td>
                    <td class="ta-r"></td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="2" class="center">Tidak ada data.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="sep"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="row">
                <div>Total</div>
                <div><?= rupiah($totalBayar); ?></div>
            </div>
            <div class="row">
                <div>Bayar</div>
                <div><?= rupiah($bayarNominal); ?></div>
            </div>
            <div class="row">
                <div>Kembali</div>
                <div><?= rupiah($kembaliNominal); ?></div>
            </div>
        </div>

        <div class="sep"></div>

        <!-- Footer -->
        <div class="footer center mb-4">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>
</body>

</html>