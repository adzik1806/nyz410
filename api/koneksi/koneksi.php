<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// SUDAH DIUBAH KE JALUR POOLER IPV4 (Mengatasi error Vercel)
$host     = "aws-0-ap-southeast-1.pooler.supabase.com"; 
$port     = "6543"; // Wajib port 6543 untuk connection pooling
$dbname   = "postgres";
$user     = "postgres.ahsuqcloonhvjhgwpwgo"; // Wajib menggunakan Project ID lengkap kamu
$password = "Adzikahmad0896"; 

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