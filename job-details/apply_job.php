<?php
session_start();
include '../include/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "You must be logged in to apply for jobs.";
    header("Location: ./login.php");
    exit;
}

// Mengambil nilai dari session dan form
$user_id = $_SESSION['username']; // Gunakan session untuk user_id
$job_id = $_POST['job_id'] ?? null; // Mengambil job_id dari form

// Validasi input
if (!$job_id) {
    $_SESSION['error'] = "Invalid job data. Please select a valid job.";
    header("Location: ./browse_jobs.php");
    exit;
}

// Mulai transaksi untuk memastikan konsistensi
sqlsrv_begin_transaction($conn);

// Cek apakah user sudah melamar untuk pekerjaan ini
$sql_check = "SELECT COUNT(*) FROM melamar WHERE User_pelamar = ? AND Loker_idLoker = ?";
$stmt_check = sqlsrv_prepare($conn, $sql_check, array(&$user_id, &$job_id));

if (sqlsrv_execute($stmt_check)) {
    $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

    if ($row[0] > 0) {
        // Jika sudah melamar, beri pesan error dan redirect
        $_SESSION['error'] = "You have already applied for this job.";
        sqlsrv_rollback($conn); // Rollback transaksi jika ada kesalahan
        header("Location: ../browse-jobs");
        exit;
    }
} else {
    $_SESSION['error'] = "Error checking application status: " . print_r(sqlsrv_errors(), true);
    sqlsrv_rollback($conn); // Rollback transaksi jika gagal mengecek
    header("Location: ../browse-jobs");
    exit;
}

// Jika belum melamar, lanjutkan dengan insert lamaran
$sql = "INSERT INTO melamar (User_pelamar, Loker_idLoker, waktu, status_lamaran_id) VALUES (?, ?, GETDATE(), 1)";
$stmt = sqlsrv_prepare($conn, $sql, array(&$user_id, &$job_id));

if (sqlsrv_execute($stmt)) {
    // Commit transaksi jika berhasil
    sqlsrv_commit($conn);
    $_SESSION['success'] = "Your application has been submitted successfully.";
    echo "<script>
            window.onload = function() {
                document.getElementById('successModal').style.display = 'flex';
            }
          </script>";
} else {
    $_SESSION['error'] = "There was an error with your application: " . print_r(sqlsrv_errors(), true);
    sqlsrv_rollback($conn); // Rollback transaksi jika gagal
}

// Redirect kembali ke halaman Browse Jobs
header("Location: ../browse-jobs");
exit;
?>