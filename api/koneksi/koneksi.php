<?php
// Masukkan data asli dari akun TiDB Cloud kamu
$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com"; 
$user = "3dnB917rPV4v88s.root"; // Pastikan diisi username root lengkapmu dari TiDB
$pass = "dL48mU1Ba0xK2lKo"; // Pastikan diisi password asli TiDB-mu
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
