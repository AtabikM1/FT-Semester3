<?php
// Mulai session untuk memastikan akses yang benar
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan daftar pelamar (Role 2)
$sql_pelamar = "SELECT * FROM [user] WHERE Role_idRole = 2 AND username != 'jpc'";
$result_pelamar = sqlsrv_query($conn, $sql_pelamar);

// Query untuk mendapatkan daftar perusahaan (Role 3)
$sql_perusahaan = "SELECT * FROM [user] WHERE Role_idRole = 3 AND username != 'jpc'";
$result_perusahaan = sqlsrv_query($conn, $sql_perusahaan);

// Menyertakan header
include "./header.php";
?>

<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">

    <!-- Daftar Pelamar -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pelamar</h2>
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
                <?php while ($user = sqlsrv_fetch_array($result_pelamar, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="px-6 py-4">Pelamar</td>
                        <td class="px-6 py-4">
                            <a href="delete_user.php?id=<?php echo $user['username']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pelamar ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Perusahaan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Perusahaan</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Nama Perusahaan</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = sqlsrv_fetch_array($result_perusahaan, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="px-6 py-4">Perusahaan</td>
                        <td class="px-6 py-4">
                            <a href="delete_user.php?id=<?php echo $user['username']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus perusahaan ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>

</html>