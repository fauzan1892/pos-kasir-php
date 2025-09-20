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

        $notaParamRaw = filter_input(INPUT_GET, 'nota', FILTER_UNSAFE_RAW, ['flags' => FILTER_FLAG_NO_ENCODE_QUOTES]);
        $notaAction = is_string($notaParamRaw) ? trim($notaParamRaw) : '';
        $isNotaYes = ($notaAction === 'yes');
?>
        <h4>Keranjang Penjualan</h4>
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
		<div class="col-sm-4">
			<div class="card card-primary mb-3">
				<div class="card-header bg-primary text-white">
					<h5><i class="fa fa-search"></i> Cari Barang</h5>
				</div>
				<div class="card-body">
					<input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan : Kode / Nama Barang  [ENTER]">
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="card card-primary mb-3">
				<div class="card-header bg-primary text-white">
					<h5><i class="fa fa-list"></i> Hasil Pencarian</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<div id="hasil_cari"></div>
						<div id="tunggu"></div>
					</div>
				</div>
			</div>
		</div>
		

		<div class="col-sm-12">
			<div class="card card-primary">
				<div class="card-header bg-primary text-white">
					<h5><i class="fa fa-shopping-cart"></i> KASIR
                                        <a class="btn btn-danger float-right"
                                                onclick="javascript:return confirm('Apakah anda ingin reset keranjang ?');" href="fungsi/hapus/hapus.php?penjualan=jual&csrf_token=<?php echo urlencode(csrf_get_token());?>">
						<b>RESET KERANJANG</b></a>
					</h5>
				</div>
				<div class="card-body">
					<div id="keranjang" class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<td><b>Tanggal</b></td>
                                                                <td><input type="text" readonly="readonly" class="form-control" value="<?= htmlspecialchars(date('j F Y, G:i'), ENT_QUOTES, 'UTF-8');?>" name="tgl"></td>
							</tr>
						</table>
						<table class="table table-bordered w-100" id="example1">
							<thead>
								<tr>
									<td> No</td>
									<td> Nama Barang</td>
									<td style="width:10%;"> Jumlah</td>
									<td style="width:20%;"> Total</td>
									<td> Kasir</td>
									<td> Aksi</td>
								</tr>
							</thead>
							<tbody>
								<?php $total_bayar=0; $no=1; $hasil_penjualan = $lihat -> penjualan();?>
								<?php foreach($hasil_penjualan  as $isi){?>
								<tr>
									<td><?= htmlspecialchars((string) $no, ENT_QUOTES, 'UTF-8');?></td>
									<td><?= htmlspecialchars($isi['nama_barang'], ENT_QUOTES, 'UTF-8');?></td>
									<td>
										<!-- aksi ke table penjualan -->
                                                                                <form method="POST" action="fungsi/edit/edit.php?jual=jual">
                                                                                                <?php echo csrf_field(); ?>
												<input type="number" name="jumlah" value="<?= htmlspecialchars($isi['jumlah'], ENT_QUOTES, 'UTF-8');?>" class="form-control">
												<input type="hidden" name="id" value="<?= htmlspecialchars($isi['id_penjualan'], ENT_QUOTES, 'UTF-8');?>" class="form-control">
												<input type="hidden" name="id_barang" value="<?= htmlspecialchars($isi['id_barang'], ENT_QUOTES, 'UTF-8');?>" class="form-control">
											</td>
											<td>Rp.<?php echo number_format($isi['total']);?>,-</td>
											<td><?= htmlspecialchars($isi['nm_member'], ENT_QUOTES, 'UTF-8');?></td>
											<td>
												<button type="submit" class="btn btn-warning">Update</button>
										</form>
										<!-- aksi ke table penjualan -->
                                                                                <a href="fungsi/hapus/hapus.php?jual=jual&id=<?= htmlspecialchars($isi['id_penjualan'], ENT_QUOTES, 'UTF-8');?>&brg=<?= htmlspecialchars($isi['id_barang'], ENT_QUOTES, 'UTF-8');?>
                                                                                        &jml=<?= urlencode($isi['jumlah']); ?>&csrf_token=<?php echo urlencode(csrf_get_token());?>"  class="btn btn-danger"><i class="fa fa-times"></i>
										</a>
									</td>
								</tr>
								<?php $no++; $total_bayar += $isi['total'];}?>
							</tbody>
					</table>
					<br/>
					<?php $hasil = $lihat -> jumlah(); ?>
					<div id="kasirnya">
						<table class="table table-stripped">
							<?php
							// proses bayar dan ke nota
                                                        $total = 0.0;
                                                        $bayar = 0.0;
                                                        $hitung = 0.0;
                                                        if($isNotaYes && $_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                $totalInput = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_NUMBER_FLOAT, ['flags' => FILTER_FLAG_ALLOW_FRACTION]);
                                                                $bayarInput = filter_input(INPUT_POST, 'bayar', FILTER_SANITIZE_NUMBER_FLOAT, ['flags' => FILTER_FLAG_ALLOW_FRACTION]);
                                                                if($totalInput !== null && $totalInput !== false && $totalInput !== '') {
                                                                        $total = (float) $totalInput;
                                                                }
                                                                if($bayarInput !== null && $bayarInput !== false && $bayarInput !== '') {
                                                                        $bayar = (float) $bayarInput;
                                                                }

                                                                if($bayar > 0.0) {
                                                                        $hitung = $bayar - $total;
                                                                        if($bayar >= $total) {
                                                                                $idBarangList = filter_input(INPUT_POST, 'id_barang', FILTER_DEFAULT, ['flags' => FILTER_REQUIRE_ARRAY]);
                                                                                $idMemberList = filter_input(INPUT_POST, 'id_member', FILTER_DEFAULT, ['flags' => FILTER_REQUIRE_ARRAY]);
                                                                                $jumlahList = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT, ['flags' => FILTER_REQUIRE_ARRAY]);
                                                                                $totalList = filter_input(INPUT_POST, 'total1', FILTER_SANITIZE_NUMBER_FLOAT, ['flags' => FILTER_REQUIRE_ARRAY | FILTER_FLAG_ALLOW_FRACTION]);
                                                                                $tglInputList = filter_input(INPUT_POST, 'tgl_input', FILTER_UNSAFE_RAW, ['flags' => FILTER_REQUIRE_ARRAY | FILTER_FLAG_NO_ENCODE_QUOTES]);
                                                                                $periodeList = filter_input(INPUT_POST, 'periode', FILTER_UNSAFE_RAW, ['flags' => FILTER_REQUIRE_ARRAY | FILTER_FLAG_NO_ENCODE_QUOTES]);

                                                                                $jumlahDipilih = is_array($idBarangList) ? count($idBarangList) : 0;
                                                                                for($x = 0; $x < $jumlahDipilih; $x++) {
                                                                                        $barangId = '';
                                                                                        if (is_array($idBarangList) && isset($idBarangList[$x]) && is_string($idBarangList[$x]) && preg_match('/^[A-Za-z0-9-]+$/', $idBarangList[$x])) {
                                                                                                $barangId = $idBarangList[$x];
                                                                                        }

                                                                                        $memberId = (is_array($idMemberList) && isset($idMemberList[$x])) ? (int) $idMemberList[$x] : 0;
                                                                                        $jumlahItem = (is_array($jumlahList) && isset($jumlahList[$x]) && $jumlahList[$x] !== false) ? (int) $jumlahList[$x] : 0;
                                                                                        $totalItem = (is_array($totalList) && isset($totalList[$x]) && $totalList[$x] !== false && $totalList[$x] !== '') ? (float) $totalList[$x] : 0.0;
                                                                                        $tglInputItem = (is_array($tglInputList) && isset($tglInputList[$x])) ? trim((string) $tglInputList[$x]) : '';
                                                                                        $periodeItem = (is_array($periodeList) && isset($periodeList[$x])) ? trim((string) $periodeList[$x]) : '';

                                                                                        if ($barangId === '' || $memberId <= 0 || $jumlahItem <= 0 || $tglInputItem === '' || $periodeItem === '') {
                                                                                                continue;
                                                                                        }

                                                                                        $d = array($barangId, $memberId, $jumlahItem, $totalItem, $tglInputItem, $periodeItem);
                                                                                        $sql = "INSERT INTO nota (id_barang,id_member,jumlah,total,tanggal_input,periode) VALUES(?,?,?,?,?,?)";
                                                                                        $row = $config->prepare($sql);
                                                                                        $row->execute($d);

                                                                                        $sql_barang = "SELECT * FROM barang WHERE id_barang = ?";
                                                                                        $row_barang = $config->prepare($sql_barang);
                                                                                        $row_barang->execute(array($barangId));
                                                                                        $hsl = $row_barang->fetch();

                                                                                        if ($hsl) {
                                                                                                $stok = (int) $hsl['stok'];
                                                                                                $idb  = $hsl['id_barang'];

                                                                                                $total_stok = $stok - $jumlahItem;
                                                                                                $sql_stok = "UPDATE barang SET stok = ? WHERE id_barang = ?";
                                                                                                $row_stok = $config->prepare($sql_stok);
                                                                                                $row_stok->execute(array($total_stok, $idb));
                                                                                        }
                                                                                }
                                                                                echo '<script>alert("Belanjaan Berhasil Di Bayar !");</script>';
                                                                        } else {
                                                                                echo '<script>alert("Uang Kurang ! Rp.'.$hitung.'");</script>';
                                                                        }
                                                                }
                                                        }
							?>
							<!-- aksi ke table nota -->
                                                        <form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
                                                                <?php echo csrf_field(); ?>
								<?php foreach($hasil_penjualan as $isi){;?>
									<input type="hidden" name="id_barang[]" value="<?= htmlspecialchars($isi['id_barang'], ENT_QUOTES, 'UTF-8');?>">
									<input type="hidden" name="id_member[]" value="<?= htmlspecialchars($isi['id_member'], ENT_QUOTES, 'UTF-8');?>">
									<input type="hidden" name="jumlah[]" value="<?= htmlspecialchars($isi['jumlah'], ENT_QUOTES, 'UTF-8');?>">
									<input type="hidden" name="total1[]" value="<?= htmlspecialchars($isi['total'], ENT_QUOTES, 'UTF-8');?>">
									<input type="hidden" name="tgl_input[]" value="<?= htmlspecialchars($isi['tanggal_input'], ENT_QUOTES, 'UTF-8');?>">
                                                                        <input type="hidden" name="periode[]" value="<?= htmlspecialchars(date('m-Y'), ENT_QUOTES, 'UTF-8');?>">
								<?php $no++; }?>
								<tr>
									<td>Total Semua  </td>
									<td><input type="text" class="form-control" name="total" value="<?= htmlspecialchars((string) $total_bayar, ENT_QUOTES, 'UTF-8');?>"></td>
								
									<td>Bayar  </td>
									<td><input type="text" class="form-control" name="bayar" value="<?= htmlspecialchars((string) $bayar, ENT_QUOTES, 'UTF-8');?>"></td>
									<td><button class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button>
                                                                        <?php if($isNotaYes){?>
                                                                                <a class="btn btn-danger" href="fungsi/hapus/hapus.php?penjualan=jual&csrf_token=<?php echo urlencode(csrf_get_token());?>">
										<b>RESET</b></a></td><?php }?></td>
								</tr>
							</form>
							<!-- aksi ke table nota -->
							<tr>
								<td>Kembali</td>
								<td><input type="text" class="form-control" value="<?= htmlspecialchars((string) $hitung, ENT_QUOTES, 'UTF-8');?>"></td>
								<td></td>
								<td>
                                                                        <a href="print.php?nm_member=<?php echo urlencode($_SESSION['admin']['nm_member']);?>
                                                                        &bayar=<?php echo urlencode($bayar);?>&kembali=<?php echo urlencode($hitung);?>" target="_blank">
									<button class="btn btn-secondary">
										<i class="fa fa-print"></i> Print Untuk Bukti Pembayaran
									</button></a>
								</td>
							</tr>
						</table>
						<br/>
						<br/>
					</div>
				</div>
			</div>
		</div>
	</div>
	

<script>
// AJAX call for autocomplete 
$(document).ready(function(){
	$("#cari").change(function(){
                $.ajax({
                        type: "POST",
                        url: "fungsi/edit/edit.php?cari_barang=yes",
                        data:{keyword: $(this).val(), csrf_token: window.csrfToken || ''},
			beforeSend: function(){
				$("#hasil_cari").hide();
				$("#tunggu").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
			},
			success: function(html){
				$("#tunggu").html('');
				$("#hasil_cari").show();
				$("#hasil_cari").html(html);
			}
		});
	});
});
//To select country name
</script>