<?php  
include "header.php";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != base64_encode('Admin')) { ?>
        <div class="container">
            <div class="text-center">Halaman ini hanya untuk hak akses Admin saja!</div>
        </div>
    <?php } else {
include_once 'includes/user.inc.php';
$pro = new User($db);
$stmt = $pro->readAll();
?>
    <div class="row">
        <div class="col-md-6 text-left">
            <h4>Data Pengguna</h4>
        </div>
        <div class="col-md-6 text-right">
            <button onclick="location.href='user-baru.php'" class="btn btn-primary" id="btn-tambah">Tambah Data</button>
        </div>
    </div>
    <br/>
    <table width="100%" class="table table-striped table-bordered" id="tabeldata">
        <thead>
            <tr>
                <th width="30px">ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th width="150px">Aksi</th>
            </tr>
        </thead>
        <tbody>
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    ?>
            <tr>
                <td><?php echo $row['id_pengguna'] ?></td>
    	    <td><?php echo $row['nama_lengkap'] ?></td>
    	    <td><?php echo $row['username'] ?></td>
            <td><?php echo $row['email'] ?></td>
            <td><?php echo $row['role'] ?></td>
            <td class="text-center">
    		  <a href="user-ubah.php?id=<?php echo $row['id_pengguna'] ?>" class="btn btn-warning"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
    		  <a href="user-hapus.php?id=<?php echo $row['id_pengguna'] ?>" onclick="return confirm('Yakin ingin menghapus data')" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
    	    </td>
            </tr>
    <?php
    }
    ?>
        </tbody>
    </table>
<?php
    }
} ?>
