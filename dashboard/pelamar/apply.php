<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan ada data yang dikirimkan melalui POST
if (isset($_POST['id_loker']) && isset($_POST['username_pelamar'])) {
    $id_loker = $_POST['id_loker'];
    $username_pelamar = $_POST['username_pelamar'];

    // Cek apakah pelamar sudah melamar lowongan ini
    $sql_check = "SELECT * FROM aplikasi WHERE id_loker = ? AND username_pelamar = ?";
    $stmt_check = sqlsrv_prepare($conn, $sql_check, array($id_loker, $username_pelamar));
    sqlsrv_execute($stmt_check);

    if (sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC)) {
        echo "Anda sudah melamar lowongan ini.";  // Pesan jika sudah melamar
    } else {
        // Menyimpan aplikasi baru
        $sql_insert = "INSERT INTO aplikasi (id_loker, username_pelamar, status) VALUES (?, ?, 'Pending')";
        $stmt_insert = sqlsrv_prepare($conn, $sql_insert, array($id_loker, $username_pelamar));

        if (sqlsrv_execute($stmt_insert)) {
            echo "Aplikasi berhasil dikirim.";  // Pesan jika aplikasi berhasil
        } else {
            echo "Terjadi kesalahan, aplikasi gagal dikirim.";  // Pesan error
        }
    }
}
?>