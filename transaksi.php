<?php
include 'koneksi.php';

$notification = "";

// Tambah Transaksi
if (isset($_POST['submit'])) {
    $id_mobil = $_POST['id_mobil'];
    $tanggal = date('Y-m-d');
    $layanan = $_POST['layanan'];
    $jumlah = $_POST['jumlah'];

    mysqli_query($conn, "INSERT INTO transaksi (id_mobil, tanggal_transaksi, total_biaya) VALUES ('$id_mobil', '$tanggal', 0)");
    $id_transaksi = mysqli_insert_id($conn);

    $total_biaya = 0;

    for ($i = 0; $i < count($layanan); $i++) {
        $id_layanan = $layanan[$i];
        $qty = $jumlah[$i];

        $res = mysqli_query($conn, "SELECT harga FROM jenis_layanan WHERE id_layanan = '$id_layanan'");
        $row = mysqli_fetch_assoc($res);
        if (!$row) continue;

        $subtotal = $row['harga'] * $qty;
        $total_biaya += $subtotal;

        mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_layanan, subtotal) VALUES ('$id_transaksi', '$id_layanan', '$subtotal')");
    }

    mysqli_query($conn, "UPDATE transaksi SET total_biaya = '$total_biaya' WHERE id_transaksi = '$id_transaksi'");
    $notification = "added";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4">Transaksi Service</h1>

    <?php if ($notification == 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Transaksi berhasil <strong>ditambahkan</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Form Tambah Transaksi -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Tambah Transaksi</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Mobil</label>
                    <select name="id_mobil" class="form-select" required>
                        <option value="">-- Pilih Mobil --</option>
                        <?php
                        $mobil = mysqli_query($conn, "SELECT * FROM mobil");
                        while ($m = mysqli_fetch_assoc($mobil)) {
                            echo "<option value='{$m['id_mobil']}'>{$m['no_polisi']} - {$m['merk']} {$m['model']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div id="layanan-group">
                    <div class="row g-2 mb-2 layanan-item">
                        <div class="col-md-6">
                            <select name="layanan[]" class="form-select" required>
                                <option value="">-- Pilih Layanan --</option>
                                <?php
                                $layanans = mysqli_query($conn, "SELECT * FROM jenis_layanan");
                                while ($l = mysqli_fetch_assoc($layanans)) {
                                    echo "<option value='{$l['id_layanan']}'>{$l['nama_layanan']} (Rp. ".number_format($l['harga'], 0, ',', '.').")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove">Hapus</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary mb-3" id="btnTambahLayanan">Tambah Layanan</button>
                <br>
                <button type="submit" name="submit" class="btn btn-success">Simpan Transaksi</button>
            </form>
        </div>
    </div>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
        <tr>
            <th>No</th>
            <th>No Polisi</th>
            <th>Tanggal</th>
            <th>Total Biaya</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $q = mysqli_query($conn, "
            SELECT t.*, m.no_polisi FROM transaksi t
            JOIN mobil m ON t.id_mobil = m.id_mobil
            ORDER BY t.tanggal_transaksi DESC
        ");
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<tr class='text-center'>
                    <td>{$no}</td>
                    <td>{$row['no_polisi']}</td>
                    <td>" . date('d-m-Y', strtotime($row['tanggal_transaksi'])) . "</td>
                    <td>Rp. " . number_format($row['total_biaya'], 0, ',', '.') . "</td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script>
// Tambah layanan dinamis
document.getElementById('btnTambahLayanan').addEventListener('click', function () {
    const group = document.querySelector('.layanan-item');
    const clone = group.cloneNode(true);
    clone.querySelector('select').value = '';
    clone.querySelector('input').value = '';
    document.getElementById('layanan-group').appendChild(clone);
});

// Hapus layanan
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove')) {
        const item = e.target.closest('.layanan-item');
        const allItems = document.querySelectorAll('.layanan-item');
        if (allItems.length > 1) {
            item.remove();
        }
    }
});
</script>
</body>
</html>
