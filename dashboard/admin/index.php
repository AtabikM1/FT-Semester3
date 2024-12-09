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

include "./header.php";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Main Dashboard -->
<div class="min-h-screen bg-slate-50 pt-24 relative">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10 w-full">
        <path fill="#3b82f6" fill-opacity="0.1"
            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
        <path fill="#3b82f6" fill-opacity="0.07"
            d="M0,192L48,181.3C96,171,192,149,288,154.7C384,160,480,192,576,197.3C672,203,768,181,864,165.3C960,149,1056,139,1152,144C1248,149,1344,171,1392,181.3L1440,192L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
    </svg>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Dashboard Overview</h1>
            <p class="mt-2 text-slate-600">Welcome back, Administrator</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pelamar Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Pelamar</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo number_format($jumlah_pelamar); ?>
                        </h3>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Perusahaan Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Perusahaan Terdaftar</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2">
                            <?php echo number_format($jumlah_perusahaan); ?>
                        </h3>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Loker Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Lowongan Aktif</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo number_format($jumlah_loker); ?>
                        </h3>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Artikel Card -->
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Artikel Karir</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo number_format($jumlah_artikel); ?>
                        </h3>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Application Status Chart -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Statistik Lamaran</h2>
                <canvas id="lamaranChart" height="300"></canvas>
            </div>

            <!-- Traffic Chart -->
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Traffic Postingan Loker</h2>
                <canvas id="trafficLokerChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart configurations
    const lamaranChart = new Chart(document.getElementById('lamaranChart'), {
        type: 'doughnut',
        data: {
            labels: ['Dalam Proses', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    <?php echo isset($jumlah_lamaran[1]) ? $jumlah_lamaran[1] : 0; ?>,
                    <?php echo isset($jumlah_lamaran[2]) ? $jumlah_lamaran[2] : 0; ?>,
                    <?php echo isset($jumlah_lamaran[3]) ? $jumlah_lamaran[3] : 0; ?>
                ],
                backgroundColor: ['#3B82F6', '#10B981', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            cutout: '70%'
        }
    });

    const trafficLokerChart = new Chart(document.getElementById('trafficLokerChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Jumlah Postingan',
                data: <?php echo json_encode($postingan_counts); ?>,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>