<?php 
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");
    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=data-laporan-".date('Y-m-d').".xlsx");

    require 'config.php';
    include $view;
    $lihat = new view($config);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if(!empty($_GET['cari'])){?>
	<!-- view barang -->	
    <div class="modal-view">
        <table border="1" width="100%" cellpadding="3" cellspacing="4">
            <thead>
                <tr bgcolor="yellow">
                    <th> No</th>
                    <th> ID Barang</th>
                    <th> Nama Barang</th>
                    <th style="width:10%;"> Jumlah</th>
                    <th style="width:20%;"> Total</th>
                    <th> Kasir</th>
                    <th> Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $periode = $_GET['bln'].'-'.$_GET['thn'];
                    $no=1; 
                    $jumlah = 0;
                    $bayar = 0;
                    $hasil = $lihat -> periode_jual($periode);
                    foreach($hasil as $isi){
                        $bayar += $isi['total'];
                        $jumlah += $isi['jumlah'];
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $isi['id_barang'];?></td>
                    <td><?php echo $isi['nama_barang'];?></td>
                    <td><?php echo $isi['jumlah'];?> </td>
                    <td><?php echo $isi['total'];?></td>
                    <td><?php echo $isi['nm_member'];?></td>
                    <td><?php echo $isi['tanggal_input'];?></td>
                </tr>
                <?php $no++; }?>
                <tr>
                    <th colspan="3">Total Terjual</td>
                    <th><?php echo $jumlah;?></td>
                    <th>Rp.<?php echo number_format($bayar);?>,-</td>
                    <th colspan="2" style="background:#ddd"></th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php }else{?>
    <!-- view barang -->	
    <div class="modal-view">
        <table border="1" width="100%" cellpadding="3" cellspacing="4">
            <thead>
                <tr bgcolor="yellow">
                    <th> No</th>
                    <th> ID Barang</th>
                    <th> Nama Barang</th>
                    <th style="width:10%;"> Jumlah</th>
                    <th style="width:20%;"> Total</th>
                    <th> Kasir</th>
                    <th> Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; $hasil = $lihat -> jual();?>
                <?php 
                    $bayar = 0;
                    $jumlah = 0;
                    foreach($hasil as $isi){ 
                        $bayar += $isi['total'] * $isi['jumlah'];
                        $jumlah += $isi['jumlah'];
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo $isi['id_barang'];?></td>
                    <td><?php echo $isi['nama_barang'];?></td>
                    <td><?php echo $isi['jumlah'];?> </td>
                    <td><?php echo $isi['total'];?></td>
                    <td><?php echo $isi['nm_member'];?></td>
                    <td><?php echo $isi['tanggal_input'];?></td>
                </tr>
                <?php $no++; }?>
                <tr>
                    <th colspan="3">Total Terjual</td>
                    <th><?php echo $jumlah;?></td>
                    <th>Rp.<?php echo number_format($bayar);?>,-</td>
                    <th colspan="2" style="background:#ddd"></th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php }?>
              
</body>
</html>