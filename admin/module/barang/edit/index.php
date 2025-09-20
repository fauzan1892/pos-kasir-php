 <!--sidebar end-->

 <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
 <!--main content start-->
 <?php
        $barangParamRaw = filter_input(INPUT_GET, 'barang', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
        $idParam = (is_string($barangParamRaw) && preg_match('/^[A-Za-z0-9-]+$/', $barangParamRaw)) ? $barangParamRaw : '';
        $hasil = $lihat -> barang_edit($idParam);
        if (!$hasil) {
                echo '<div class="alert alert-danger">Data barang tidak ditemukan.</div>';
                return;
        }
?>
 <a href="index.php?page=barang" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Balik </a>
 <h4>Edit Barang</h4>
 <?php if(isset($_GET['success'])){?>
 <div class="alert alert-success">
     <p>Edit Data Berhasil !</p>
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
                        <form action="fungsi/edit/edit.php?barang=edit" method="POST">
                                <?php echo csrf_field(); ?>
				<tr>
					<td>ID Barang</td>
                                        <td><input type="text" readonly="readonly" class="form-control" value="<?php echo htmlspecialchars($hasil['id_barang'], ENT_QUOTES, 'UTF-8');?>"
                                                        name="id"></td>
				</tr>
				<tr>
					<td>Kategori</td>
					<td>
						<select name="kategori" class="form-control">
							<option value="<?php echo htmlspecialchars($hasil['id_kategori'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($hasil['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?></option>
							<option value="#">Pilih Kategori</option>
							<?php  $kat = $lihat -> kategori(); foreach($kat as $isi){ 	?>
							<option value="<?php echo htmlspecialchars($isi['id_kategori'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($isi['nama_kategori'], ENT_QUOTES, 'UTF-8'); ?></option>
							<?php }?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Nama Barang</td>
					<td><input type="text" class="form-control" value="<?php echo htmlspecialchars($hasil['nama_barang'], ENT_QUOTES, 'UTF-8');?>" name="nama"></td>
				</tr>
				<tr>
					<td>Merk Barang</td>
					<td><input type="text" class="form-control" value="<?php echo htmlspecialchars($hasil['merk'], ENT_QUOTES, 'UTF-8');?>" name="merk"></td>
				</tr>
				<tr>
					<td>Harga Beli</td>
					<td><input type="number" class="form-control" value="<?php echo htmlspecialchars($hasil['harga_beli'], ENT_QUOTES, 'UTF-8');?>" name="beli"></td>
				</tr>
				<tr>
					<td>Harga Jual</td>
					<td><input type="number" class="form-control" value="<?php echo htmlspecialchars($hasil['harga_jual'], ENT_QUOTES, 'UTF-8');?>" name="jual"></td>
				</tr>
				<tr>
					<td>Satuan Barang</td>
					<td>
						<select name="satuan" class="form-control">
							<option value="<?php echo htmlspecialchars($hasil['satuan_barang'], ENT_QUOTES, 'UTF-8');?>"><?php echo htmlspecialchars($hasil['satuan_barang'], ENT_QUOTES, 'UTF-8');?>
							</option>
							<option value="#">Pilih Satuan</option>
							<option value="PCS">PCS</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Stok</td>
					<td><input type="number" class="form-control" value="<?php echo htmlspecialchars($hasil['stok'], ENT_QUOTES, 'UTF-8');?>" name="stok"></td>
				</tr>
				<tr>
					<td>Tanggal Update</td>
					<td><input type="text" readonly="readonly" class="form-control" value="<?php echo  date("j F Y, G:i");?>"
							name="tgl"></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary"><i class="fa fa-edit"></i> Update Data</button></td>
				</tr>
			</form>
		</table>
	</div>
</div>
