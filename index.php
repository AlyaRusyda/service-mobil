<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Service Mobil</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="css/style.css">
   <style>
      .hero {
         background: url('images/service.jpg') center/cover no-repeat;
         min-height: 60vh;
         position: relative;
         display: flex;
         align-items: center;
         justify-content: center;
         text-align: center;
      }

      .hero::before {
         content: "";
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.7);
         /* Shadow hitam 50% */
         z-index: 1;
      }

      .hero .container {
         position: relative;
         z-index: 2;
         color: white;
      }
   </style>

</head>

<body>
   <nav class="navbar navbar-expand-lg">
      <div class="container">
         <a class="navbar-brand" href="index.php"><i class="fas fa-tools"></i> Service Mobil</a>
         <?php session_start(); ?>
         <div class="ms-auto d-flex align-items-center gap-4">
            <?php if (isset($_SESSION['is_login'])): ?>
               <a href="customer.php" class="nav-link">Customer</a>
               <a href="mobil.php" class="nav-link">Mobil</a>
               <a href="layanan.php" class="nav-link">Layanan</a>
               <a href="transaksi.php" class="nav-link">Transaksi</a>
               <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
               <a href="login.php" class="btn-login">Login</a>
            <?php endif; ?>
         </div>
   </nav>

   <section class="hero">
      <div class="container">
         <h1>Service Mobil Jaya Abadi</h1>
         <p>Layanan terpercaya untuk mobil kesayangan anda</p>
      </div>
   </section>

   <div class="container my-5">
      <div class="row text-center">
         <div class="col-md-4 mb-4">
            <div class="card p-4">
               <div class="icon"><i class="fas fa-users"></i></div>
               <?php
               $q = "SELECT COUNT(*) AS total_customer FROM customer";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               ?>
               <h3><?= $data['total_customer'] ?></h3>
               <p>Total Customer</p>
            </div>
         </div>
         <div class="col-md-4 mb-4">
            <div class="card p-4">
               <div class="icon"><i class="fas fa-car"></i></div>
               <?php
               $q = "SELECT COUNT(*) AS total_mobil FROM mobil";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               ?>
               <h3><?= $data['total_mobil'] ?></h3>
               <p>Mobil Terdaftar</p>
            </div>
         </div>
         <div class="col-md-4 mb-4">
            <div class="card p-4">
               <div class="icon"><i class="fas fa-calendar-check"></i></div>
               <?php
               $q = "SELECT COUNT(*) AS total_layanan FROM jenis_layanan";
               $r = mysqli_query($conn, $q);
               $data = mysqli_fetch_assoc($r);
               ?>
               <h3><?= $data['total_layanan'] ?></h3>
               <p>Jenis Layanan</p>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <h2 class="section-title">Jenis Layanan</h2>
      <div class="row">
         <?php
         $query = mysqli_query($conn, "SELECT * FROM jenis_layanan ORDER BY nama_layanan ASC");
         while ($row = mysqli_fetch_assoc($query)):
         ?>
            <div class="col-md-4 mb-4">
               <div class="card p-4">
                  <h5><?= htmlspecialchars($row['nama_layanan']) ?></h5>
                  <p>Harga: <strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong></p>
               </div>
            </div>
         <?php endwhile; ?>
      </div>
   </div>

   <footer>
      <p>© <?= date("Y") ?> <span>Service Mobil Jaya Abadi</span></p>
      <p>Dibuat oleh <span>Kelompok Undefined</span></p>
   </footer>

   <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>