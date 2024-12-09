<?php
session_start();
include '../../include/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Periksa apakah pengguna sudah ada di tabel pelamar
$sql_check = "SELECT * FROM pelamar WHERE User_username = ?";
$stmt_check = sqlsrv_prepare($conn, $sql_check, array($username));

if (!sqlsrv_execute($stmt_check)) {
    die("Error: " . print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);

// Jika data belum ada, lanjutkan dengan insert data baru ke tabel pelamar
if (!$user) {
    // Perbarui data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form
        $alamat = $_POST['alamat'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $telepon = $_POST['telepon'] ?? '';
        $email = $_POST['email'] ?? '';

        // Proses foto jika ada
        $foto_path = '/asset/defaultpfp.jpg'; // Default foto jika tidak ada file upload

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Tentukan folder penyimpanan foto
            $upload_dir = '../../asset/'; // Sesuaikan dengan path yang benar
            $upload_file = $upload_dir . basename($_FILES['foto']['name']);

            // Cek apakah file adalah gambar
            $image_file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
            if (in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
                // Pindahkan file ke folder
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_file)) {
                    $foto_path = '/asset/' . basename($_FILES['foto']['name']); // Simpan path foto
                } else {
                    $_SESSION['error'] = "Terjadi kesalahan saat mengunggah foto.";
                    header("Location: profile.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = "Hanya gambar yang diperbolehkan (JPG, JPEG, PNG, GIF).";
                header("Location: profile.php");
                exit;
            }
        }

        // Menyimpan resume sebagai teks (bukan file)
        $resume_text = $_POST['resume'] ?? '';

        // Menggunakan waktu sekarang untuk tanggal daftar
        $tanggal_daftar = date('Y-m-d H:i:s');

        // Mengubah gender sesuai dengan database (L untuk Male, P untuk Female)
        if ($gender === 'Male') {
            $gender = 'L';
        } elseif ($gender === 'Female') {
            $gender = 'P';
        }

        // Menyusun query INSERT untuk menambahkan data pelamar baru
        $sql_insert = "INSERT INTO pelamar (User_username, foto, alamat, tanggal_lahir, gender, tanggal_daftar, telepon, email, bio, resume) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($username, $foto_path, $alamat, $tanggal_lahir, $gender, $tanggal_daftar, $telepon, $email, $bio, $resume_text);

        $stmt_insert = sqlsrv_prepare($conn, $sql_insert, $params);

        if (sqlsrv_execute($stmt_insert)) {
            $_SESSION['message'] = "Profil berhasil ditambahkan!";
            header("Location: profile.php"); // Redirect setelah berhasil
            exit;
        } else {
            $_SESSION['error'] = "Gagal menambahkan profil. Error: " . print_r(sqlsrv_errors(), true);
            header("Location: profile.php");
            exit;
        }
    }
} else {
    // Jika data sudah ada, beri pesan bahwa profil sudah terdaftar
    $_SESSION['error'] = "Profil sudah terdaftar.";
    header("Location: profile.php");
    exit;
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
                    <img src="<?php echo isset($user['foto']) ? 'data:image/jpeg;base64,' . base64_encode($user['foto']) : '/asset/defaultpfp.jpg'; ?>"
                        alt="Profile Picture"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-24 h-24 mx-auto mb-4">
                    <input type="file" name="foto" class="w-full text-sm text-gray-700 py-2 px-3 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">email</label>
                    <textarea name="email" id="email" rows="1"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($user['resume'] ?? ''); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="telepon" class="block text-sm font-medium text-gray-700">telepon</label>
                    <textarea name="telepon" id="telepon" rows="1"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($user['resume'] ?? ''); ?></textarea>
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
                        <option value="L" <?php echo (isset($user['gender']) && $user['gender'] === 'L') ? 'selected' : ''; ?>>Male
                        </option>
                        <option value="P" <?php echo (isset($user['gender']) && $user['gender'] === 'P') ? 'selected' : ''; ?>>Female
                        </option>
                    </select>
                </div>



                <div class="mb-4">
                    <label for="resume" class="block text-sm font-medium text-gray-700">Resume</label>
                    <textarea name="resume" id="resume" rows="4"
                        class="w-full mt-1 px-3 py-2 border rounded-md"><?php echo htmlspecialchars($user['resume'] ?? ''); ?></textarea>
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