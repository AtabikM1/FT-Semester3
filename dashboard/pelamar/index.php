<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

if ($_SESSION['Role'] != 2) {
    header("Location: login.php");
    exit;
}

include "../../include/header.php";

// Query untuk mengambil data profil pelamar
$sql_profil = "SELECT * FROM pelamar WHERE User_username = ?";
$stmt_profil = sqlsrv_prepare($conn, $sql_profil, array($_SESSION['username']));
sqlsrv_execute($stmt_profil);
$profil = sqlsrv_fetch_array($stmt_profil, SQLSRV_FETCH_ASSOC);

// Query untuk mendapatkan jumlah aplikasi berdasarkan status
$sql_stats = "
    SELECT 
        COUNT(CASE WHEN m.status_lamaran_id = 1 THEN 1 END) AS tertunda,
        COUNT(CASE WHEN m.status_lamaran_id = 2 THEN 1 END) AS diterima,
        COUNT(CASE WHEN m.status_lamaran_id = 3 THEN 1 END) AS ditolak,
        COUNT(*) AS total
    FROM melamar m
    WHERE m.User_pelamar = ?
";
$stmt_stats = sqlsrv_prepare($conn, $sql_stats, array($_SESSION['username']));
sqlsrv_execute($stmt_stats);
$stats = sqlsrv_fetch_array($stmt_stats, SQLSRV_FETCH_ASSOC);

// Query untuk mendapatkan daftar lowongan terbaru
$sql_lowongan = "
    SELECT TOP 5 judul, perusahaan.nama AS perusahaan, tanggal_post
    FROM loker
    INNER JOIN perusahaan ON loker.Username_perusahaan = perusahaan.User_username
    ORDER BY tanggal_post DESC
";
$stmt_lowongan = sqlsrv_query($conn, $sql_lowongan);

// Query untuk mengambil data aplikasi pelamar
$sql_aplikasi = "
     SELECT l.judul AS judul_lowongan, sl.description AS status_lamaran
    FROM melamar m
    JOIN loker l ON m.Loker_idLoker = l.idLoker
    JOIN status_lamaran sl ON m.status_lamaran_id = sl.id
    WHERE m.User_pelamar = ?
";
$stmt_aplikasi = sqlsrv_prepare($conn, $sql_aplikasi, array($_SESSION['username']));
sqlsrv_execute($stmt_aplikasi);
?>

<br><br>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Sertakan Chart.js -->

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">

    <!-- Sapaan dan Profil -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            Selamat <?php echo (date('H') < 12) ? 'Pagi' : ((date('H') < 17) ? 'Siang' : 'Sore'); ?>,
            <?php echo htmlspecialchars($profil['User_username']); ?>
        </h2>
        <div class="flex items-center space-x-4">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($profil['foto']); ?>" alt="Foto Profil"
                class="w-16 h-16 rounded-full">
            <div>
                <p class="font-semibold"><?php echo htmlspecialchars($profil['alamat']); ?></p>
                <p class="text-sm text-gray-600">Pelamar</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik dan Status Aplikasi -->
    <div class="flex space-x-4 mb-6">
        <!-- Ringkasan Statistik -->
        <div class="bg-white shadow rounded-lg p-4 w-1/3">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Ringkasan Aplikasi</h2>
            <div class="flex space-x-2">
                <div class="bg-green-100 p-3 rounded text-center">
                    <p class="font-semibold text-sm">Tertunda</p>
                    <p class="text-lg"><?php echo $stats['tertunda']; ?> aplikasi</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded text-center">
                    <p class="font-semibold text-sm">Diterima</p>
                    <p class="text-lg"><?php echo $stats['diterima']; ?> aplikasi</p>
                </div>
                <div class="bg-red-100 p-3 rounded text-center">
                    <p class="font-semibold text-sm">Ditolak</p>
                    <p class="text-lg"><?php echo $stats['ditolak']; ?> aplikasi</p>
                </div>
            </div>
        </div>

        <!-- Chart - Status Aplikasi -->
        <div class="bg-white shadow rounded-lg p-4 w-1/3">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Status Aplikasi Anda</h3>
            <canvas id="statusChart" class="w-full h-32"></canvas>
        </div>
    </div>


    <!-- Daftar Lowongan Terbaru -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Terbaru</h2>
        <ul>
            <?php while ($lowongan = sqlsrv_fetch_array($stmt_lowongan, SQLSRV_FETCH_ASSOC)): ?>
                <li class="border-b py-2">
                    <a href="#" class="font-semibold text-gray-800"><?php echo htmlspecialchars($lowongan['judul']); ?></a>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($lowongan['perusahaan']); ?> -
                        <?php echo $lowongan['tanggal_post']->format('Y M d'); ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Aplikasi Saya -->
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
            labels: ['Tertunda', 'Diterima', 'Ditolak'],
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