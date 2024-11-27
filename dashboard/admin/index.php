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

<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">

    <!-- Chart Section -->
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
                        <td class="px-6 py-4"><a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Delete</a>
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
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['Username_perusahaan']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
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
                </tr>
            </thead>
            <tbody>
                <?php while ($artikel = sqlsrv_fetch_array($result_artikel, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($artikel['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($artikel['User_username']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<?php include '../../include/footer.php'; ?>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data yang didapat dari PHP
    const chartData = <?php echo json_encode($chartData); ?>;

    // Menyiapkan data untuk chart
    // Menyiapkan data untuk chart
    const labels = chartData.map(item => `Kelompok ID ${item.id_kelompok * 10} - ${item.id_kelompok * 10 + 9}`); // Label sumbu X dengan kelompok ID
    const data = chartData.map(item => item.jumlah_loker); // Data jumlah loker

    // Membuat chart menggunakan Chart.js
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', // Jenis chart (bar, line, pie, dll)
        data: {
            labels: labels, // Label sumbu X
            datasets: [{
                label: 'Jumlah Loker',
                data: data, // Data untuk chart
                backgroundColor: '#4e73df', // Warna untuk setiap bar
                borderColor: '#4e73df',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true, // Memastikan sumbu Y mulai dari 0
                    title: {
                        display: true,
                        text: 'Jumlah Loker' // Nama sumbu Y
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Kelompok ID Loker' // Nama sumbu X
                    }
                }
            }
        }
    });

</script>

</body>

</html>