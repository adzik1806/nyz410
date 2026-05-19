<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// SUDAH DIUBAH KE JALUR POOLER IPV4 (Mengatasi error Vercel)
$host     = "db.ahsuqcloonhvjhgwpwgo.supabase.co"; 
$port     = "5432"; 
$dbname   = "postgres";
$user     = "postgres"; 
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