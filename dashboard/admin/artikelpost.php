<?php
session_start();
include '../../include/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $sub_judul = $_POST['sub_judul'] ?? '';
    $konten = $_POST['konten'] ?? '';
    $cover = $_FILES['cover']['name'] ?? '';
    $user_username = $_SESSION['username']; // Ambil username dari session admin

    // Validasi form
    if (!$judul || !$konten || !$user_username) {
        $error = "Semua kolom harus diisi.";
    } else {
        // Proses upload gambar jika ada
        if ($cover) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($cover);
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $targetFile)) {
                // Gambar berhasil di-upload
            } else {
                $error = "Terjadi kesalahan saat meng-upload gambar.";
            }
        }

        // Menyimpan artikel ke database
        if (!isset($error)) {
            $sql = "INSERT INTO artikel (IdArtikel, judul, sub_judul, konten, cover, User_username) 
                    VALUES (NEWID(), ?, ?, ?, ?, ?)";
            $params = array($judul, $sub_judul, $konten, $cover, $user_username);
            $stmt = sqlsrv_prepare($conn, $sql, $params);

            if (sqlsrv_execute($stmt)) {
                $success = "Artikel berhasil diposting.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan artikel.";
            }
        }
    }
}

include './header.php'; // Menyertakan header aplikasi Anda
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Artikel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Post Artikel Baru</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif (isset($success)): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="post_artikel.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                <input type="text" id="judul" name="judul" class="mt-1 p-2 w-full border border-gray-300 rounded"
                    required>
            </div>

            <div class="mb-4">
                <label for="sub_judul" class="block text-sm font-medium text-gray-700">Sub Judul</label>
                <input type="text" id="sub_judul" name="sub_judul"
                    class="mt-1 p-2 w-full border border-gray-300 rounded">
            </div>

            <div class="mb-4">
                <label for="konten" class="block text-sm font-medium text-gray-700">Konten</label>
                <textarea id="konten" name="konten" rows="5" class="mt-1 p-2 w-full border border-gray-300 rounded"
                    required></textarea>
            </div>

            <div class="mb-4">
                <label for="cover" class="block text-sm font-medium text-gray-700">Cover (Optional)</label>
                <input type="file" id="cover" name="cover" class="mt-1 p-2 w-full border border-gray-300 rounded">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Post Artikel</button>
        </form>
    </div>

</body>

</html>