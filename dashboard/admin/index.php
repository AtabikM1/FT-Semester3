<?php
// Mulai session untuk memastikan akses yang benar
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan data pengguna, lowongan, dan artikel
$sql_user = "SELECT * FROM [user]";
$result_user = sqlsrv_query($conn, $sql_user);

$sql_loker = "SELECT * FROM loker";
$result_loker = sqlsrv_query($conn, $sql_loker);

$sql_artikel = "SELECT * FROM artikel";
$result_artikel = sqlsrv_query($conn, $sql_artikel);

// Query untuk menghitung jumlah lowongan berdasarkan tipe
$sql_chart = "SELECT 
    FLOOR(CAST(SUBSTRING(idLoker, 2, LEN(idLoker)) AS INT) / 10) AS id_kelompok, 
    COUNT(*) AS jumlah_loker
FROM loker
GROUP BY FLOOR(CAST(SUBSTRING(idLoker, 2, LEN(idLoker)) AS INT) / 10)
ORDER BY id_kelompok;
";
$stmt_chart = sqlsrv_prepare($conn, $sql_chart, array($_SESSION['username']));
sqlsrv_execute($stmt_chart);

// Menyiapkan data untuk chart
$chartData = [];
while ($row = sqlsrv_fetch_array($stmt_chart, SQLSRV_FETCH_ASSOC)) {
    $chartData[] = $row;
}

// Menyertakan header
include "./header.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">

    <!-- Statistik Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Statistik Lowongan</h2>
    <div class="mb-6">
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>

    <!-- Daftar Pengguna -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pengguna</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = sqlsrv_fetch_array($result_user, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="px-6 py-4">
                            <?php echo $user['Role_idRole'] == 1 ? 'Admin' : ($user['Role_idRole'] == 2 ? 'Pelamar' : 'Perusahaan'); ?>
                        </td>
                        <td class="px-6 py-4">
                            <a href="edit_user.php?id=<?php echo $user['username']; ?>"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['username']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Lowongan</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Perusahaan</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['Username_perusahaan']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Artikel -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Artikel</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Penulis</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($artikel = sqlsrv_fetch_array($result_artikel, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($artikel['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($artikel['User_username']); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit_artikel.php?id=<?php echo $artikel['IdArtikel']; ?>"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_artikel.php?id=<?php echo $artikel['IdArtikel']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Inisialisasi chart menggunakan data
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($chartData, 'id_kelompok')); ?>,
            datasets: [{
                label: 'Jumlah Lowongan',
                data: <?php echo json_encode(array_column($chartData, 'jumlah_loker')); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        }
    });
</script>

</body>

</html>