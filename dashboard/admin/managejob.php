<?php
// Mulai session untuk memastikan akses yang benar
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan semua loker
$sql_loker = "SELECT * FROM loker";
$result_loker = sqlsrv_query($conn, $sql_loker);

// Menyertakan header
include "./header.php";
?>

<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">

    <!-- Daftar Loker -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Kelola Loker</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul Loker</th>
                    <th class="px-6 py-3 text-left">Perusahaan</th>
                    <th class="px-6 py-3 text-left">Lokasi</th>
                    <th class="px-6 py-3 text-left">Gaji</th>
                    <th class="px-6 py-3 text-left">Tanggal Ditambahkan</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['Username_perusahaan']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['gaji']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tanggal_post']->format('Y-m-d H:i:s')); ?>
                        </td>

                        <td class="px-6 py-4">
                            <a href="edit_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Approve</a>
                            <a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus loker ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>

</html>