<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Periksa sesi pengguna
if ($_SESSION['Role'] != 2) {
    header("Location: login.php");
    exit;
}



// Query untuk mengambil data profil pelamar
$sql_profil = "SELECT * FROM pelamar WHERE User_username = ?";
$stmt_profil = sqlsrv_prepare($conn, $sql_profil, array($_SESSION['username']));
sqlsrv_execute($stmt_profil);
$profil = sqlsrv_fetch_array($stmt_profil, SQLSRV_FETCH_ASSOC);
// Path default jika tidak ada foto
$defaultFotoPath = '../../asset/defaultpfp.jpg';
$foto = (!empty($profil['foto']) && file_exists('../../' . $profil['foto']))
    ? '../../' . htmlspecialchars($profil['foto'])
    : $defaultFotoPath;


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
$lowongan_list = [];
while ($row = sqlsrv_fetch_array($stmt_lowongan, SQLSRV_FETCH_ASSOC)) {
    $lowongan_list[] = $row;
}

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
$aplikasi_list = [];
while ($row = sqlsrv_fetch_array($stmt_aplikasi, SQLSRV_FETCH_ASSOC)) {
    $aplikasi_list[] = $row;
}

// Cek apakah profil lengkap
$isProfileComplete = !empty($profil['alamat']) && !empty($profil['foto']);
$profileButtonText = $isProfileComplete ? 'Edit Profil' : 'Lengkapi Profil';
$profileButtonLink = $isProfileComplete ? '../../profile/pelamar/edit-profile.php' : '../../profile/pelamar/edit-profile.php';

// Set nilai default untuk profil yang belum lengkap
$profileUsername = !empty($profil['User_username']) ? htmlspecialchars($profil['User_username']) : 'Pengguna';
$profileAlamat = !empty($profil['alamat']) ? htmlspecialchars($profil['alamat']) : 'Alamat belum diisi';


?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" href="../asset/logooo.png" type="image/png">
<?php include '../../include/header.php'; ?>

<!-- Main Dashboard -->
<div class="min-h-screen bg-slate-50 pt-24 relative">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="absolute -top-10 w-full">
        <path fill="#3b82f6" fill-opacity="0.1"
            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
    </svg>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Section -->
        <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200 my-16">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo $foto; ?>" alt="Profile Picture"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-24 h-24 mx-auto">


                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">
                            Selamat <?php echo (date('H') < 12) ? 'Pagi' : ((date('H') < 17) ? 'Siang' : 'Sore'); ?>,
                            <?php echo $profileUsername; ?>
                        </h2>
                        <p class="text-slate-600"><?php echo $profileAlamat; ?></p>
                    </div>
                </div>
                <!-- Button to Edit or Complete Profile -->
                <!-- <button>
                    <a href="<?php echo $profileButtonLink; ?>"
                        class="inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                        <?php echo $profileButtonText; ?>
                    </a>
                </button> -->

            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Pending Application</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['tertunda']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Accepted Application</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['diterima']; ?></h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all border border-slate-200">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Rejected Application</p>
                        <h3 class="text-2xl font-bold text-slate-800 mt-2"><?php echo $stats['ditolak']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application List -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800 mb-6">Latest Application</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Lowongan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php foreach ($aplikasi_list as $aplikasi): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-800">
                                    <?php echo htmlspecialchars($aplikasi['judul_lowongan']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        <?php
                                        switch ($aplikasi['status_lamaran']) {
                                            case 'Tertunda':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'Diterima':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'Ditolak':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                        }
                                        ?>">
                                        <?php echo htmlspecialchars($aplikasi['status_lamaran']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart configuration
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Tertunda', 'Diterima', 'Ditolak'],
            datasets: [{
                data: [
                    <?php echo $stats['tertunda']; ?>,
                    <?php echo $stats['diterima']; ?>,
                    <?php echo $stats['ditolak']; ?>
                ],
                backgroundColor: ['#FCD34D', '#34D399', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
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
</script>

<?php include '../../include/footer.php'; ?>