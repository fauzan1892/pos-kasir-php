<?php
$successParam = filter_input(INPUT_GET, 'success', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
$showSuccess = is_string($successParam) && $successParam !== '';
?>
<h4>Pengaturan Toko</h4>
<br>
<?php if($showSuccess){?>
<div class="alert alert-success">
	<p>Ubah Data Berhasil !</p>
</div>
<?php }?>
<div class="card">
	<div class="card-body">
                <form method="post" action="fungsi/edit/edit.php?pengaturan=ubah">
                        <?php echo csrf_field(); ?>
			<div class="row">
				<div class="col-md 6">
					<div class="form-group">
						<label for="">Nama Toko</label>
						<input class="form-control" name="namatoko" value="<?= htmlspecialchars($toko['nama_toko'] ?? '', ENT_QUOTES, 'UTF-8');?>"
									placeholder="Nama Toko">
					</div>
					<div class="form-group">
						<label for="">Alamat Toko</label>
						<input class="form-control" name="alamat" value="<?= htmlspecialchars($toko['alamat_toko'] ?? '', ENT_QUOTES, 'UTF-8');?>"
									placeholder="Alamat Toko">
					</div>
				</div>
				<div class="col-md 6">
					<div class="form-group">
						<label for="">Kontak (Hp)</label>
						<input class="form-control" name="kontak" value="<?= htmlspecialchars($toko['tlp'] ?? '', ENT_QUOTES, 'UTF-8');?>"
									placeholder="Kontak (Hp)">
					</div>
					<div class="form-group">
						<label for="">Nama Pemilik Toko</label>
						<input class="form-control" name="pemilik" value="<?= htmlspecialchars($toko['nama_pemilik'] ?? '', ENT_QUOTES, 'UTF-8');?>"
									placeholder="Nama Pemilik Toko">
					</div>
				</div>
			</div>
			<button id="tombol-simpan" class="btn btn-primary"><i class="fas fa-edit"></i> Update Data</button>
		</form>
	</div>
</div>