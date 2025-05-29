<?php
include 'koneksi.php';

$notification = "";

// Tambah Layanan
if (isset($_POST['tambah'])) {
   $nama = $_POST['nama_layanan'];
   $harga = $_POST['harga'];
   mysqli_query($conn, "INSERT INTO jenis_layanan (nama_layanan, harga) VALUES ('$nama', '$harga')");
   $notification = "added";
}

// Ubah Layanan
if (isset($_POST['ubah'])) {
   $id = $_POST['id_layanan'];
   $nama = $_POST['nama_layanan'];
   $harga = $_POST['harga'];
   mysqli_query($conn, "UPDATE jenis_layanan SET nama_layanan='$nama', harga='$harga' WHERE id_layanan='$id'");
   $notification = "updated";
}

// Hapus Layanan
if (isset($_GET['hapus'])) {
   $id = $_GET['hapus'];
   mysqli_query($conn, "DELETE FROM jenis_layanan WHERE id_layanan = '$id'");
   $notification = "deleted";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>Manajemen Jenis Layanan</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
   <div class="container mt-5">
      <h1 class="mb-4">Manajemen Jenis Layanan</h1>

      <!-- Notifikasi -->
      <?php if ($notification == 'added'): ?>
         <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Data layanan berhasil <strong>ditambahkan</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
         </div>
      <?php elseif ($notification == 'updated'): ?>
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data layanan berhasil <strong>diperbarui</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
         </div>
      <?php elseif ($notification == 'deleted'): ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data layanan berhasil <strong>dihapus</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
         </div>
      <?php endif; ?>

      <!-- Form Tambah -->
      <div class="card mb-4">
         <div class="card-header bg-primary text-white">Tambah Layanan</div>
         <div class="card-body">
            <form method="post" class="row g-3">
               <div class="col-md-6">
                  <input type="text" name="nama_layanan" class="form-control" placeholder="Nama Layanan" required>
               </div>
               <div class="col-md-4">
                  <input type="number" name="harga" class="form-control" placeholder="Harga" required>
               </div>
               <div class="col-md-2">
                  <button type="submit" name="tambah" class="btn btn-primary w-100">Tambah</button>
               </div>
            </form>
         </div>
      </div>

      <!-- Tabel Layanan -->
      <table class="table table-bordered table-hover text-center align-middle">
         <thead class="table-dark">
            <tr>
               <th>ID</th>
               <th>Nama Layanan</th>
               <th>Harga</th>
               <th>Aksi</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $layanan_query = mysqli_query($conn, "SELECT * FROM jenis_layanan ORDER BY id_layanan ASC");
            while ($row = mysqli_fetch_assoc($layanan_query)):
            ?>
               <tr>
                  <td><?= $row['id_layanan'] ?></td>
                  <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                  <td>Rp. <?= number_format($row['harga'], 0, ',', '.') ?></td>
                  <td>
                     <!-- Tombol Ubah memicu modal -->
                     <button
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal<?= $row['id_layanan'] ?>">
                        Ubah
                     </button>
                     <a
                        href="?hapus=<?= $row['id_layanan'] ?>"
                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                        class="btn btn-danger btn-sm">
                        Hapus
                     </a>
                  </td>
               </tr>

               <!-- Modal Edit -->
               <div
                  class="modal fade"
                  id="editModal<?= $row['id_layanan'] ?>"
                  tabindex="-1"
                  aria-labelledby="editModalLabel<?= $row['id_layanan'] ?>"
                  aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <form method="post">
                           <div class="modal-header">
                              <h5 class="modal-title" id="editModalLabel<?= $row['id_layanan'] ?>">Ubah Data Layanan</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                           </div>
                           <div class="modal-body">
                              <input type="hidden" name="id_layanan" value="<?= $row['id_layanan'] ?>">
                              <div class="mb-3">
                                 <label class="form-label">Nama Layanan</label>
                                 <input
                                    type="text"
                                    name="nama_layanan"
                                    class="form-control"
                                    value="<?= htmlspecialchars($row['nama_layanan']) ?>"
                                    required>
                              </div>
                              <div class="mb-3">
                                 <label class="form-label">Harga</label>
                                 <input
                                    type="number"
                                    name="harga"
                                    class="form-control"
                                    value="<?= $row['harga'] ?>"
                                    required>
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

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
