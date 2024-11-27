<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include '../include/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'User is not logged in.']);
    exit;
}

// Mendapatkan data JSON dari request body (untuk POST request)
$input = json_decode(file_get_contents('php://input'), true);

// Cek jika parameter user_id dan job_id ada
if (isset($input['user_id']) && isset($input['job_id'])) {
    $user_id = $input['user_id'];
    $job_id = $input['job_id'];

    // Query untuk melamar pekerjaan
    $sql = "INSERT INTO melamar (Loker_idLoker, User_pelamar, waktu, status_lamaran_id) 
            VALUES (?, ?, SYSDATETIME(), 1);";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$job_id, &$user_id));

    if ($stmt && sqlsrv_execute($stmt)) {
        // Jika berhasil, kirimkan respons JSON
        echo json_encode(['success' => true, 'message' => 'Application successful']);
    } else {
        // Jika gagal, kirimkan error JSON
        echo json_encode(['success' => false, 'error' => 'Failed to apply for the job', 'sql_error' => sqlsrv_errors()]);
    }
} else {
    // Jika parameter tidak lengkap
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
?>