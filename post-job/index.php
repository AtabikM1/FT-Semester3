<?php
session_start();
include '../include/koneksi.php';

// Memastikan user sudah login dan memiliki hak akses perusahaan
if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['username']; // Menggunakan session untuk user_id
} else {
    echo "User is not logged in.";
    exit;
}

// Memeriksa apakah user adalah perusahaan
$sqlPerusahaan = "SELECT idPerusahaan FROM perusahaan WHERE User_username = ?";
$stmt = sqlsrv_prepare($conn, $sqlPerusahaan, array(&$user_id));
sqlsrv_execute($stmt);
$perusahaan = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$perusahaan) {
    echo "User is not associated with a company.";
    exit;
}

// Menangani form post job
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangani input dari form
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $tipe_loker = $_POST['tipe_loker'];
    $lokasi = $_POST['lokasi'];
    $gaji = $_POST['gaji'];
    $tanggal_post = date('Y-m-d H:i:s');
    $tanggal_deadline = $_POST['tanggal_deadline'];

    // Insert query untuk menyimpan lowongan kerja
    $sqlInsertLoker = "INSERT INTO loker (idLoker, judul, deskripsi, tipe_loker, lokasi, gaji, Username_perusahaan, tanggal_post, tanggal_deadline)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Membuat idLoker baru (contoh bisa menggunakan UUID atau ID yang unik)
    $idLoker = uniqid();

    $params = array(
        &$idLoker,
        &$judul,
        &$deskripsi,
        &$tipe_loker,
        &$lokasi,
        &$gaji,
        &$user_id,  // User yang sedang login (perusahaan)
        &$tanggal_post,
        &$tanggal_deadline
    );

    $stmtInsertLoker = sqlsrv_prepare($conn, $sqlInsertLoker, $params);

    if (sqlsrv_execute($stmtInsertLoker)) {
        echo "Job posted successfully!";
    } else {
        echo "Error posting job.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Post a New Job</h1>
            <form action="post-job.php" method="POST" class="space-y-6">
                <!-- Job Title -->
                <div>
                    <label for="judul" class="block text-lg font-medium text-gray-700">Job Title</label>
                    <input type="text" name="judul" id="judul" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Job Description -->
                <div>
                    <label for="deskripsi" class="block text-lg font-medium text-gray-700">Job Description</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <!-- Job Type -->
                <div>
                    <label for="tipe_loker" class="block text-lg font-medium text-gray-700">Job Type</label>
                    <select name="tipe_loker" id="tipe_loker" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>

                <!-- Job Location -->
                <div>
                    <label for="lokasi" class="block text-lg font-medium text-gray-700">Location</label>
                    <input type="text" name="lokasi" id="lokasi" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Salary -->
                <div>
                    <label for="gaji" class="block text-lg font-medium text-gray-700">Salary</label>
                    <input type="text" name="gaji" id="gaji"
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Deadline -->
                <div>
                    <label for="tanggal_deadline" class="block text-lg font-medium text-gray-700">Deadline</label>
                    <input type="date" name="tanggal_deadline" id="tanggal_deadline" required
                        class="mt-2 p-3 w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full py-3 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">Post Job</button>
                </div>
            </form>
        </div>

        <!-- Company Information (optional) -->
        <div class="bg-white rounded-xl shadow-md p-8 mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">About Your Company</h2>
            <div class="space-y-4">
                <div>
                    <p class="font-medium text-gray-700">Company Name: <?= htmlspecialchars($perusahaan['nama']) ?></p>
                    <p class="text-gray-600"><?= nl2br(htmlspecialchars($perusahaan['deskripsi'])) ?></p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Email: <?= htmlspecialchars($perusahaan['email']) ?></p>
                    <p class="font-medium text-gray-700">Phone: <?= htmlspecialchars($perusahaan['telepon']) ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
