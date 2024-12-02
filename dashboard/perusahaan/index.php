<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database ada

// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

// Proses perubahan status lamaran
if (isset($_POST['action'], $_POST['pelamar_id'], $_POST['loker_id'])) {
    $status = ($_POST['action'] === 'approve') ? 2 : 3; // 2 untuk diterima, 3 untuk ditolak
    $pelamar_id = $_POST['pelamar_id'];
    $loker_id = $_POST['loker_id'];

    $sql_update_status = "
        UPDATE melamar 
        SET status_lamaran_id = ? 
        WHERE User_pelamar = ? AND Loker_idLoker = ?";
    $stmt_update_status = sqlsrv_prepare($conn, $sql_update_status, array($status, $pelamar_id, $loker_id));

    if (sqlsrv_execute($stmt_update_status)) {
        $_SESSION['status_message'] = 'Status lamaran berhasil diperbarui!';
    } else {
        $_SESSION['status_message'] = 'Terjadi kesalahan saat memperbarui status lamaran.';
    }

    header("Location: " . $_SERVER['PHP_SELF']);
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
        u.nama AS pelamar_nama, 
        l.judul AS judul_loker, 
        sl.status_code AS status_lamaran, 
        sl.description AS deskripsi_status, 
        m.Loker_idLoker, 
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
        l.Username_perusahaan = ?";
$stmt_pelamar = sqlsrv_prepare($conn, $sql_pelamar, array($_SESSION['username']));
sqlsrv_execute($stmt_pelamar);
// Query untuk menghitung jumlah pelamar per lowongan
$sql_loker_stats = "
    SELECT 
        l.judul AS judul_loker, 
        COUNT(m.User_pelamar) AS jumlah_pelamar
    FROM 
        melamar m
    INNER JOIN 
        loker l ON m.Loker_idLoker = l.idLoker
    WHERE 
        l.Username_perusahaan = ?
    GROUP BY 
        l.judul
    ORDER BY 
        jumlah_pelamar DESC";
$stmt_loker_stats = sqlsrv_prepare($conn, $sql_loker_stats, array($_SESSION['username']));
sqlsrv_execute($stmt_loker_stats);

// Statistik Aplikasi
$sql_stats = "
    SELECT 
        SUM(CASE WHEN status_lamaran_id = 1 THEN 1 ELSE 0 END) AS tertunda,
        SUM(CASE WHEN status_lamaran_id = 2 THEN 1 ELSE 0 END) AS diterima,
        SUM(CASE WHEN status_lamaran_id = 3 THEN 1 ELSE 0 END) AS ditolak,
        COUNT(*) AS total
    FROM melamar 
    WHERE Loker_idLoker IN (SELECT idLoker FROM loker WHERE Username_perusahaan = ?)";
$stmt_stats = sqlsrv_prepare($conn, $sql_stats, array($_SESSION['username']));
sqlsrv_execute($stmt_stats);
$stats = sqlsrv_fetch_array($stmt_stats, SQLSRV_FETCH_ASSOC);

include "../../include/header.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<br><br><br>
<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">
    <?php if (isset($_SESSION['status_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $_SESSION['status_message'];
            unset($_SESSION['status_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Ringkasan Statistik -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Ringkasan Statistik Aplikasi</h2>
        <div class="flex space-x-4">
            <div class="bg-green-100 p-4 rounded text-center w-1/3">
                <p class="font-semibold">Tertunda</p>
                <p class="text-2xl"><?php echo $stats['tertunda']; ?> aplikasi</p>
                <p class="text-sm">
                    <?php
                    echo ($stats['total'] > 0) ? round(($stats['tertunda'] / $stats['total']) * 100, 2) . '%' : '0%';
                    ?>
                </p>
            </div>
            <div class="bg-yellow-100 p-4 rounded text-center w-1/3">
                <p class="font-semibold">Diterima</p>
                <p class="text-2xl"><?php echo $stats['diterima']; ?> aplikasi</p>
                <p class="text-sm">
                    <?php
                    echo ($stats['total'] > 0) ? round(($stats['diterima'] / $stats['total']) * 100, 2) . '%' : '0%';
                    ?>
                </p>
            </div>
            <div class="bg-red-100 p-4 rounded text-center w-1/3">
                <p class="font-semibold">Ditolak</p>
                <p class="text-2xl"><?php echo $stats['ditolak']; ?> aplikasi</p>
                <p class="text-xs">
                    <?php
                    echo ($stats['total'] > 0) ? round(($stats['ditolak'] / $stats['total']) * 100, 2) . '%' : '0%';
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Chart - Status Aplikasi -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-700 mb-4">Lowongan Paling Banyak Diminati</h3>
        <canvas id="lokerChart" class="w-full h-48"></canvas>
    </div>

    <!-- Daftar Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Lokasi</th>

                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($stmt_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>

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
                        <td class="px-6 py-4"><?php echo htmlspecialchars($pelamar['deskripsi_status']); ?></td>
                        <td class="px-6 py-4">
                            <?php if ((int) $pelamar['status_lamaran'] === 1): ?>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="pelamar_id" value="<?php echo $pelamar['pelamar_username']; ?>">
                                    <input type="hidden" name="loker_id" value="<?php echo $pelamar['Loker_idLoker']; ?>">
                                    <button type="submit" name="action" value="approve"
                                        class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                                </form>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="pelamar_id" value="<?php echo $pelamar['pelamar_username']; ?>">
                                    <input type="hidden" name="loker_id" value="<?php echo $pelamar['Loker_idLoker']; ?>">
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

<script>
    // Ambil data lowongan dan jumlah pelamar
    var lokerLabels = [];
    var lokerData = [];
    <?php while ($loker_stats = sqlsrv_fetch_array($stmt_loker_stats, SQLSRV_FETCH_ASSOC)): ?>
        lokerLabels.push("<?php echo addslashes($loker_stats['judul_loker']); ?>");
        lokerData.push(<?php echo $loker_stats['jumlah_pelamar']; ?>);
    <?php endwhile; ?>

    // Grafik Bar - Lowongan Paling Banyak Diminati
    var ctx = document.getElementById('lokerChart').getContext('2d');
    var lokerChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: lokerLabels,
            datasets: [{
                label: 'Jumlah Pelamar',
                data: lokerData,
                backgroundColor: '#4CAF50',
                borderColor: '#388E3C',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>