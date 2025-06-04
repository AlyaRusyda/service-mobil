<?php
include 'koneksi.php';

$notification = "";

// Tambah Transaksi
if (isset($_POST['submit'])) {
    $id_mobil = $_POST['id_mobil'];
    $tanggal = date('Y-m-d');
    $layanan = $_POST['layanan'];

    // Initial insert for transaksi to get id_transaksi
    mysqli_query($conn, "INSERT INTO transaksi (id_mobil, tanggal_transaksi, total_biaya) VALUES ('$id_mobil', '$tanggal', 0)");
    $id_transaksi = mysqli_insert_id($conn);

    $total_biaya = 0;

    if (is_array($layanan)) {
        foreach ($layanan as $id_layanan) {
            $id_layanan = mysqli_real_escape_string($conn, $id_layanan);

            if (empty($id_layanan)) continue;

            $res = mysqli_query($conn, "SELECT harga FROM jenis_layanan WHERE id_layanan = '$id_layanan'");
            $row = mysqli_fetch_assoc($res);
            if (!$row) continue;

            $subtotal = $row['harga'];
            $total_biaya += $subtotal;

            mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_layanan, subtotal) VALUES ('$id_transaksi', '$id_layanan', '$subtotal')");
        }
    }

    mysqli_query($conn, "UPDATE transaksi SET total_biaya = '$total_biaya' WHERE id_transaksi = '$id_transaksi'");
    $notification = "added";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-tools"></i> Service Mobil</a>
            <div class="ms-auto d-flex align-items-center gap-4">
                <a href="customer.php" class="nav-link">Customer</a>
                <a href="mobil.php" class="nav-link">Mobil</a>
                <a href="layanan.php" class="nav-link">Layanan</a>
                <a href="transaksi.php" class="nav-link">Transaksi</a>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container mt-4 mb-5">
            <h1 class="page-title text-center mb-4">Transaksi Service</h1>

            <?php if ($notification == 'added'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Transaksi berhasil <strong>ditambahkan</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Tambah Transaksi</div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label text-white">Mobil</label>
                            <select name="id_mobil" class="form-select" required>
                                <option value="">-- Pilih Mobil --</option>
                                <?php
                                $mobil = mysqli_query($conn, "SELECT * FROM mobil ORDER BY merk, model, no_polisi");
                                while ($m = mysqli_fetch_assoc($mobil)) {
                                    echo "<option value='{$m['id_mobil']}'>" . htmlspecialchars("{$m['no_polisi']} - {$m['merk']} {$m['model']}") . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div id="layanan-group">
                            <div class="row g-2 mb-2 layanan-item align-items-end">
                                <div class="col-md-10">
                                    <label class="form-label text-white">Layanan</label>
                                    <select name="layanan[]" class="form-select" required>
                                        <option value="">-- Pilih Layanan --</option>
                                        <?php
                                        $layanan_options_html = "";
                                        $layanans_query = mysqli_query($conn, "SELECT * FROM jenis_layanan ORDER BY nama_layanan");
                                        while ($l = mysqli_fetch_assoc($layanans_query)) {
                                            $layanan_options_html .= "<option value='{$l['id_layanan']}'>" . htmlspecialchars($l['nama_layanan']) . " (Rp. " . number_format($l['harga'], 0, ',', '.') . ")</option>";
                                        }
                                        echo $layanan_options_html;
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger w-100 btn-remove">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" id="btnTambahLayanan">Tambah Layanan</button>
                        <br>
                        <button type="submit" name="submit" class="btn btn-success">Simpan Transaksi</button>
                    </form>
                </div>
            </div>

            <h2 class="h4 mb-3 mt-5">Riwayat Transaksi</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No Polisi</th>
                        <th>Mobil</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Layanan</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn, "
            SELECT 
                t.id_transaksi,
                t.tanggal_transaksi,
                t.total_biaya,
                m.no_polisi,
                m.merk,
                m.model,
                c.nama AS nama_customer,
                GROUP_CONCAT(jl.nama_layanan SEPARATOR ', ') AS layanan_dilakukan
            FROM transaksi t
            JOIN mobil m ON t.id_mobil = m.id_mobil
            JOIN customer c ON m.id_customer = c.id_customer
            JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
            JOIN jenis_layanan jl ON dt.id_layanan = jl.id_layanan
            GROUP BY t.id_transaksi
            ORDER BY t.tanggal_transaksi DESC, t.id_transaksi DESC
        ");

                    if (mysqli_num_rows($q) > 0) {
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo "<tr class='text-center align-middle'>
                    <td>" . htmlspecialchars($row['no_polisi']) . "</td>
                    <td>" . htmlspecialchars($row['merk'] . ' ' . $row['model']) . "</td>
                    <td>" . htmlspecialchars($row['nama_customer']) . "</td>
                    <td>" . date('d-m-Y', strtotime($row['tanggal_transaksi'])) . "</td>
                    <td>" . htmlspecialchars($row['layanan_dilakukan']) . "</td>
                    <td>Rp. " . number_format($row['total_biaya'], 0, ',', '.') . "</td>
                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Belum ada data transaksi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Jaya Abadi - All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const layananOptionsHTML = `<?php echo str_replace(["\r", "\n"], '', addslashes($layanan_options_html)); ?>`;

        document.getElementById('btnTambahLayanan').addEventListener('click', function() {
            const layananGroup = document.getElementById('layanan-group');
            const newLayananItem = document.createElement('div');
            newLayananItem.classList.add('row', 'g-2', 'mb-2', 'layanan-item', 'align-items-end');
            newLayananItem.innerHTML = `
        <div class="col-md-10">
            <label class="form-label visually-hidden">Layanan</label> <select name="layanan[]" class="form-select" required>
                <option value="">-- Pilih Layanan --</option>
                ${layananOptionsHTML}
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100 btn-remove">Hapus</button>
        </div>
    `;
            layananGroup.appendChild(newLayananItem);
        });

        // Hapus layanan
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove')) {
                const item = e.target.closest('.layanan-item');
                const allItems = document.querySelectorAll('#layanan-group .layanan-item');
                if (allItems.length > 1) {
                    item.remove();
                } else {
                    // Optionally, alert the user or clear the fields of the last item instead of removing it.
                    alert('Minimal satu layanan harus ada.');
                    // Clear fields if it's the last one and you don't want to remove it
                    // item.querySelector('select[name="layanan[]"]').value = '';
                }
            }
        });
    </script>
</body>

</html>