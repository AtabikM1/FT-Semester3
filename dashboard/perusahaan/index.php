<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan lowongan yang diposting oleh perusahaan ini
$sql_loker = "SELECT * FROM loker WHERE Username_perusahaan = ?";
$stmt_loker = sqlsrv_prepare($conn, $sql_loker, array($_SESSION['username']));
sqlsrv_execute($stmt_loker);

// Query untuk mendapatkan pelamar yang melamar ke lowongan perusahaan ini
$sql_pelamar = "
    SELECT 
    m.User_pelamar AS pelamar_username, 
    p.User_username AS pelamar_id, 
    u.nama AS pelamar_nama, 
    l.judul AS judul_loker, 
    sl.status_code AS status_lamaran, 
    sl.description AS deskripsi_status, 
    m.waktu
FROM 
    melamar m
INNER JOIN 
    pelamar p ON m.User_pelamar = p.User_username
INNER JOIN 
    [user] u ON p.User_username = u.username
INNER JOIN 
    loker l ON m.Loker_idLoker = l.idLoker
INNER JOIN 
    status_lamaran sl ON m.status_lamaran_id = sl.id
WHERE 
    l.Username_perusahaan = 'fwd';
";
$stmt_pelamar = sqlsrv_prepare($conn, $sql_pelamar, array($_SESSION['username']));
sqlsrv_execute($stmt_pelamar);

// Proses perubahan status lamaran
if (isset($_POST['action']) && isset($_POST['idMelamar'])) {
    $idMelamar = $_POST['idMelamar'];
    $status = $_POST['action'] === 'approve' ? 'ter' : 'tol'; // "ter" untuk diterima, "tol" untuk ditolak

    $sql_update_status = "UPDATE pelamar SET Status = ? WHERE idMelamar = ?";
    $stmt_update_status = sqlsrv_prepare($conn, $sql_update_status, array($status, $idMelamar));

    if (sqlsrv_execute($stmt_update_status)) {
        echo "<script>alert('Status lamaran berhasil diperbarui!'); window.location.reload();</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui status lamaran.');</script>";
    }
}

// Proses tambah lowongan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['action'])) {
    $judul = $_POST['judul'];
    $tipe_loker = $_POST['tipe_loker'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $username_perusahaan = $_SESSION['username'];

    // Query untuk menambahkan lowongan baru
    $sql_tambah = "INSERT INTO loker (judul, tipe_loker, lokasi, deskripsi, Username_perusahaan) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt_tambah = sqlsrv_prepare($conn, $sql_tambah, array($judul, $tipe_loker, $lokasi, $deskripsi, $username_perusahaan));

    if (sqlsrv_execute($stmt_tambah)) {
        echo "<script>alert('Lowongan berhasil ditambahkan!'); window.location.href='../dashboard/perusahaan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan lowongan.');</script>";
    }
}

include "../../include/header.php";
?>
<br><br>
<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">
    <!-- Daftar Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Lokasi</th>
                    <th class="px-6 py-3 text-left">Deskripsi</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($stmt_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['deskripsi']); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Pelamar -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pelamar</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Nama Pelamar</th>
                    <th class="px-6 py-3 text-left">Lowongan</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pelamar = sqlsrv_fetch_array($stmt_pelamar, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($pelamar['pelamar_nama']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($pelamar['judul_loker']); ?></td>
                        <td class="px-6 py-4">
                            <?php echo $pelamar['status_lamaran'] === '2' ? 'Diterima' : ($pelamar['status_lamaran'] === '3' ? 'Ditolak' : 'Menunggu'); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($pelamar['status_lamaran'] === null || $pelamar['status_lamaran'] === 1): ?>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="idMelamar" value="<?php echo $pelamar['status_lamaran']; ?>">
                                    <button type="submit" name="action" value="approve"
                                        class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                                </form>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="idMelamar" value="<?php echo $pelamar['status_lamaran']; ?>">
                                    <button type="submit" name="action" value="reject"
                                        class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-500">Aksi Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../include/footer.php'; ?>