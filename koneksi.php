<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'service';

try{
   $conn = mysqli_connect($hostname, $username, $password, $database);
} catch (Exception $e) {
   echo "<b>Koneksi Gagal: <b>" . $e;
}