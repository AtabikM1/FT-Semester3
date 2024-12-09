<?php
include '../include/koneksi.php'; // Pastikan koneksi database ada

// Pastikan request adalah POST dan ada username yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username'])) {
        $username = $data['username'];

        // Query untuk mendapatkan data pelamar
        $sql_user = "EXEC GetUserProfile ?";
        $stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
        sqlsrv_execute($stmt_user);

        $user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);

        if ($user) {
            // Mengirimkan data sebagai JSON
            echo json_encode([
                'nama' => htmlspecialchars($user['nama'] ?? 'Pengguna Baru'),
                'foto' => $user['foto'] ? $user['foto'] : '../../asset/defaultpfp.jpg',
                'alamat' => htmlspecialchars($user['alamat'] ?? 'Alamat belum diisi'),
                'tanggal_lahir' => isset($user['tanggal_lahir']) ? $user['tanggal_lahir']->format('Y-m-d') : 'Tanggal lahir belum diisi',
                'gender' => $user['gender'] ? ($user['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') : 'Gender belum diisi',
                'telepon' => htmlspecialchars($user['telepon'] ?? 'Telepon belum diisi'),
                'email' => htmlspecialchars($user['email'] ?? 'Email belum diisi'),
                'bio' => htmlspecialchars($user['bio'] ?? 'Belum ada deskripsi.')
            ]);
        } else {
            echo json_encode(['error' => 'Data pelamar tidak ditemukan']);
        }
    } else {
        echo json_encode(['error' => 'Username tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'Request method tidak valid']);
}
?>