<?php
session_start();
include '../../include/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
include "../../include/header.php";

// Periksa apakah pengguna sudah ada di tabel perusahaan
$sql_check = "SELECT * FROM perusahaan WHERE User_username = ?";
$stmt_check = sqlsrv_prepare($conn, $sql_check, array($username));

if (!sqlsrv_execute($stmt_check)) {
    die("Error: " . print_r(sqlsrv_errors(), true));
}

$perusahaan = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [];
    $sql_update = "UPDATE perusahaan SET ";
    $isFirst = true;

    // Periksa dan update kolom yang diubah
    if (!empty($_POST['alamat'])) {
        $sql_update .= $isFirst ? "alamat = ?" : ", alamat = ?";
        $params[] = $_POST['alamat'];
        $isFirst = false;
    }

    if (!empty($_POST['deskripsi'])) {
        $sql_update .= $isFirst ? "deskripsi = ?" : ", deskripsi = ?";
        $params[] = $_POST['deskripsi'];
        $isFirst = false;
    }

    if (!empty($_POST['email'])) {
        $sql_update .= $isFirst ? "email = ?" : ", email = ?";
        $params[] = $_POST['email'];
        $isFirst = false;
    }

    if (!empty($_FILES['foto']['tmp_name'])) {
        // Tentukan nama file foto yang baru
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_name = basename($_FILES['foto']['name']);
        $target_dir = "../../asset/upload/"; // Folder tujuan upload
        $target_file = $target_dir . $foto_name;

        // Validasi file gambar
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        if (in_array($file_extension, $valid_extensions)) {
            // Cek apakah file valid dan tidak ada error
            if (move_uploaded_file($foto_tmp, $target_file)) {
                // Jika foto berhasil diupload, simpan path ke database
                $sql_update .= $isFirst ? "foto = ?" : ", foto = ?";
                $params[] = '/asset/upload/' . $foto_name; // Simpan path foto
            } else {
                $_SESSION['error'] = "Failed to upload photo";
            }
        } else {
            $_SESSION['error'] = "Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }

    // Jika ada perubahan data, lanjutkan dengan update
    if (count($params) > 0) {
        $sql_update .= " WHERE User_username = ?";
        $params[] = $username;

        $stmt_update = sqlsrv_prepare($conn, $sql_update, $params);

        if (sqlsrv_execute($stmt_update)) {
            $_SESSION['message'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile. Error: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        $_SESSION['error'] = "No changes were saved.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Perusahaan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.1.0/dist/notiflix-3.1.0.min.css" />
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <h1 class="text-2xl font-semibold mb-6 text-center">Edit Profil Perusahaan</h1>

            <!-- Menampilkan Pesan Status -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="mb-4 text-green-600"><?php echo $_SESSION['message']; ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="mb-4 text-red-600"><?php echo $_SESSION['error']; ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Form Edit Profil -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-6 text-center">
                    <img src="<?php echo isset($perusahaan['foto']) ? $perusahaan['foto'] : '/asset/upload/default.png'; ?>"
                        alt="Logo Perusahaan"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-24 h-24 mx-auto mb-4">
                    <input type="file" name="foto" class="w-full text-sm text-gray-700 py-2 px-3 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
                    <textarea name="alamat" id="alamat" rows="4"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($perusahaan['alamat'] ?? ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Perusahaan</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($perusahaan['deskripsi'] ?? ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Perusahaan</label>
                    <input type="email" name="email" id="email"
                        value="<?php echo htmlspecialchars($perusahaan['email'] ?? ''); ?>"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notiflix Script -->
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.1.0/dist/notiflix-3.1.0.min.js"></script>

    <script>
        // Menampilkan notifikasi jika berhasil atau gagal dengan efek yang lebih menarik
        <?php if (isset($_SESSION['message'])): ?>
            Notiflix.Notify.success('<?php echo $_SESSION['message']; ?>', {
                position: 'center-top',
                backgroundColor: '#4BB543',
                textColor: '#fff',
                fontSize: '16px',
                timeout: 3000,
                cssAnimation: true, // Mengaktifkan animasi
                cssAnimationDuration: 800,
                borderRadius: '10px',
            });
            <?php unset($_SESSION['message']); endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Notiflix.Notify.failure('<?php echo $_SESSION['error']; ?>', {
                position: 'center-top',
                backgroundColor: '#FF0000',
                textColor: '#fff',
                fontSize: '16px',
                timeout: 3000,
                cssAnimation: true, // Mengaktifkan animasi
                cssAnimationDuration: 800,
                borderRadius: '10px',
            });
            <?php unset($_SESSION['error']); endif; ?>
    </script>
</body>

</html>