<?php
$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$user = "3dnB917rPV4v88s.root";     // Default XAMPP
$pass = "kiuNEfdEu49G3rhh";         // Default XAMPP kosong
$db   = "komunitas_bola"; // Pastikan nama database sesuai yang Anda buat di phpMyAdmin
$port = 4000; // TiDB Cloud wajib menggunakan port 4000

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>