<?php 
/*
  | Source Code Aplikasi Penjualan Barang Kasir dengan PHP & MYSQL
  | 
  | @package   : pos-kasir-php
  | @file	   : index.php 
  | @author    : fauzan1892 / Fauzan Falah
  | @copyright : Copyright (c) 2017-2021 Codekop.com (https://www.codekop.com)
  | @blog      : https://www.codekop.com/read/source-code-aplikasi-penjualan-barang-kasir-dengan-php-amp-mysql-gratis.html
  | 
  | 
  | 
  | 
 */

	@ob_start();
	session_start();

        if(!empty($_SESSION['admin'])){
                require 'config.php';
                require_once 'fungsi/csrf.php';
                csrf_get_token();
                csrf_guard();
                include $view;
                $lihat = new view($config);
		$toko = $lihat -> toko();
		//  admin
			include 'admin/template/header.php';
                        include 'admin/template/sidebar.php';
                                $allowedPages = array(
                                        'barang',
                                        'barang/details',
                                        'barang/edit',
                                        'kategori',
                                        'pengaturan',
                                        'jual',
                                        'laporan',
                                        'user'
                                );
                                if(!empty($_GET['page'])){
                                        $requestedPage = (string) $_GET['page'];
                                        if(in_array($requestedPage, $allowedPages, true)){
                                                $moduleRoot = realpath(__DIR__.'/admin/module');
                                                $modulePath = realpath(__DIR__.'/admin/module/'.$requestedPage.'/index.php');
                                                if($moduleRoot !== false && $modulePath !== false && strpos($modulePath, $moduleRoot) === 0 && is_file($modulePath)){
                                                        include $modulePath;
                                                }else{
                                                        include 'admin/template/home.php';
                                                }
                                        }else{
                                                include 'admin/template/home.php';
                                        }
                                }else{
                                        include 'admin/template/home.php';
                                }
			include 'admin/template/footer.php';
		// end admin
	}else{
		echo '<script>window.location="login.php";</script>';
		exit;
	}
?>

