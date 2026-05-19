<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Data baru dari akun Supabase kamu
$host     = "db.ahsuqcloonhvjhgwpwgo.supabase.co"; 
$port     = "5432";
$dbname   = "postgres";
$user     = "postgres"; 
$password = "Adzikahmad0896"; // Ganti dengan password database buatanmu

try {
    // Koneksi menggunakan PDO PostgreSQL (Otomatis mendukung SSL secure transport)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Koneksi ke Supabase Gagal: " . $e->getMessage());
}
?>