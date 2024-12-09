<?php
// Tambahkan header CORS yang lengkap
// header("Access-Control-Allow-Origin: *");  // Mengizinkan akses dari semua domain
// header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");  // Metode yang diizinkan
// header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Header yang diizinkan

// Menangani preflight request untuk metode POST
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Konfigurasi server dan database
$serverName = "localhost";  // Ganti dengan nama server atau IP Address SQL Server
$connectionOptions = array(
    "Database" => "db_polinemakarir",  // Ganti dengan nama database SQL Server
    "Uid" => "SA",  // Ganti dengan username SQL Server
    "PWD" => "Ankaramessi123"   // Ganti dengan password SQL Server
);

// Mencoba membuat koneksi ke SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Mengecek jika koneksi berhasil
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi ke SQL Server gagal!"]);
    exit;
}
?>