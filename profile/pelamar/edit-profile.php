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
// Periksa apakah pengguna sudah ada di tabel pelamar
$sql_check = "SELECT * FROM pelamar WHERE User_username = ?";
$stmt_check = sqlsrv_prepare($conn, $sql_check, array($username));

if (!sqlsrv_execute($stmt_check)) {
    die("Error: " . print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

// Perbarui data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [];
    $sql_update = "UPDATE pelamar SET ";
    $isFirst = true;

    // Periksa dan update kolom yang diubah
    if (!empty($_POST['alamat'])) {
        $sql_update .= $isFirst ? "alamat = ?" : ", alamat = ?";
        $params[] = $_POST['alamat'];
        $isFirst = false;
    }

    if (!empty($_POST['bio'])) {
        $sql_update .= $isFirst ? "bio = ?" : ", bio = ?";
        $params[] = $_POST['bio'];
        $isFirst = false;
    }

    if (!empty($_POST['tanggal_lahir'])) {
        $sql_update .= $isFirst ? "tanggal_lahir = ?" : ", tanggal_lahir = ?";
        $params[] = $_POST['tanggal_lahir'];
        $isFirst = false;
    }

    if (!empty($_POST['gender'])) {
        if ($_POST['gender'] === 'Male') {
            $_POST['gender'] = 'L';
        } elseif ($_POST['gender'] === 'Female') {
            $_POST['gender'] = 'P';
        }
        if (!$isFirst) {
            $sql_update .= ", ";
        }
        $sql_update .= "gender = ?";
        $params[] = $_POST['gender'];
        $isFirst = false;
    }

    if (!empty($_FILES['foto']['tmp_name'])) {
        // Tentukan nama file foto yang baru
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_name = basename($_FILES['foto']['name']);
        $target_dir = "../../asset/upload/"; // Folder tujuan upload
        $target_file = $target_dir . $foto_name;

        // Cek apakah file valid dan tidak ada error
        if (move_uploaded_file($foto_tmp, $target_file)) {
            // Jika foto berhasil diupload, simpan path ke database
            $sql_update .= $isFirst ? "foto = ?" : ", foto = ?";
            $params[] = '/asset/upload/' . $foto_name; // Simpan path foto
        } else {
            $_SESSION['error'] = "Foto gagal diunggah.";
        }
    }


    // Jika ada perubahan data, lanjutkan dengan update
    if (count($params) > 0) {
        $sql_update .= " WHERE User_username = ?";
        $params[] = $username;

        $stmt_update = sqlsrv_prepare($conn, $sql_update, $params);

        if (sqlsrv_execute($stmt_update)) {
            $_SESSION['message'] = "Profil berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui profil. Error: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        $_SESSION['error'] = "Tidak ada perubahan yang disimpan.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <!-- Notiflix CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.1.0/dist/notiflix-3.1.0.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <h1 class="text-2xl font-semibold mb-6 text-center">Edit Profil</h1>

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
                    <img src="<?php echo $_SESSION['userFoto']; ?>" alt="Profile Picture"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-24 h-24 mx-auto mb-4">
                    <input type="file" name="foto" class="w-full text-sm text-gray-700 py-2 px-3 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="4"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                        value="<?php echo isset($user['tanggal_lahir']) ? $user['tanggal_lahir']->format('Y-m-d') : ''; ?>"
                        class="w-full mt-1 px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="w-full mt-1 px-3 py-2 border rounded-md">
                        <option value="Male" <?php echo $user['gender'] === 'L' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $user['gender'] === 'P' ? 'selected' : ''; ?>>Female
                        </option>
                        <option value="Other" <?php echo $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notiflix Script -->
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.1.0/dist/notiflix-3.1.0.min.js"></script>
    <script>
        // Menampilkan notifikasi jika berhasil atau gagal
        <?php if (isset($_SESSION['message'])): ?>
            Notiflix.Notify.success('<?php echo $_SESSION['message']; ?>');
            <?php unset($_SESSION['message']); endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Notiflix.Notify.failure('<?php echo $_SESSION['error']; ?>');
            <?php unset($_SESSION['error']); endif; ?>
    </script>
</body>

</html>