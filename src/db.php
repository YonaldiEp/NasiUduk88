<?php

$host = "sgp.domcloud.co"; // <-- Ganti dengan Hostname Anda
$user = "nasiuduk88";      // <-- Ganti dengan Nama pengguna Anda
$pass = "y_BK2b+q14bgUVX)82";  // <-- GANTI DENGAN KATA SANDI MYSQL ANDA
$db   = "nasiuduk88_db";   // <-- Nama database sudah benar

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>