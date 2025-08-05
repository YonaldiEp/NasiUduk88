<?php

$host = "sgp.domcloud.co";
$user = "nasiuduk88";
$pass = "825SaH3K-++sWoFzl9";
$db   = "nasiuduk88_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
