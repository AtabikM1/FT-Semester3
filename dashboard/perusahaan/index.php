<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan lowongan yang diposting oleh perusahaan ini
$sql_loker = "SELECT * FROM loker WHERE Username_perusahaan = ?";
$stmt_loker = sqlsrv_prepare($conn, $sql_loker, array($_SESSION['username']));
sqlsrv_execute($stmt_loker);

// Proses tambah lowongan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $tipe_loker = $_POST['tipe_loker'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $username_perusahaan = $_SESSION['username'];

    // Query untuk menambahkan lowongan baru
    $sql_tambah = "INSERT INTO loker (judul, tipe_loker, lokasi, deskripsi, Username_perusahaan) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt_tambah = sqlsrv_prepare($conn, $sql_tambah, array($judul, $tipe_loker, $lokasi, $deskripsi, $username_perusahaan));

    if (sqlsrv_execute($stmt_tambah)) {
        echo "<script>alert('Lowongan berhasil ditambahkan!'); window.location.href='../dashboard/perusahaan';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan lowongan.');</script>";
    }
}

include "../../include/header.php";
?>
<br><br>
<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">
    <!-- Daftar Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Lokasi</th>
                    <th class="px-6 py-3 text-left">Deskripsi</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($stmt_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['deskripsi']); ?></td>
                        <td class="px-6 py-4">
                            <a href="edit_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Form untuk Menambah Lowongan Baru -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah Lowongan Baru</h2>
    <form action="" method="POST" class="bg-white shadow rounded-lg p-6">
        <div class="mb-4">
            <label for="judul" class="block text-gray-700">Judul Lowongan</label>
            <input type="text" name="judul" id="judul" class="w-full p-3 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="tipe_loker" class="block text-gray-700">Tipe Lowongan</label>
            <select name="tipe_loker" id="tipe_loker" class="w-full p-3 border border-gray-300 rounded" required>
                <option value="Full-Time">Full-Time</option>
                <option value="Part-Time">Part-Time</option>
                <option value="Freelance">Freelance</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lokasi" class="block text-gray-700">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="w-full p-3 border border-gray-300 rounded" required>
        </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full p-3 border border-gray-300 rounded"
                required></textarea>
        </div>
        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Tambah Lowongan</button>
        </div>
    </form>
</div>

<?php include '../../include/footer.php'; ?>