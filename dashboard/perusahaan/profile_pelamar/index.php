<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $pelamar_id = $_GET['id'];

    // Query untuk mendapatkan detail pelamar
    $sql_pelamar_detail = "
        SELECT 
            p.User_username AS pelamar_id,
            u.nama AS pelamar_nama,
            p.email AS pelamar_email,
            p.no_telepon AS pelamar_telepon,
            p.alamat AS pelamar_alamat,
            l.judul AS judul_loker,
            m.status_lamaran
        FROM 
            pelamar p
        INNER JOIN 
            [user] u ON p.User_username = u.username
        INNER JOIN 
            melamar m ON p.User_username = m.User_pelamar
        INNER JOIN 
            loker l ON m.Loker_idLoker = l.idLoker
        WHERE 
            p.User_username = ?
    ";
    $stmt_pelamar_detail = sqlsrv_prepare($conn, $sql_pelamar_detail, array($pelamar_id));
    sqlsrv_execute($stmt_pelamar_detail);
    $pelamar = sqlsrv_fetch_array($stmt_pelamar_detail, SQLSRV_FETCH_ASSOC);
}

// Proses perubahan status lamaran
if (isset($_POST['action']) && isset($_POST['idMelamar'])) {
    $idMelamar = $_POST['idMelamar'];
    $status = $_POST['action'] === 'approve' ? 2 : 3; // 2 untuk diterima, 3 untuk ditolak

    $sql_update_status = "UPDATE melamar SET status_lamaran = ? WHERE idMelamar = ?";
    $stmt_update_status = sqlsrv_prepare($conn, $sql_update_status, array($status, $idMelamar));

    if (sqlsrv_execute($stmt_update_status)) {
        echo "<script>alert('Status lamaran berhasil diperbarui!'); window.location.href='profile_pelamar.php?id=" . $pelamar_id . "';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui status lamaran.');</script>";
    }
}

include "../../include/header.php";
?>

<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Profil Pelamar</h2>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-2">Informasi Pelamar</h3>
        <p><strong>Nama:</strong> <?php echo htmlspecialchars($pelamar['pelamar_nama']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($pelamar['pelamar_email']); ?></p>
        <p><strong>Telepon:</strong> <?php echo htmlspecialchars($pelamar['pelamar_telepon']); ?></p>
        <p><strong>Alamat:</strong> <?php echo htmlspecialchars($pelamar['pelamar_alamat']); ?></p>
        <p><strong>Lowongan:</strong> <?php echo htmlspecialchars($pelamar['judul_loker']); ?></p>

        <h3 class="text-xl font-semibold mb-2 mt-4">Status Lamaran</h3>
        <p>
            <?php
            if ($pelamar['status_lamaran'] == 2) {
                echo "Diterima";
            } elseif ($pelamar['status_lamaran'] == 3) {
                echo "Ditolak";
            } else {
                echo "Menunggu";
            }
            ?>
        </p>

        <!-- Tombol untuk approve atau reject -->
        <?php if ($pelamar['status_lamaran'] == 1): ?>
            <form action="" method="POST" class="mt-4">
                <input type="hidden" name="idMelamar" value="<?php echo $pelamar['pelamar_id']; ?>">
                <button type="submit" name="action" value="approve"
                    class="bg-green-500 text-white px-4 py-2 rounded mr-2">Approve</button>
                <button type="submit" name="action" value="reject"
                    class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
            </form>
        <?php elseif ($pelamar['status_lamaran'] == 2): ?>
            <span class="text-green-500">Lamaran Diterima</span>
        <?php elseif ($pelamar['status_lamaran'] == 3): ?>
            <span class="text-red-500">Lamaran Ditolak</span>
        <?php endif; ?>
    </div>
</div>

<?php include '../../include/footer.php'; ?>