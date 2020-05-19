
<?php 
	 
	error_reporting(0);
	session_start();

		 if($_SESSION['admin']){
			
			require 'config.php';
			
			include $view;
		
			$lihat = new view($config);
			$toko = $lihat -> toko();
		
			//  admin
				include 'admin/template/header.php';
				include 'admin/template/sidebar.php';
					if(!empty($_GET['page'])){
						include 'admin/module/'.$_GET['page'].'/index.php';
					}else{
						include 'admin/template/home.php';
					}
				include 'admin/template/footer.php';
			// end admin
		}else{
			echo '<script>window.location="login.php";</script>';
		}
?>

