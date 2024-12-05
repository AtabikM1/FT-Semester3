<?php
session_start();
include '../include/koneksi.php'; // Pastikan koneksi database ada

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

// Pastikan ada ID lowongan yang akan dihapus
if (isset($_GET['id'])) {
    $idLoker = $_GET['id'];

    // Periksa apakah lowongan tersebut milik perusahaan yang sedang login
    $sql_check_loker = "SELECT * FROM loker WHERE idLoker = ? AND Username_perusahaan = ?";
    $stmt_check_loker = sqlsrv_prepare($conn, $sql_check_loker, array($idLoker, $_SESSION['username']));
    sqlsrv_execute($stmt_check_loker);

    if (sqlsrv_fetch($stmt_check_loker)) {
        // Jika milik perusahaan yang login, hapus lowongan
        $sql_delete_loker = "DELETE FROM loker WHERE idLoker = ?";
        $stmt_delete_loker = sqlsrv_prepare($conn, $sql_delete_loker, array($idLoker));

        if (sqlsrv_execute($stmt_delete_loker)) {
            header("Location: index.php"); // Redirect ke halaman index setelah berhasil menghapus
            exit;
        } else {
            echo "Terjadi kesalahan saat menghapus lowongan.";
        }
    } else {
        echo "Lowongan ini tidak dapat dihapus.";
    }
} else {
    echo "ID lowongan tidak ditemukan.";
}
?>