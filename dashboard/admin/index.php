<?php
// Mulai session untuk memastikan akses yang benar
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Query untuk menghitung jumlah pelamar, perusahaan, loker, artikel, sertifikat, dan status lamaran
$sql_user = "SELECT COUNT(*) AS jumlah_pelamar FROM pelamar";
$result_user = sqlsrv_query($conn, $sql_user);
$jumlah_pelamar = sqlsrv_fetch_array($result_user, SQLSRV_FETCH_ASSOC)['jumlah_pelamar'];


$sql_perusahaan = "SELECT COUNT(*) AS jumlah_perusahaan FROM perusahaan";
$result_perusahaan = sqlsrv_query($conn, $sql_perusahaan);
$jumlah_perusahaan = sqlsrv_fetch_array($result_perusahaan, SQLSRV_FETCH_ASSOC)['jumlah_perusahaan'];

$sql_loker = "SELECT COUNT(*) AS jumlah_loker FROM loker";
$result_loker = sqlsrv_query($conn, $sql_loker);
$jumlah_loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)['jumlah_loker'];

$sql_artikel = "SELECT COUNT(*) AS jumlah_artikel FROM artikel";
$result_artikel = sqlsrv_query($conn, $sql_artikel);
$jumlah_artikel = sqlsrv_fetch_array($result_artikel, SQLSRV_FETCH_ASSOC)['jumlah_artikel'];

$sql_sertifikat = "SELECT COUNT(*) AS jumlah_sertifikat FROM sertifikat";
$result_sertifikat = sqlsrv_query($conn, $sql_sertifikat);
$jumlah_sertifikat = sqlsrv_fetch_array($result_sertifikat, SQLSRV_FETCH_ASSOC)['jumlah_sertifikat'];

// Query untuk menghitung jumlah lamaran berdasarkan status
$sql_lamaran = "SELECT status_lamaran_id, COUNT(*) AS jumlah FROM melamar GROUP BY status_lamaran_id";
$result_lamaran = sqlsrv_query($conn, $sql_lamaran);

$jumlah_lamaran = [];
while ($row = sqlsrv_fetch_array($result_lamaran, SQLSRV_FETCH_ASSOC)) {
    $jumlah_lamaran[$row['status_lamaran_id']] = $row['jumlah'];
}
$sql_traffic_loker = "
    SELECT 
        CONVERT(date, tanggal_post) AS tanggal, 
        COUNT(*) AS jumlah_postingan
    FROM loker 
    WHERE tanggal_post >= DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()), 0) 
    AND tanggal_post < DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) + 1, 0)
    GROUP BY CONVERT(date, tanggal_post)
    ORDER BY tanggal
";
$result_traffic_loker = sqlsrv_query($conn, $sql_traffic_loker);

// Siapkan data untuk grafik
$dates = [];
$postingan_counts = [];

while ($row = sqlsrv_fetch_array($result_traffic_loker, SQLSRV_FETCH_ASSOC)) {
    $dates[] = $row['tanggal'];
    $postingan_counts[] = $row['jumlah_postingan'];
}


// Menyertakan header
include "./header.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">

    <!-- Statistik Umum -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Umum</h2>
    <div class="mb-6 grid grid-cols-2 gap-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg">Jumlah Pelamar</h3>
            <p><?php echo $jumlah_pelamar; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg">Jumlah Perusahaan</h3>
            <p><?php echo $jumlah_perusahaan; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg">Jumlah Lowongan</h3>
            <p><?php echo $jumlah_loker; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-lg">Jumlah Artikel</h3>
            <p><?php echo $jumlah_artikel; ?></p>
        </div>
    </div>

    <!-- Grafik Statistik Lamaran -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Lamaran</h2>
    <div class="mb-6">
        <canvas id="lamaranChart" width="400" height="200"></canvas>
    </div>

    <!-- Daftar Pengguna -->


</div>

<script>
    // Data untuk grafik lamaran
    var lamaranData = {
        labels: ['Dalam Proses', 'Diterima', 'Ditolak'],
        datasets: [{
            label: 'Jumlah Lamaran',
            data: [
                <?php echo isset($jumlah_lamaran[1]) ? $jumlah_lamaran[1] : 0; ?>,
                <?php echo isset($jumlah_lamaran[2]) ? $jumlah_lamaran[2] : 0; ?>,
                <?php echo isset($jumlah_lamaran[3]) ? $jumlah_lamaran[3] : 0; ?>,
                0 // Tambahkan jumlah untuk status lainnya jika diperlukan
            ],
            backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 159, 64, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
            borderWidth: 1
        }]
    };

    // Inisialisasi grafik lamaran
    var ctx = document.getElementById('lamaranChart').getContext('2d');
    var lamaranChart = new Chart(ctx, {
        type: 'bar',
        data: lamaranData
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- HTML Content -->

<div class="max-w-7xl mx-auto p-6">



    <!-- Grafik Traffic Postingan Loker -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Traffic Postingan Loker (Bulan Ini)</h2>
    <div class="mb-6">
        <canvas id="trafficLokerChart" width="400" height="200"></canvas>
    </div>

</div>

<script>
    // Data untuk grafik lamaran
    var lamaranData = {
        labels: ['Dalam Proses', 'Diterima', 'Ditolak'],
        datasets: [{
            label: 'Jumlah Lamaran',
            data: [
                <?php echo isset($jumlah_lamaran[1]) ? $jumlah_lamaran[1] : 0; ?>,
                <?php echo isset($jumlah_lamaran[2]) ? $jumlah_lamaran[2] : 0; ?>,
                <?php echo isset($jumlah_lamaran[3]) ? $jumlah_lamaran[3] : 0; ?>,
                0 // Tambahkan jumlah untuk status lainnya jika diperlukan
            ],
            backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 159, 64, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 159, 64, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
            borderWidth: 1
        }]
    };

    // Inisialisasi grafik lamaran
    var ctx = document.getElementById('lamaranChart').getContext('2d');
    var lamaranChart = new Chart(ctx, {
        type: 'bar',
        data: lamaranData
    });

    // Data untuk grafik traffic postingan loker
    var trafficLokerData = {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Jumlah Postingan Loker',
            data: <?php echo json_encode($postingan_counts); ?>,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    };

    // Inisialisasi grafik traffic postingan loker
    var ctxTrafficLoker = document.getElementById('trafficLokerChart').getContext('2d');
    var trafficLokerChart = new Chart(ctxTrafficLoker, {
        type: 'line',
        data: trafficLokerData
    });
</script>

</body>

</html>


</body>

</html>