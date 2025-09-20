<?php
        $idParam = isset($_GET['barang']) ? preg_replace('/[^A-Za-z0-9-]/', '', $_GET['barang']) : '';
        $hasil = $lihat -> barang_edit($idParam);
        if (!$hasil) {
                echo '<div class="alert alert-danger">Data barang tidak ditemukan.</div>';
                return;
        }
?>
<a href="index.php?page=barang" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
<h4>Details Barang</h4>
<?php if(isset($_GET['success-stok'])){?>
<div class="alert alert-success">
	<p>Tambah Stok Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['success'])){?>
<div class="alert alert-success">
	<p>Tambah Data Berhasil !</p>
</div>
<?php }?>
<?php if(isset($_GET['remove'])){?>
<div class="alert alert-danger">
	<p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<div class="card card-body">
	<div class="table-responsive">
		<table class="table table-striped">
			<tr>
				<td>ID Barang</td>
                                <td><?php echo htmlspecialchars($hasil['id_barang'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Kategori</td>
                                <td><?php echo htmlspecialchars($hasil['nama_kategori'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Nama Barang</td>
                                <td><?php echo htmlspecialchars($hasil['nama_barang'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Merk Barang</td>
                                <td><?php echo htmlspecialchars($hasil['merk'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Harga Beli</td>
                                <td><?php echo htmlspecialchars($hasil['harga_beli'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Harga Jual</td>
                                <td><?php echo htmlspecialchars($hasil['harga_jual'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Satuan Barang</td>
                                <td><?php echo htmlspecialchars($hasil['satuan_barang'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Stok</td>
                                <td><?php echo htmlspecialchars($hasil['stok'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Tanggal Input</td>
                                <td><?php echo htmlspecialchars($hasil['tgl_input'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
			<tr>
				<td>Tanggal Update</td>
                                <td><?php echo htmlspecialchars($hasil['tgl_update'], ENT_QUOTES, 'UTF-8');?></td>
			</tr>
		</table>
	</div>
</div>
