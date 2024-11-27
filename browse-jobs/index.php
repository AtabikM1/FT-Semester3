<?php
session_start();

// Cek apakah pengguna adalah pelamar
if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 2) {
    header("Location: login.php");
    exit;
}

// Sertakan file koneksi database
include '../include/koneksi.php';

// Default parameter pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10; // Jumlah lowongan per halaman
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk mendapatkan data lowongan dengan fitur pencarian
$sql_loker = "SELECT l.idLoker, l.judul, l.deskripsi, l.tipe_loker, l.lokasi, l.gaji, p.nama AS nama_perusahaan
              FROM loker l
              INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username
              WHERE l.judul LIKE ? OR l.lokasi LIKE ?
              ORDER BY l.judul ASC
              OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
$params = ["%$search%", "%$search%", $offset, $limit];
$stmt = sqlsrv_query($conn, $sql_loker, $params);

// Jika query gagal
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Hitung total lowongan untuk pagination
$sql_count = "SELECT COUNT(*) AS total FROM loker WHERE judul LIKE ? OR lokasi LIKE ?";
$count_params = ["%$search%", "%$search%"];
$count_stmt = sqlsrv_query($conn, $sql_count, $count_params);
if ($count_stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$total_row = sqlsrv_fetch_array($count_stmt, SQLSRV_FETCH_ASSOC)['total'];
$total_pages = ceil($total_row / $limit);
?>
<?php include '../include/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<br><br><br>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900">
    <div class="container mx-auto px-6 lg:px-20 py-10 min-h-screen flex flex-col justify-between">
        <h1 class="text-4xl font-bold mb-8 text-center">Browse Jobs</h1>

        <!-- Form Pencarian -->
        <form method="GET" class="mb-6">
            <div class="flex items-center gap-4">
                <input type="text" name="search" placeholder="Search job title or location"
                    value="<?= htmlspecialchars($search) ?>"
                    class="flex-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="submit"
                    class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-lg hover:bg-blue-600 transition">Search</button>
            </div>
        </form>

        <!-- Tabel Lowongan -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="table-auto w-full">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-6 py-3">Job Title</th>
                        <th class="px-6 py-3">Location</th>
                        <th class="px-6 py-3">Company</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Salary</th>
                        <th class="px-6 py-3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (sqlsrv_has_rows($stmt)): ?>
                        <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                            <tr class="border-b hover:bg-gray-100">
                                <td class="px-6 py-4"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['tipe_loker']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['gaji']) ?: 'Negotiable' ?></td>
                                <td class="px-6 py-4 text-center">
                                    <a href="./job-details/?id=<?= urlencode($row['idLoker']) ?>"
                                        class="text-blue-500 hover:underline">View</a>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-500">No jobs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"
                    class="px-4 py-2 mx-1 <?= $i === $page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' ?> rounded-lg">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>

<?php include '../include/footer.php'; ?>