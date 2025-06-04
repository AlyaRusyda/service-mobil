<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'service1';

try{
   $conn = mysqli_connect($hostname, $username, $password, $database);
} catch (Exception $e) {
   echo "<b>Koneksi Gagal: <b>" . $e;
}