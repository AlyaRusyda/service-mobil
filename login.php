<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-dark text-white">
    <div class="card bg-secondary text-white p-4 shadow" style="width: 350px;">
        <h2 class="text-center">Login</h2>
        <?php if (isset($_GET['pesan'])) { ?>
            <div class="alert alert-danger"><?= $_GET['pesan'] ?></div>
        <?php } ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control shadow-lg border-0" id="username" name="username" placeholder="admin" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control shadow-lg border-0" id="password" name="password" placeholder="admin123" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            <a href="index.php" class="btn btn-light w-100 mt-2">Back To Service Web</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin123') {
        session_start();
        $_SESSION['is_login'] = true;
        header('Location:index.php');
    } else {
        header('Location:login.php?pesan=Username atau Password salah!!!');
    }
}
