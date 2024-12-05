<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi ke database sudah benar

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Menangani permintaan AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    // Mengambil detail loker
    if ($action === 'getDetail') {
        $idLoker = $_POST['idLoker'] ?? null;
        if ($idLoker) {
            // Query untuk mengambil detail loker berdasarkan idLoker
            // Mengambil detail loker
            $sql = "SELECT l.idLoker, l.judul, l.deskripsi, l.lokasi, l.gaji, 
        CONVERT(varchar, l.tanggal_deadline, 23) AS tanggal_deadline, 
        p.nama AS nama_perusahaan
        FROM loker l
        INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username
        WHERE l.idLoker = ?";

            $params = array(&$idLoker);
            $stmt = sqlsrv_prepare($conn, $sql, $params);

            // Menjalankan query dan mengembalikan hasilnya sebagai JSON
            if (sqlsrv_execute($stmt)) {
                if ($detail = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo json_encode($detail);
                    exit;
                } else {
                    echo json_encode(['error' => 'Data tidak ditemukan.']);
                    exit;
                }
            } else {
                echo json_encode(['error' => 'Gagal menjalankan query: ' . print_r(sqlsrv_errors(), true)]);
                exit;
            }
        } else {
            echo json_encode(['error' => 'ID Loker tidak ditemukan.']);
            exit;
        }
    }
    if ($action === 'updateStatus') {
        $idLoker = $_POST['idLoker'] ?? null;
        $status = $_POST['status'] ?? null;
        if ($idLoker && in_array($status, ['approve', 'reject'])) {
            // Cek status sebelumnya
            $sql_check_status = "SELECT status_approval FROM loker WHERE idLoker = ?";
            $params_check = array(&$idLoker);
            $stmt_check = sqlsrv_prepare($conn, $sql_check_status, $params_check);

            if (sqlsrv_execute($stmt_check)) {
                $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
                $current_status = $row['status_approval'];

                // Cek jika status sudah 'ter' (2) atau 'tol' (3)
                if ($current_status === 2 || $current_status === 3) {
                    echo json_encode(['message' => 'Aksi sudah dilakukan.']);
                    exit;
                }

                // Tentukan status baru
                $statusApproval = ($status == 'approve') ? 2 : 3;

                // Update status loker
                $sql = "UPDATE loker SET status_approval = ? WHERE idLoker = ?";
                $params = array($statusApproval, $idLoker);
                $stmt = sqlsrv_prepare($conn, $sql, $params);

                if (sqlsrv_execute($stmt)) {
                    echo json_encode(['message' => 'Status berhasil diperbarui.']);
                    exit;
                } else {
                    // Menampilkan error jika query gagal
                    die(print_r(sqlsrv_errors(), true));
                }
            } else {
                // Menampilkan error jika query pertama gagal
                die(print_r(sqlsrv_errors(), true));
            }
        } else {
            echo json_encode(['message' => 'Gagal memperbarui status.']);
        }
        exit;
    }

    // Jika action tidak dikenali
    echo json_encode(['error' => 'Aksi tidak dikenali.']);
    exit;
}
?>