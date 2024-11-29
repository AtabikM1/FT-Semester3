<?php
session_start();
include '../include/koneksi.php';

// Memastikan user sudah login
if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['username']; // Menggunakan session untuk user_id
} else {
    echo "User is not logged in.";
    exit;
}

// Mendapatkan ID Loker
$idLoker = $_GET['id'] ?? null;
if ($idLoker) {
    // Query untuk mengambil detail loker
    $sql = "SELECT l.idLoker, l.judul, l.deskripsi, l.tipe_loker, l.lokasi, l.gaji, l.tanggal_post, l.tanggal_deadline, p.nama AS nama_perusahaan, p.foto
    FROM loker l
    INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username
    WHERE l.idLoker = ?";

    $stmt = sqlsrv_prepare($conn, $sql, array(&$idLoker));
    sqlsrv_execute($stmt);
    $job = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (!$job) {
        echo "Job not found.";
        exit;
    }

    // Mengonversi gaji menjadi format yang benar
    $gaji = $job['gaji'];
    if (is_string($gaji)) {
        $gaji = str_replace('.', '', $gaji); // Menghapus titik
    }
    $gaji = (float) $gaji; // Mengonversi menjadi angka
    $formattedGaji = number_format($gaji, 0, ',', '.');

} else {
    echo "Job ID is missing.";
    exit;
}
// Debugging: Memeriksa hasil eksekusi query
if (!$stmt) {
    die(print_r(sqlsrv_errors(), true));  // Menampilkan error SQL
} elseif (!sqlsrv_execute($stmt)) {
    die(print_r(sqlsrv_errors(), true));  // Menampilkan error jika query gagal
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($job['judul']) ?> - Job Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="w-24 h-24 bg-gray-200 rounded-xl">
                    <!-- Menampilkan Foto Perusahaan -->
                    <?php if (!empty($job['foto'])): ?>
                        <img src="<?php echo $job['foto'] ? 'data:image/jpeg;base64,' . base64_encode($job['foto']) : '/path/to/default-logo.jpeg'; ?>"
                            alt="Logo Perusahaan" class="w-full h-full object-cover rounded-xl">
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center text-white font-semibold">No
                            Image</div>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($job['judul']) ?></h1>
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                            <?= htmlspecialchars($job['tipe_loker']) ?>
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0l4 4-4-4m0 8l-4-4 4 4m0 0l4-4-4 4">
                                </path>
                            </svg>
                            <span><?= htmlspecialchars($job['lokasi']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 0l4 4-4-4m0 8l-4-4 4 4m0 0l4-4-4 4">
                                </path>
                            </svg>
                            <span><?= $formattedGaji ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4">
                    <button id="applyBtnShow"
                        class="px-6 py-3 bg-yellow-400 text-gray-900 rounded-lg font-semibold hover:bg-yellow-500 transition">
                        Apply Now
                    </button>
                    <!-- <button
                        class="px-6 py-3 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition">
                        Save Job
                    </button> -->
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h2 class="text-2xl font-bold mb-6">Job Description</h2>
                    <p class="text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($job['deskripsi'])) ?></p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-md p-8">
                    <h2 class="text-xl font-bold mb-6">Job Overview</h2>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <p class="font-medium text-gray-900">Posted:
                                <?= date_format($job['tanggal_post'], 'd M Y') ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="font-medium text-gray-900">Deadline:
                                <?= date_format($job['tanggal_deadline'], 'd M Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="applyModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-xl shadow-lg w-1/3">
            <h3 class="text-lg font-semibold mb-4">Are you sure you want to apply for this job?</h3>
            <form action="apply_job.php" method="POST">
                <!-- Input hidden untuk mengirimkan data ke PHP -->
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['username']) ?>" />
                <input type="hidden" name="job_id" value="<?= htmlspecialchars($idLoker) ?>" />
                <div class="flex justify-between">
                    <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Apply</button>
                </div>
            </form>
        </div>
        <!-- Modal Success -->
        <div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-xl shadow-lg w-1/3">
                <h3 class="text-lg font-semibold mb-4">Application Submitted Successfully!</h3>
                <p>Your application has been successfully submitted. We will get back to you soon.</p>
                <div class="flex justify-center mt-4">
                    <button onclick="closeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Close</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Menampilkan modal ketika tombol apply diklik
        document.getElementById('applyBtnShow').addEventListener('click', function () {
            document.getElementById('applyModal').style.display = 'flex';
        });

        // Menutup modal ketika tombol cancel diklik
        document.getElementById('cancelBtn').addEventListener('click', function () {
            document.getElementById('applyModal').style.display = 'none';
        });
    </script>

    <script>
        // Menutup modal setelah tombol close ditekan
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
            window.location.href = '../browse-jobs'; // Redirect setelah menutup modal
        }

    </script>
</body>

</html>