<?php
include 'koneksi.php';

$notification = "";

// Tambah Mobil
if (isset($_POST['tambah'])) {
    $no_polisi = $_POST['no_polisi'];
    $merk = $_POST['merk'];
    $model = $_POST['model'];
    $id_customer = $_POST['id_customer'];

    mysqli_query($conn, "INSERT INTO mobil (no_polisi, merk, model, id_customer) VALUES ('$no_polisi', '$merk', '$model', '$id_customer')");
    $notification = "added";
}

// Ubah Mobil
if (isset($_POST['ubah'])) {
    $id_mobil = $_POST['id_mobil'];
    $merk = $_POST['merk'];
    $model = $_POST['model'];
    $id_customer = $_POST['id_customer'];

    mysqli_query($conn, "UPDATE mobil SET merk='$merk', model='$model', id_customer='$id_customer' WHERE id_mobil='$id_mobil'");
    $notification = "updated";
}

// Hapus Mobil
if (isset($_GET['hapus'])) {
    $id_mobil = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mobil WHERE id_mobil = '$id_mobil'");
    $notification = "deleted";
}

// Ambil data customer untuk dropdown
$customer_query = mysqli_query($conn, "SELECT id_customer, nama FROM customer");
$customers = [];
while ($row = mysqli_fetch_assoc($customer_query)) {
    $customers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Data Mobil</title>
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
            <h1 class="page-title text-center">Manajemen Data Mobil</h1> <?php if ($notification == 'added'): ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    Data mobil berhasil <strong>ditambahkan</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($notification == 'updated'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data mobil berhasil <strong>diperbarui</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($notification == 'deleted'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Data mobil berhasil <strong>dihapus</strong>.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Tambah Mobil</div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="no_polisi" class="form-control" placeholder="No Polisi" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="merk" class="form-control" placeholder="Merk" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="model" class="form-control" placeholder="Model" required>
                        </div>
                        <div class="col-md-3">
                            <select name="id_customer" class="form-select" required>
                                <option value="" disabled selected>Pilih Customer</option>
                                <?php foreach ($customers as $cust): ?>
                                    <option value="<?= $cust['id_customer'] ?>"><?= htmlspecialchars($cust['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" name="tambah" class="btn btn-primary">Tambah Mobil</button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>No Polisi</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Customer</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $mobil_query = mysqli_query($conn, "
                        SELECT m.id_mobil, m.no_polisi, m.merk, m.model, m.id_customer, c.nama 
                        FROM mobil m
                        LEFT JOIN customer c ON m.id_customer = c.id_customer
                        ORDER BY m.id_mobil ASC
                    ");
                    while ($row = mysqli_fetch_assoc($mobil_query)):
                    ?>
                        <tr>
                            <td><?= $row['id_mobil'] ?></td>
                            <td><?= htmlspecialchars($row['no_polisi']) ?></td>
                            <td><?= htmlspecialchars($row['merk']) ?></td>
                            <td><?= htmlspecialchars($row['model']) ?></td>
                            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                            <td>
                                <button
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal<?= $row['id_mobil'] ?>">
                                    Ubah
                                </button>
                                <a
                                    href="?hapus=<?= $row['id_mobil'] ?>"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')"
                                    class="btn btn-danger btn-sm">
                                    Hapus
                                </a>
                            </td>
                        </tr>

                        <div
                            class="modal fade"
                            id="editModal<?= $row['id_mobil'] ?>"
                            tabindex="-1"
                            aria-labelledby="editModalLabel<?= $row['id_mobil'] ?>"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?= $row['id_mobil'] ?>">Ubah Data Mobil</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_mobil" value="<?= $row['id_mobil'] ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Merk</label>
                                                <input
                                                    type="text"
                                                    name="merk"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($row['merk']) ?>"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Model</label>
                                                <input
                                                    type="text"
                                                    name="model"
                                                    class="form-control"
                                                    value="<?= htmlspecialchars($row['model']) ?>"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Customer</label>
                                                <select name="id_customer" class="form-select" required>
                                                    <option value="" disabled>Pilih Customer</option>
                                                    <?php foreach ($customers as $cust): ?>
                                                        <option value="<?= $cust['id_customer'] ?>" <?= ($cust['id_customer'] == $row['id_customer']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($cust['nama']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="ubah" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
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
</body>

</html>