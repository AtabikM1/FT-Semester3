<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

if ($_SESSION['Role'] != 2) {
    header("Location: login.php");
    exit;
}

include "../../include/header.php";

// Query untuk mendapatkan jumlah aplikasi berdasarkan status
$sql_stats = "
    SELECT 
        COUNT(CASE WHEN status_lamaran_id = 1 THEN 1 END) AS tertunda,
        COUNT(CASE WHEN status_lamaran_id = 2 THEN 1 END) AS diterima,
        COUNT(CASE WHEN status_lamaran_id = 3 THEN 1 END) AS ditolak,
        COUNT(*) AS total
    FROM melamar 
    WHERE User_pelamar = ?
";

$stmt_stats = sqlsrv_prepare($conn, $sql_stats, array($_SESSION['username']));
sqlsrv_execute($stmt_stats);
$stats = sqlsrv_fetch_array($stmt_stats, SQLSRV_FETCH_ASSOC);

// Query untuk mendapatkan data aplikasi pelamar
$sql_aplikasi = "
 SELECT 
     loker.judul AS judul_lowongan,
     status_lamaran.description AS status_lamaran
 FROM melamar
 INNER JOIN loker ON melamar.Loker_idLoker = loker.idLoker
 INNER JOIN status_lamaran ON melamar.status_lamaran_id = status_lamaran.id
 WHERE melamar.User_pelamar = ?
 ";

$stmt_aplikasi = sqlsrv_prepare($conn, $sql_aplikasi, array($_SESSION['username']));
sqlsrv_execute($stmt_aplikasi);
?>

<br><br>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Sertakan Chart.js -->

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">

    <!-- Aplikasi Saya (Paling atas) -->
    <div class="bg-white shadow rounded-lg mb-6 p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Aplikasi Saya</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Lowongan</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($aplikasi = sqlsrv_fetch_array($stmt_aplikasi, SQLSRV_FETCH_ASSOC)): ?>
                        <tr class="border-b">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($aplikasi['judul_lowongan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($aplikasi['status_lamaran']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian Ringkasan Statistik dan Status Aplikasi (dalam dua kolom) -->
    <div class="flex space-x-4 mb-6">

        <!-- Ringkasan Statistik -->
        <div class="bg-white shadow rounded-lg p-6 w-1/2">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Ringkasan Statistik Aplikasi</h2>
            <div class="flex space-x-4">
                <div class="bg-green-100 p-4 rounded text-center">
                    <p class="font-semibold">Tertunda</p>
                    <p class="text-2xl"><?php echo $stats['tertunda']; ?> aplikasi</p>
                    <p class="text-sm"><?php echo round(($stats['tertunda'] / $stats['total']) * 100, 2); ?>%</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded text-center">
                    <p class="font-semibold">Diterima</p>
                    <p class="text-2xl"><?php echo $stats['diterima']; ?> aplikasi</p>
                    <p class="text-sm"><?php echo round(($stats['diterima'] / $stats['total']) * 100, 2); ?>%</p>
                </div>
                <div class="bg-red-100 p-4 rounded text-center">
                    <p class="font-semibold text-sm">Ditolak</p>
                    <p class="text-xl"><?php echo $stats['ditolak']; ?> aplikasi</p>
                    <p class="text-xs"><?php echo round(($stats['ditolak'] / $stats['total']) * 100, 2); ?>%</p>
                </div>
            </div>
        </div>

        <!-- Chart - Status Aplikasi -->
        <div class="bg-white shadow rounded-lg p-6 w-1/2">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Status Aplikasi Anda</h3>
            <canvas id="statusChart" class="w-48 h-48"></canvas> <!-- Menyesuaikan ukuran chart -->
        </div>
    </div>
</div>

<script>
    // Data chart status aplikasi
    var tertunda = <?php echo $stats['tertunda']; ?>;
    var diterima = <?php echo $stats['diterima']; ?>;
    var ditolak = <?php echo $stats['ditolak']; ?>;

    var ctx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctx, {
        type: 'pie', // Tipe chart: 'pie', 'bar', atau 'line'
        data: {
            labels: ['tertunda', 'diterima', 'Ditolak'],
            datasets: [{
                label: 'Status Aplikasi',
                data: [tertunda, diterima, ditolak],
                backgroundColor: ['#ff9800', '#4caf50', '#f44336'],

                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>

<?php include '../../include/footer.php'; ?>