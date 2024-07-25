<?php
session_start();
include_once '../includes/db_connect.php';
include_once '../includes/config.php';

$config = new Config();
$db = $config->getConnection();

// Ambil data transaksi dari database
$query = "SELECT id_pengguna, id_transaksi FROM transaksi GROUP BY id_pengguna";
$stmt = $db->prepare($query);
$stmt->execute();
$pengguna_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengiriman</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-custom {
            width: 100%;
        }
        .table-custom th,
        .table-custom td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table-custom thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table-custom tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <h2 class="mb-4">Form Pengiriman</h2>
    <div class="row">
        <!-- Select Box untuk Memilih Pengguna -->
        <div class="col-md-12 mb-4">
            <label for="pengguna">Pilih Pengguna</label>
            <select id="pengguna" class="form-control">
                <option value="">-- Pilih Pengguna --</option>
                <?php foreach ($pengguna_list as $pengguna): ?>
                    <option value="<?php echo $pengguna['id_pengguna']; ?>">
                        <?php echo htmlspecialchars($pengguna['id_pengguna']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tabel untuk Menampilkan Data Transaksi -->
        <div class="col-md-12">
            <table class="table table-custom" id="transaksi-table" style="display: none;">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Form Pengiriman Perusahaan -->
        <div class="col-md-6 form-pengiriman" style="display: none;">
            <div class="card mb-4">
                <div class="card-header">
                    Pengiriman
                </div>
                <div class="card-body">
                    <form action="proses_pengiriman_perusahaan.php" method="post">
                        <div class="form-group">
                            <label for="nama">Pihak Pengirim</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Nomor Resi</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak</label>
                            <input type="text" class="form-control" id="kontak" name="kontak" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('pengguna').addEventListener('change', function() {
        var penggunaId = this.value;
        var forms = document.querySelectorAll('.form-pengiriman');
        var transaksiTable = document.getElementById('transaksi-table');
        var tbody = transaksiTable.querySelector('tbody');

        if (penggunaId) {
            forms.forEach(function(form) {
                form.style.display = 'block';
            });

            // Mengambil data transaksi dengan AJAX
            $.ajax({
                url: 'get_transaksi.php',
                method: 'GET',
                data: { id_pengguna: penggunaId },
                dataType: 'json',
                success: function(data) {
                    tbody.innerHTML = '';

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    data.forEach(function(transaksi) {
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${transaksi.id_transaksi}</td>
                            <td>${transaksi.nama_item}</td>
                            <td>${transaksi.jumlah_transaksi}</td>
                            <td>${transaksi.harga_item}</td>
                            <td>${transaksi.total}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    transaksiTable.style.display = 'table';
                },
                error: function() {
                    alert('Gagal mengambil data transaksi');
                }
            });
        } else {
            forms.forEach(function(form) {
                form.style.display = 'none';
            });

            transaksiTable.style.display = 'none';
        }
    });
</script>
</body>
</html>
