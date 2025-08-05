<?php

$host = "sgp.domcloud.co";
$user = "nasiuduk88";
$pass = "xsJ2VR6++J1zcN81-a";
$db   = "nasiuduk88_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
