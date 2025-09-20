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
		<link rel="stylesheet" href="assets/css/bootstrap.css">
	</head>
	<body>
		<script>window.print();</script>
		<div class="container">
			<div class="row">
				<div class="col-sm-4"></div>
				<div class="col-sm-4">
					<center>
                                                <p><?php echo htmlspecialchars($toko['nama_toko'], ENT_QUOTES, 'UTF-8');?></p>
                                                <p><?php echo htmlspecialchars($toko['alamat_toko'], ENT_QUOTES, 'UTF-8');?></p>
                                                <p>Tanggal : <?php  echo date("j F Y, G:i");?></p>
                                                <p>Kasir : <?php  echo $kasir;?></p>
					</center>
					<table class="table table-bordered" style="width:100%;">
						<tr>
							<td>No.</td>
							<td>Barang</td>
							<td>Jumlah</td>
							<td>Total</td>
						</tr>
						<?php $no=1; foreach($hsl as $isi){?>
						<tr>
							<td><?php echo $no;?></td>
							<td><?php echo $isi['nama_barang'];?></td>
							<td><?php echo $isi['jumlah'];?></td>
							<td><?php echo $isi['total'];?></td>
						</tr>
						<?php $no++; }?>
					</table>
					<div class="pull-right">
						<?php $hasil = $lihat -> jumlah(); ?>
						Total : Rp.<?php echo number_format($hasil['bayar']);?>,-
						<br/>
                                                Bayar : Rp.<?php echo number_format($bayarNominal);?>,-
                                                <br/>
                                                Kembali : Rp.<?php echo number_format($kembaliNominal);?>,-
					</div>
					<div class="clearfix"></div>
					<center>
						<p>Terima Kasih Telah berbelanja di toko kami !</p>
					</center>
				</div>
				<div class="col-sm-4"></div>
			</div>
		</div>
	</body>
</html>
