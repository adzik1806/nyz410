<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Data Connection Pooling Supabase IPv4
$host     = "aws-0-ap-southeast-1.pooler.supabase.com"; 
$port     = "6543"; 
$dbname   = "postgres";
$password = "Adzikahmad0896"; 

// PERBAIKAN DI SINI: Gunakan titik dua (:) untuk memisahkan user postgres dengan ID project
$user     = "postgres:ahsuqcloonhvjhgwpwgo"; 

try {
    // Koneksi menggunakan PDO PostgreSQL lewat jalur Pooler IPv4
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Koneksi ke Supabase Gagal: " . $e->getMessage());
}
?>