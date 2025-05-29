<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Website Service Mobil</title>
   <link rel="stylesheet" href="css/all.min.css">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <style>
      .hero {
         background: url('images/service.jpg') center/cover no-repeat;
         min-height: 60vh;
         position: relative;
         display: flex;
         align-items: center;
         justify-content: center;
      }

      .hero::before {
         content: '';
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background: rgba(0, 0, 0, 0.5);
      }

      .hero-content {
         position: relative;
         z-index: 1;
      }

      a.card {
         text-decoration: none;
         color: inherit;
         transition: transform 0.2s ease;
      }

      a.card:hover {
         transform: scale(1.03);
         box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      }
   </style>
</head>

<body class="d-flex flex-column vh-100 bg-light">
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
      <div class="container">
         <a href="index.php" class="navbar-brand fw-bold">Service Mobil</a>
      </div>
   </nav>

   <div class="hero text-white text-center">
      <div class="hero-content">
         <h1 class="fw-bold">Bengkel</h1>
         <p class="fs-4">Solusi terpercaya untuk perawatan mobil Anda</p>
      </div>
   </div>

   <div class="container mt-5">
      <div class="row text-center">
         <div class="col-md-4 mb-3">
            <a href="customer.php" class="card shadow p-3 bg-white d-block">
               <?php
               $q = "SELECT COUNT(*) AS total_customer FROM customer";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               ?>
               <h3 class="text-primary"><?= $data['total_customer'] ?></h3>
               <p class="fw-bold">Total Customer</p>
            </a>
         </div>
         <div class="col-md-4 mb-3">
            <a href="mobil.php" class="card shadow p-3 bg-white d-block">
               <?php
               $q = "SELECT COUNT(*) AS total_mobil FROM mobil";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               ?>
               <h3 class="text-success"><?= $data['total_mobil'] ?></h3>
               <p class="fw-bold">Jumlah Mobil Terdaftar</p>
            </a>
         </div>
         <div class="col-md-4 mb-3">
            <a href="transaksi.php" class="card shadow p-3 bg-white d-block">
               <?php
               $q = "SELECT MAX(tanggal_transaksi) AS terakhir_service FROM transaksi";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               $tgl = $data['terakhir_service'] ? date('d-m-Y', strtotime($data['terakhir_service'])) : 'Belum Ada';
               ?>
               <h3 class="text-danger"><?= $tgl ?></h3>
               <p class="fw-bold">Terakhir Service</p>
            </a>
         </div>
      </div>
   </div>

   <!-- Daftar Jenis Layanan -->
   <div class="container py-5">
      <h2 class="text-center mb-4">Daftar Jenis Layanan</h2>
      <div class="row">
         <?php
         $query = mysqli_query($conn, "SELECT * FROM jenis_layanan ORDER BY nama_layanan ASC");
         while ($row = mysqli_fetch_assoc($query)):
         ?>
            <div class="col-md-4 mb-3">
               <div class="card shadow-sm h-100">
                  <div class="card-body">
                     <h5 class="card-title text-primary"><?= htmlspecialchars($row['nama_layanan']) ?></h5>
                     <p class="card-text">Harga: <strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong></p>
                  </div>
               </div>
            </div>
         <?php endwhile; ?>
      </div>
   </div>

      <!-- Footer -->
   <footer class="bg-dark text-white text-center py-4 mt-auto">
      <div class="container">
         <p class="mb-1 fw-bold">© <?= date("Y") ?> Bengkel Service Mobil UMS</p>
         <p class="mb-0">Dibuat dengan ❤️ oleh Tim Mahasiswa Informatika</p>
      </div>
   </footer>

   <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>