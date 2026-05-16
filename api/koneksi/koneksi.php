<?php
// Ambil data asli TiDB Cloud milikmu
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com"; 
$user = "3dnB917rPV4v88s...root"; // Masukkan username root lengkapmu di sini
$pass = "PasswordPanjangDariTiDBKamu"; // Masukkan password asli TiDB-mu di sini
$db   = "komunitas_bola"; 
$port = 4000; 

// 1. Inisialisasi objek MySQLi
$conn = mysqli_init();

// 2. Aktifkan bendera SSL agar koneksi aman (Mengatasi error insecure transport)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// 3. Lakukan koneksi ke TiDB Cloud dengan port 4000
if (!mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>
