<?php
// Mulai session untuk memastikan akses yang benar
session_start();
include '../../include/koneksi.php';// Pastikan koneksi database sudah ada

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
include "../../include/header.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">


    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Daftar Pengguna -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pengguna</h2>
        <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Role</th>
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
</body>

</html>
<?php include '../../include/footer.php'; ?>