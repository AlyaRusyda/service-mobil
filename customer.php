<?php
include 'koneksi.php';

// Inisialisasi notifikasi
$notification = "";

// Tambah Customer
if (isset($_POST['tambah'])) {
   $nama = $_POST['nama'];
   $no_telepon = $_POST['no_telepon'];
   mysqli_query($conn, "INSERT INTO customer (nama, no_telepon) VALUES ('$nama', '$no_telepon')");
   $notification = "added";
}

// Ubah Customer
if (isset($_POST['ubah'])) {
   $id = $_POST['id_customer'];
   $nama = $_POST['nama'];
   $no_telepon = $_POST['no_telepon'];
   mysqli_query($conn, "UPDATE customer SET nama='$nama', no_telepon='$no_telepon' WHERE id_customer='$id'");
   $notification = "updated";
}

// Hapus Customer
if (isset($_GET['hapus'])) {
   $id = $_GET['hapus'];
   mysqli_query($conn, "DELETE FROM customer WHERE id_customer = '$id'");
   $notification = "deleted";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Data Customer</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
   <style>
      .container {
         margin-top: 50px;
      }
   </style>
</head>

<body>
   <div class="container">
      <h1 class="mb-4">Manajemen Data Customer</h1>

      <!-- Alert -->
      <?php if ($notification == 'added'): ?>
         <div class="alert alert-primary alert-dismissible fade show" role="alert">
            Customer berhasil <strong>ditambahkan</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
      <?php elseif ($notification == 'updated'): ?>
         <div class="alert alert-success alert-dismissible fade show" role="alert">
            Customer berhasil <strong>diperbarui</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
      <?php elseif ($notification == 'deleted'): ?>
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Customer berhasil <strong>dihapus</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
      <?php endif; ?>

      <!-- Form Tambah Customer -->
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

      <!-- Tabel Customer -->
      <table class="table table-bordered table-hover">
         <thead class="table-dark text-center">
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
            $query = mysqli_query($conn, "SELECT * FROM customer");
            while ($row = mysqli_fetch_assoc($query)): ?>
               <tr class="align-middle text-center">
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['nama']) ?></td> <!-- Tampil teks saja -->
                  <td><?= htmlspecialchars($row['no_telepon']) ?></td> <!-- Tampil teks saja -->
                  <td class="d-flex gap-2 justify-content-center">
                     <!-- Tombol Ubah untuk buka modal edit -->
                     <button
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEdit"
                        data-id="<?= $row['id_customer'] ?>"
                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                        data-telp="<?= htmlspecialchars($row['no_telepon']) ?>">
                        Ubah
                     </button>
                     <a href="?hapus=<?= $row['id_customer'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">Hapus</a>
                  </td>
               </tr>
            <?php endwhile; ?>
         </tbody>
      </table>

      <!-- Modal Edit -->
      <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
         <div class="modal-dialog">
            <form method="post" class="modal-content">
               <div class="modal-header bg-warning">
                  <h5 class="modal-title" id="modalEditLabel">Edit Customer</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

      <script src="js/bootstrap.bundle.min.js"></script>
      <script>
         const modalEdit = document.getElementById('modalEdit');
         modalEdit.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const telp = button.getAttribute('data-telp');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-telp').value = telp;
         });
      </script>
   </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>