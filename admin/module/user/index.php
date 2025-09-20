 <!--sidebar end-->

 <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
 <!--main content start-->
<?php
        $id = $_SESSION['admin']['id_member'];
        $hasil = $lihat -> member_edit($id);
        $successParam = filter_input(INPUT_GET, 'success', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
        $showSuccess = is_string($successParam) && $successParam !== '';

        $removeParam = filter_input(INPUT_GET, 'remove', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
        $showRemove = is_string($removeParam) && $removeParam !== '';
?>
<h4>Profil Pengguna Aplikasi</h4>
<br>
<?php if($showSuccess){?>
<div class="alert alert-success">
        <p>Edit Data Berhasil !</p>
</div>
<?php }?>
<?php if($showRemove){?>
<div class="alert alert-danger">
	<p>Hapus Data Berhasil !</p>
</div>
<?php }?>
<div class="row">
	<div class="col-sm-3">
		<div class="card card-primary">
			<div class="card-header">
				<h5 class="mt-2"><i class="fa fa-user"></i> Foto Pengguna </h5>
			</div>
			<div class="card-body">
				<img src="assets/img/user/<?= htmlspecialchars($hasil['gambar'] ?? '', ENT_QUOTES, 'UTF-8');?>" alt="#" class="img-fluid w-100" />
			</div>
			<div class="card-footer">
                                <form method="POST" action="fungsi/edit/edit.php?gambar=user" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
					<input type="file" accept="image/*" name="foto">
					<input type="hidden" value="<?= htmlspecialchars($hasil['gambar'] ?? '', ENT_QUOTES, 'UTF-8');?>" name="foto2">
					<input type="hidden" name="id" value="<?= htmlspecialchars($hasil['id_member'] ?? '', ENT_QUOTES, 'UTF-8');?>">
					<br><br>	
					<button type="submit" class="btn btn-primary btn-md" value="Tambah">
						<i class="fas fa-edit mr-1"></i>  Ganti Foto
					</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<div class="card card-primary">
			<div class="card-header">
				<h5 class="mt-2"><i class="fa fa-user"></i> Kelola Pengguna </h5>
			</div>
			<div class="card-body">
				<div class="box-content">
                                        <form class="form-horizontal" method="POST" action="fungsi/edit/edit.php?profil=edit"
                                                enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
						<fieldset>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Nama </label>
								<div class="input-group">
									<input type="text" class="form-control" style="border-radius:0px;" name="nama"
										data-items="4" value="<?= htmlspecialchars($hasil['nm_member'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
										required="required" />
								</div>
							</div>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Email </label>
								<div class="input-group">
									<input type="email" class="form-control" style="border-radius:0px;" name="email"
										value="<?= htmlspecialchars($hasil['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required="required" />
								</div>
							</div>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Telepon </label>
								<div class="input-group">
									<input type="text" class="form-control" style="border-radius:0px;" name="tlp"
										value="<?= htmlspecialchars($hasil['telepon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required="required" />
								</div>
							</div>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">NIK ( KTP ) </label>
								<div class="input-group">
									<input type="text" class="form-control" style="border-radius:0px;" name="nik"
										value="<?= htmlspecialchars($hasil['NIK'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required="required" />
								</div>
							</div>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Alamat </label>
								<div class="controls">
									<textarea name="alamat" rows="3" class="form-control" style="border-radius:0px;"
										required="required"><?= htmlspecialchars($hasil['alamat_member'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
								</div>
							</div>
							<input type="hidden" name="id" value="<?= htmlspecialchars($hasil['id_member'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
							<button class="btn btn-primary" name="btn" value="Tambah">
								<i class="fas fa-edit"></i> Ubah Profil
							</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="card card-primary">
			<div class="card-header">
				<h5 class="mt-2"><i class="fa fa-lock"></i> Ganti Password</h5>
			</div>
			<div class="card-body">
				<div class="box-content">
                                        <form class="form-horizontal" method="POST" action="fungsi/edit/edit.php?pass=ganti-pas">
                                                <?php echo csrf_field(); ?>
						<fieldset>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Username </label>
								<div class="input-group">
									<input type="text" class="form-control" style="border-radius:0px;" name="user"
										data-items="4" value="<?= htmlspecialchars($hasil['user'] ?? '', ENT_QUOTES, 'UTF-8');?>" />
								</div>
							</div>
							<div class="control-group mb-3">
								<label class="control-label" for="typeahead">Password Baru</label>
								<div class="input-group">
									<input type="password" class="form-control" placeholder="Enter Your New Password" id="pass" name="pass" data-items="4" value=""
										required="required" />
								</div>
							</div>
							<input type="hidden" class="form-control" style="border-radius:0px;" name="id"
								value="<?= htmlspecialchars($hasil['id_member'] ?? '', ENT_QUOTES, 'UTF-8');?>" required="required" />
							<button type="submit" class="btn btn-primary" value="Tambah" name="proses"><i class="fas fa-edit"></i> Ubah Password</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>