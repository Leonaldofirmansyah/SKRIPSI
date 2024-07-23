<?php
include "includes/config.php";
session_start();
if(!isset($_SESSION['nama_lengkap'])){
    echo "<script>location.href='login.php'</script>";
}

$config = new Config();
$db = $config->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Bootstrap -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">
    <style>
    /* Custom CSS for Navbar */
    .navbar-custom {
        background-color: #000000; /* Warna latar belakang hitam */
    }

    .navbar-custom .navbar-brand {
        color: blue; /* Warna teks brand putih */
    }

    .navbar-custom .navbar-nav > li > a {
        color: #ffffff; /* Warna teks tautan putih */
    }

    .navbar-custom .navbar-nav > li > a:hover {
        color: #f0f0f0; /* Warna teks tautan saat hover */
    }

    .navbar-custom .dropdown-menu {
        background-color: #333333; /* Warna latar belakang menu dropdown */
    }

    .navbar-custom .dropdown-menu > li > a {
        color: #ffffff; /* Warna teks item menu dropdown */
    }

    .navbar-custom .dropdown-menu > li > a:hover {
        background-color: #555555; /* Warna latar belakang item menu dropdown saat hover */
    }
    </style>
  </head>
  <body style="background: #ffffff url(images/back1.jpg) left bottom fixed;">
  
    <nav class="navbar navbar-default navbar-static-top navbar-custom">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
		<img src="images/logo.png" alt="Company Logo">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
    
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <?php if (isset($_SESSION['role'])) {
              if ($_SESSION['role'] === base64_encode('Admin')) { ?>
               <ul class="nav navbar-nav navbar-right">
		  <li><a href="barang.php">Data Barang</a></li>
            </li>
            <?php }
            } ?>
          </ul>
         

				<?php if (isset($_SESSION['role'])) {
              if ($_SESSION['role'] === base64_encode('User')) { ?>
              </ul>
			  <li>
            <?php }
            } ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['role'])) {
                  if ($_SESSION['role'] === base64_encode('User')) { ?>
				  <li><a href="index.php">Barang</a></li>
                <li><a href="view_pesanan.php">Pembelian</a></li>
				<li><a href="transaksi.php">Pengiriman</a></li>
                    <li><a href="profile.php">Profile</a></li>
					<li><a href="logout.php">Logout</a></li>

                  <?php } elseif ($_SESSION['role'] === base64_encode('Admin')) { ?>
                  <li><a href="profile.php">Profile</a></li>
				  <li><a href="logout.php">Logout</a></li>
                  <li role="separator" class="divider"></li>
                  <?php }
                } ?>
              </ul>
            </li>
            <li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
  
    <div class="container">
