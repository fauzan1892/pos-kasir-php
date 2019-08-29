 <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <?php 
		  $id = $_SESSION['admin']['id_member'];
		  $hasil = $lihat -> member_edit($id);
      ?>
      <section id="main-content">
          <section class="wrapper">
              <div class="row">
                  <div class="col-lg-12 main-chart">
						<h3>Keranjang Penjualan</h3>
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
						<div class="col-sm-5">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4><i class="fa fa-search"></i> Cari Barang</h4>
								</div>
								<div class="panel-body">
									<input type="text" id="cari" class="form-control" name="cari" placeholder="Masukan Nama Barang / Kode Barang">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4><i class="fa fa-list"></i> Hasil Pencarian</h4>
								</div>
								<div class="panel-body">
									<div id="hasil_cari"></div>
									<div id="tunggu"></div>
									
								</div>
							</div>
						</div>
						

						<div class="col-sm-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4><i class="fa fa-shopping-cart"></i> Kasir
									<a class="pull-right" href="fungsi/hapus/hapus.php?penjualan=jual">
										<button class="btn btn-danger">RESET</button>
									</a>
									</h4>
								</div>
								<div class="panel-body">
									<div id="keranjang">
										<table class="table table-bordered">
											<tr>
												<td><b>Tanggal</b></td>
												<td><input type="text" readonly="readonly" class="form-control" value="<?php echo date("j F Y, G:i");?>" name="tgl"></td>
											</tr>
										</table>
										<table class="table table-bordered" id="example1">
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
												<?php foreach($hasil_penjualan  as $isi){;?>
												<tr>
													<td><?php echo $no;?></td>
													<td><?php echo $isi['nama_barang'];?></td>
													<td>
														<form method="POST" action="fungsi/edit/edit.php?jual=jual">
															<input type="number" name="jumlah" value="<?php echo $isi['jumlah'];?>" class="form-control">
															<input type="hidden" name="id" value="<?php echo $isi['id_penjualan'];?>" class="form-control">
															<input type="hidden" name="id_barang" value="<?php echo $isi['id_barang'];?>" class="form-control">
													</td>
													<td>Rp.<?php echo number_format($isi['total']);?>,-</td>
													<td><?php echo $isi['nm_member'];?></td>
													<td>
															<button class="btn btn-warning">Update</button>
														</form>
														<a href="fungsi/hapus/hapus.php?jual=jual&id=<?php echo $isi['id_penjualan'];?>&brg=<?php echo $isi['id_barang'];?>
														&jml=<?php echo $isi['jumlah']; ?>">
															<button class="btn btn-danger">x</button>
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
												$total = $_POST['total'];
												$bayar = $_POST['bayar'];
												$hitung = $bayar - $total;
											if(!empty($_GET['nota'])){
												
												$id_barang = $_POST['id_barang'];
												$id_member = $_POST['id_member'];
												$jumlah = $_POST['jumlah'];
												$total = $_POST['total1'];
												$tgl_input = $_POST['tgl_input'];
												$periode = $_POST['periode'];
												$jumlah_dipilih = count($id_barang);
												
												for($x=0;$x<$jumlah_dipilih;$x++){
													$d = array($id_barang[$x],$id_member[$x],$jumlah[$x],$total[$x],$tgl_input[$x],$periode[$x]);

													$sql = "INSERT INTO nota (id_barang,id_member,jumlah,total,tanggal_input,periode) VALUES(?,?,?,?,?,?)";
													$row = $config->prepare($sql);
													$row->execute($d);
												}
											}
											?>
											<form method="POST" action="index.php?page=jual&nota=yes#kasirnya">
											
												<?php foreach($hasil_penjualan as $isi){;?>
													<input type="hidden" name="id_barang[]" value="<?php echo $isi['id_barang'];?>">
													<input type="hidden" name="id_member[]" value="<?php echo $isi['id_member'];?>">
													<input type="hidden" name="jumlah[]" value="<?php echo $isi['jumlah'];?>">
													<input type="hidden" name="total1[]" value="<?php echo $isi['total'];?>">
													<input type="hidden" name="tgl_input[]" value="<?php echo $isi['tanggal_input'];?>">
													<input type="hidden" name="periode[]" value="<?php echo date('m-Y');?>">
												<?php $no++; }?>
												<tr>
													<td>Total Semua  </td>
													<td><input type="text" class="form-control" name="total" value="<?php echo $total_bayar;?>"></td>
												
													<td>Bayar  </td>
													<td><input type="text" class="form-control" name="bayar" value="<?php echo $bayar;?>"></td>
													<td><button class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button></td>
												</tr>
											</form>
											<tr>
												<td>Kembali</td>
												<td><input type="text" class="form-control" value="<?php echo $hitung;?>"></td>
												<td></td>
												<td>
													<a href="print.php?nm_member=<?php echo $_SESSION['admin']['nm_member'];?>
													&bayar=<?php echo $bayar;?>&kembali=<?php echo $hitung;?>" target="_blank">
													<button class="btn btn-default">
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
              </div>
          </section>
      </section>
	

<script>
// AJAX call for autocomplete 
$(document).ready(function(){
	$("#cari").keyup(function(){
		$.ajax({
		type: "POST",
		url: "fungsi/edit/edit.php?cari_barang=yes",
		data:'keyword='+$(this).val(),
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