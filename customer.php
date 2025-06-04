<?php
include 'koneksi.php';

$notification = "";

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    // PERHATIAN: Kode Anda rentan terhadap SQL Injection.
    // Sangat disarankan untuk menggunakan prepared statements.
    mysqli_query($conn, "INSERT INTO customer (nama, no_telepon) VALUES ('$nama', '$no_telepon')");
    $notification = "added";
}

if (isset($_POST['ubah'])) {
    $id = $_POST['id_customer'];
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];
    // PERHATIAN: Gunakan prepared statements di sini juga.
    mysqli_query($conn, "UPDATE customer SET nama='$nama', no_telepon='$no_telepon' WHERE id_customer='$id'");
    $notification = "updated";
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // PERHATIAN: Gunakan prepared statements di sini juga.
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer = '$id'");
    $notification = "deleted";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Customer</title>
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

    <main class=" content-wrapper container mt-4">
        <h1 class="text-center mb-4">Manajemen Data Customer</h1>

        <?php if ($notification == 'added'): ?>
            <div class="alert alert-dismissible fade show" role="alert">
                Customer berhasil <strong>ditambahkan</strong>.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($notification == 'updated'): ?>
            <div class="alert alert-dismissible fade show" role="alert">
                Customer berhasil <strong>diperbarui</strong>.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($notification == 'deleted'): ?>
            <div class="alert alert-dismissible fade show" role="alert">
                Customer berhasil <strong>dihapus</strong>.
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Tambah Customer</div>
            <div class="card-body">
                <form method="post" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="nama" class="form-control" placeholder="Nama Customer" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="no_telepon" class="form-control" placeholder="No Telepon" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Pastikan koneksi $conn ada dan query berjalan dengan baik
                if ($conn) {
                    $query = mysqli_query($conn, "SELECT * FROM customer");
                    if ($query) {
                        while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        data-id="<?= $row['id_customer'] ?>"
                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                        data-telp="<?= htmlspecialchars($row['no_telepon']) ?>">Ubah</button>
                                    <a href="?hapus=<?= $row['id_customer'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                <?php endwhile;
                    } else {
                        // Tambahkan penanganan error jika query gagal
                        echo "<tr><td colspan='4'>Gagal mengambil data customer: " . mysqli_error($conn) . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Koneksi database gagal.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_customer" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-telp" class="form-label">No Telepon</label>
                        <input type="text" name="no_telepon" id="edit-telp" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="ubah" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>&copy; <?= date('Y') ?> Jaya Abadi - All rights reserved.</small>
        </div>
    </footer>

    <script>
        const modalEdit = document.getElementById('modalEdit');
        if (modalEdit) { // Pastikan elemen modalEdit ada
            modalEdit.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const telp = button.getAttribute('data-telp');

                const modalTitle = modalEdit.querySelector('.modal-title');
                const idInput = modalEdit.querySelector('#edit-id');
                const namaInput = modalEdit.querySelector('#edit-nama');
                const telpInput = modalEdit.querySelector('#edit-telp');

                // Pastikan elemen-elemen input di dalam modal ada
                if (idInput) idInput.value = id;
                if (namaInput) namaInput.value = nama;
                if (telpInput) telpInput.value = telp;
                // Anda bisa juga mengubah judul modal jika diperlukan
                // if (modalTitle && nama) modalTitle.textContent = 'Edit Customer: ' + nama;
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>