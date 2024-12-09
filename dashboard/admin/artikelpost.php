<?php
session_start();
include '../../include/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Inisialisasi variabel pesan
$success = $error = null;

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $sub_judul = $_POST['sub_judul'] ?? '';
    $konten = $_POST['konten'] ?? '';
    $user_username = $_SESSION['username']; // Ambil username dari session admin
    $cover = null; // Default value jika tidak ada gambar

    // Validasi form
    if (!$judul || !$konten || !$user_username) {
        $error = "Semua kolom harus diisi.";
    } else {
        // Proses upload gambar jika ada
        if (!empty($_FILES['cover']['tmp_name'])) {
            $cover_tmp = $_FILES['cover']['tmp_name'];
            $cover_name = basename($_FILES['cover']['name']);
            $target_dir = "../../asset/upload/"; // Folder tujuan upload
            $target_file = $target_dir . $cover_name;

            // Cek apakah file berhasil diupload
            if (move_uploaded_file($cover_tmp, $target_file)) {
                $cover = '/asset/upload/' . $cover_name; // Simpan path untuk database
            } else {
                $error = "Terjadi kesalahan saat meng-upload gambar.";
            }
        }

        // Menyimpan artikel ke database jika tidak ada error
        if (!isset($error)) {
            // Buat ID 4 karakter
            $idArtikel = substr(uniqid(), -4);

            $sql = "INSERT INTO artikel (IdArtikel, judul, sub_judul, konten, cover, User_username) 
            VALUES (?, ?, ?, ?, ?, ?)";
            $params = array($idArtikel, $judul, $sub_judul, $konten, $cover, $user_username);
            $stmt = sqlsrv_prepare($conn, $sql, $params);

            if (!$stmt) {
                $error = "Kesalahan saat mempersiapkan query: " . print_r(sqlsrv_errors(), true);
            } elseif (sqlsrv_execute($stmt)) {
                $success = "Artikel berhasil diposting.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan artikel: " . print_r(sqlsrv_errors(), true);
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
    <script>
        // JavaScript untuk menampilkan pop-up jika artikel berhasil diposting
        function showPopup(message) {
            alert(message);
        }
    </script>
</head>

<body class="bg-gray-50">

    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Post Artikel Baru</h2>

        <!-- Pop-Up Success -->
        <?php if ($success): ?>
            <script>
                showPopup("<?php echo htmlspecialchars($success); ?>");
            </script>
        <?php endif; ?>

        <!-- Pesan Error -->
        <?php if ($error): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Form Post Artikel -->
        <form method="POST" action="artikelpost.php" enctype="multipart/form-data">
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