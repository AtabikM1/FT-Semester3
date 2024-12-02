<?php
session_start();
include '../../include/koneksi.php'; // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user dari database
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM pelamar WHERE User_username = ?";
$stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
sqlsrv_execute($stmt_user);
$user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);

// Variabel untuk menampilkan pesan
$success = null;
$message = "";

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $gender = $_POST['gender'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Handle file upload (foto dan resume)
    $foto = isset($_FILES['foto']['tmp_name']) && !empty($_FILES['foto']['tmp_name'])
        ? file_get_contents($_FILES['foto']['tmp_name'])
        : null;

    $resume = isset($_FILES['resume']['tmp_name']) && !empty($_FILES['resume']['tmp_name'])
        ? file_get_contents($_FILES['resume']['tmp_name'])
        : null;

    $sql_update = "UPDATE pelamar 
                   SET alamat = ?, tanggal_lahir = ?, gender = ?, telepon = ?, email = ?, bio = ?"
        . ($foto ? ", foto = ?" : "")
        . ($resume ? ", resume = ?" : "")
        . " WHERE User_username = ?";

    $params = [$alamat, $tanggal_lahir, $gender, $telepon, $email, $bio];
    if ($foto)
        $params[] = $foto;
    if ($resume)
        $params[] = $resume;
    $params[] = $username;

    $stmt_update = sqlsrv_prepare($conn, $sql_update, $params);
    if (sqlsrv_execute($stmt_update)) {
        $success = true;
        $message = "Profil berhasil diperbarui.";
    } else {
        $success = false;
        $message = "Gagal memperbarui profil. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path-to-tailwind.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Profile</title>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12">
        <h1 class="text-3xl font-bold text-center mb-8">Edit Profile</h1>

        <!-- Modal untuk pesan -->
        <?php if ($success !== null): ?>
            <div class="mb-6">
                <div
                    class="p-4 rounded-lg <?php echo $success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-8 shadow rounded-lg">
            <!-- Alamat -->
            <div>
                <label for="alamat" class="block font-medium">Alamat</label>
                <input type="text" id="alamat" name="alamat"
                    value="<?php echo htmlspecialchars($user['alamat'] ?? ''); ?>"
                    class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <label for="tanggal_lahir" class="block font-medium">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                    value="<?php echo htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>"
                    class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block font-medium">Gender</label>
                <select id="gender" name="gender" class="w-full px-4 py-2 border rounded-lg">
                    <option value="L" <?php echo isset($user['gender']) && $user['gender'] === 'L' ? 'selected' : ''; ?>>
                        Laki-Laki</option>
                    <option value="P" <?php echo isset($user['gender']) && $user['gender'] === 'P' ? 'selected' : ''; ?>>
                        Perempuan</option>
                </select>
            </div>

            <!-- Telepon -->
            <div>
                <label for="telepon" class="block font-medium">Telepon</label>
                <input type="text" id="telepon" name="telepon"
                    value="<?php echo htmlspecialchars($user['telepon'] ?? ''); ?>"
                    class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium">Email</label>
                <input type="email" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                    class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block font-medium">Bio</label>
                <textarea id="bio" name="bio" rows="4"
                    class="w-full px-4 py-2 border rounded-lg"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block font-medium">Foto</label>
                <input type="file" id="foto" name="foto" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Resume -->
            <div>
                <label for="resume" class="block font-medium">Resume (PDF)</label>
                <input type="file" id="resume" name="resume" accept=".pdf" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</body>

</html>