<?php
/*
  | Source Code Aplikasi Penjualan Barang Kasir dengan PHP & MYSQL
  | 
  | @package   : pos-kasir-php
  | @file	   : config.php ( untuk mengatur koneksi php ke database mysql )
  | @author    : fauzan1892 / Fauzan Falah
  | @copyright : Copyright (c) 2017-2021 Codekop.com (https://www.codekop.com)
  | @blog      : https://www.codekop.com/read/source-code-aplikasi-penjualan-barang-kasir-dengan-php-amp-mysql-gratis.html
  |
  | 
  | keterangan : untuk login aplikasi dengan username : admin dan password : 123
  | 
  | 
  | 
 */

date_default_timezone_set("Asia/Jakarta");
error_reporting(0);

	// sesuaikan dengan server anda
	$host 	= 'localhost'; // host server
	$user 	= 'root';  // username server
	$pass 	= ''; // password server, kalau pakai xampp kosongin saja
	$dbname = 'db_toko'; // nama database anda
	
	try{
		$config = new PDO("mysql:host=$host;dbname=$dbname;", $user,$pass);
		//echo 'sukses';
	}catch(PDOException $e){
		echo 'KONEKSI GAGAL' .$e -> getMessage();
	}
	
	$view = 'fungsi/view/view.php'; // direktori fungsi select data
?>

