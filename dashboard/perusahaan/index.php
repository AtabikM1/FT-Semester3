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
$sql_profil = "SELECT * FROM perusahaan WHERE User_username = ?";
$stmt_profil = sqlsrv_prepare($conn, $sql_profil, array($_SESSION['username']));
sqlsrv_execute($stmt_profil);
$profil = sqlsrv_fetch_array($stmt_profil, SQLSRV_FETCH_ASSOC);

// Variabel untuk memeriksa apakah profil lengkap
$profil_incomplete = (!$profil || in_array(null, $profil, true));
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
<script src="https://cdn.tailwindcss.com"></script>
<!-- Main Dashboard -->
<div class="min-h-screen bg-slate-50 pt-24 relative">
    <?php if ($profil_incomplete): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg max-w-4xl mx-auto mb-8">
            <strong>Profil Belum Lengkap!</strong>
            <p>Silakan lengkapi profil perusahaan Anda terlebih dahulu untuk mengakses fitur ini.</p>
        </div>
    <?php else: ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10 w-full">
            <path fill="#3b82f6" fill-opacity="0.1"
                d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
            </path>
        </svg>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Company Dashboard</h1>
                <p class="mt-2 text-slate-600">Manage Your Jobs and Applicants</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Tertunda Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Pending Applications</p>
                            <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['tertunda']; ?></h3>
                            <p class="text-sm text-slate-500 mt-1">
                                <?php echo round(($stats['tertunda'] / $stats['total']) * 100, 1); ?>% of total
                            </p>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Diterima Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Accepted Applications</p>
                            <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['diterima']; ?></h3>
                            <p class="text-sm text-slate-500 mt-1">
                                <?php echo round(($stats['diterima'] / $stats['total']) * 100, 1); ?>% of Total
                            </p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Ditolak Card -->
                <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-slate-600">Rejected Applications</p>
                            <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['ditolak']; ?></h3>
                            <p class="text-sm text-slate-500 mt-1">
                                <?php echo round(($stats['ditolak'] / $stats['total']) * 100, 1); ?>% of Total
                            </p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Lowongan Chart -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Most Popular Job Listings</h2>
                    <canvas id="lokerChart" height="300"></canvas>
                </div>

                <!-- Status Applications Chart -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Application Status</h2>
                    <canvas id="lokerChart2" height="300"></canvas>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    // Dummy data untuk chart
    var lokerLabels = ['Frontend Developer', 'Backend Developer', 'UI/UX Designer', 'Project Manager'];
    var lokerData = [25, 18, 15, 12];

    // Chart configurations
    const lokerChart = new Chart(document.getElementById('lokerChart'), {
        type: 'bar',
        data: {
            labels: lokerLabels,
            datasets: [{
                label: 'Jumlah Pelamar',
                data: lokerData,
                backgroundColor: '#3B82F6',
                borderColor: '#2563EB',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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

    // Chart kedua dengan tipe berbeda
    const lokerChart2 = new Chart(document.getElementById('lokerChart2'), {
        type: 'line',
        data: {
            labels: lokerLabels,
            datasets: [{
                label: 'Jumlah Pelamar',
                data: lokerData,
                backgroundColor: '#2196F3',
                borderColor: '#1976D2',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
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

<?php include '../../include/footer.php'; ?>