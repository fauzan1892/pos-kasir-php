 <!--sidebar end-->
      
      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">

              <div class="row">
                  <div class="col-lg-12 main-chart">
						<h3>Data Kategori</h3>
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
						<form method="POST" action="fungsi/tambah/tambah.php?kategori=tambah">
							<table>
								<tr>
									<td style="width:15pc;"><input type="text" class="form-control" name="kategori" placeholder="Masukan Kategori Barang Baru"></td>
									<td><button id="tombol-simpan" class="btn btn-primary"><i class="fa fa-plus"></i> Insert Data</button></td>
								</tr>
							</table>
						</form>
						<br/>
						<table class="table table-bordered" id="example1">
							<thead>
								<tr style="background:#DFF0D8;color:#333;">
									<td>No.</td>
									<td>Kategori</td>
									<td>Tanggal Input</td>
									<td>Aksi</td>
								</tr>
							</thead>
							<tbody>
							<?php 
								$hasil = $lihat -> kategori();
								$no=1;
								foreach($hasil as $isi){
							?>
								<tr>
									<td><?php echo $no;?></td>
									<td><?php echo $isi['nama_kategori'];?></td>
									<td><?php echo $isi['tgl_input'];?></td>
									<td>
										<a href="index.php?page=kategori/edit&kategori=<?php echo $isi['id_kategori'];?>"><button class="btn btn-warning">Edit</button></a>
										<a href="fungsi/hapus/hapus.php?kategori=hapus&id=<?php echo $isi['id_kategori'];?>" onclick="javascript:return confirm('Hapus Data Kategori ?');"><button class="btn btn-danger">Hapus</button></a>
									</td>
								</tr>
							<?php $no++; }?>
							</tbody>
						</table>
						<div class="clearfix" style="padding-top:16%;"></div>
				  </div>
              </div>
          </section>
      </section>
	
