<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../include/koneksi.php';

// Memastikan user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User is not logged in.']);
    exit;
}

$user_id = $_SESSION['username']; // Menggunakan username dari session

// Memastikan user adalah perusahaan
$sqlPerusahaan = "SELECT * FROM perusahaan WHERE User_username = ?";
$stmt = sqlsrv_prepare($conn, $sqlPerusahaan, [$user_id]);

if (!$stmt || !sqlsrv_execute($stmt) || !sqlsrv_fetch($stmt)) {
    echo json_encode(['status' => 'error', 'message' => 'User is not associated with a company.']);
    exit;
}

// Menangani request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = htmlspecialchars($_POST['judul']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $tipe_loker = $_POST['tipe_loker'];
    $lokasi = htmlspecialchars($_POST['lokasi']);
    $gaji = !empty($_POST['gaji']) ? htmlspecialchars($_POST['gaji']) : null; // Null jika kosong
    $tanggal_deadline = date('Y-m-d', strtotime($_POST['tanggal_deadline']));

    // Validasi tipe loker
    $valid_types = ['Full Time', 'Part Time', 'Magang'];
    if (!in_array($tipe_loker, $valid_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid job type.']);
        exit;
    }

    // Generate ID loker
    $idLoker = 'L' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)); // ID unik dengan panjang maksimal 32 karakter

    // Query untuk insert data
    $sqlInsertLoker = "INSERT INTO loker (idLoker, judul, deskripsi, tipe_loker, lokasi, gaji, Username_perusahaan, tanggal_post, tanggal_deadline)
                       VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE(), ?)";
    $params = [$idLoker, $judul, $deskripsi, $tipe_loker, $lokasi, $gaji, $user_id, $tanggal_deadline];

    $stmtInsertLoker = sqlsrv_prepare($conn, $sqlInsertLoker, $params);

    // Eksekusi query
    if ($stmtInsertLoker && sqlsrv_execute($stmtInsertLoker)) {
        echo json_encode(['status' => 'success', 'message' => 'Job posted successfully!']);
    } else {
        // Tangani error jika terjadi kegagalan
        $errors = sqlsrv_errors();
        $error_message = 'Unknown error occurred.';
        if ($errors) {
            foreach ($errors as $error) {
                $error_message = "SQLSTATE: " . $error['SQLSTATE'] . " - Error Code: " . $error['code'] . " - " . $error['message'];
                break; // Ambil error pertama
            }
        }
        echo json_encode(['status' => 'error', 'message' => $error_message]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
?>