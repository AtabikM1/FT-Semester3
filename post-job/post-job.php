<?php
session_start();
include '../include/koneksi.php';

// Memastikan user sudah login dan memiliki hak akses perusahaan
if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['username']; // Menggunakan session untuk user_id
} else {
    echo json_encode(['status' => 'error', 'message' => 'User is not logged in.']);
    exit;
}

// Memeriksa apakah user adalah perusahaan
$sqlPerusahaan = "SELECT * FROM perusahaan WHERE User_username = ?";
$stmt = sqlsrv_prepare($conn, $sqlPerusahaan, array(&$user_id));
sqlsrv_execute($stmt);
$perusahaan = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$perusahaan) {
    echo json_encode(['status' => 'error', 'message' => 'User is not associated with a company.']);
    exit;
}

// Menangani form post job
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil input dari form dan melakukan sanitasi
    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $tipe_loker = $_POST['tipe_loker'];
    $lokasi = htmlspecialchars($_POST['lokasi']);
    $gaji = $_POST['gaji'];
    $tanggal_post = date('Y-m-d H:i:s');
    $tanggal_deadline = $_POST['tanggal_deadline'];

    // Pastikan tipe_loker sesuai dengan nilai yang valid
    if (!in_array($tipe_loker, ['Full Time', 'Part Time', 'Magang'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid job type.']);
        exit;
    }

    // Generate idLoker secara acak
    $idLoker = 'L' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)); // Menghasilkan ID seperti L12345

    // Insert query untuk menyimpan lowongan kerja
    $sqlInsertLoker = "INSERT INTO loker (idLoker, judul, deskripsi, tipe_loker, lokasi, gaji, Username_perusahaan, tanggal_post, tanggal_deadline)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = array(
        &$idLoker,
        &$judul,
        &$deskripsi,
        &$tipe_loker,
        &$lokasi,
        &$gaji,
        &$user_id,  // User yang sedang login (perusahaan)
        &$tanggal_post,
        &$tanggal_deadline
    );

    $stmtInsertLoker = sqlsrv_prepare($conn, $sqlInsertLoker, $params);

    if (sqlsrv_execute($stmtInsertLoker)) {
        echo json_encode(['status' => 'success', 'message' => 'Job posted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error posting job.']);
    }
}
?>
