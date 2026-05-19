<?php
// Pastikan tidak ada spasi atau karakter aneh sebelum <?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com"; 
$user = "3dnB917rPV4v88s.root"; 
$pass = "BerEuah4oOOVbE8x"; 
$db   = "komunitas_bola"; 
$port = 4000; 

$conn = mysqli_init();

// Menghubungkan ke TiDB dengan SSL (Wajib untuk TiDB Cloud)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>