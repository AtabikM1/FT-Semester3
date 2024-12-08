<?php
session_start();
include '../../include/koneksi.php'; // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include "../../include/header.php";
// Ambil data user berdasarkan session
$username = $_SESSION['username'];

// Panggil stored procedure untuk mendapatkan profil pengguna
$sql_user = "EXEC GetUserProfile ?";
$stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
sqlsrv_execute($stmt_user);

// Ambil hasil dari stored procedure
$user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC) ?: [];

// Default nilai untuk data user jika kosong
$nama = htmlspecialchars($user['nama'] ?? 'Pengguna Baru');
// Default nilai untuk foto profil
$foto = $user['foto'] ? '' . $user['foto'] : '../../asset/defaultpfp.jpg';
$alamat = htmlspecialchars($user['alamat'] ?? 'Alamat belum diisi');
$tanggal_lahir = isset($user['tanggal_lahir']) ? $user['tanggal_lahir']->format('Y-m-d') : 'Tanggal lahir belum diisi';
$gender = $user['gender'] ? ($user['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') : 'Gender belum diisi';
$telepon = htmlspecialchars($user['telepon'] ?? 'Telepon belum diisi');
$email = htmlspecialchars($user['email'] ?? 'Email belum diisi');
$bio = htmlspecialchars($user['bio'] ?? 'Belum ada deskripsi.');

$isProfileComplete = !empty($user['nama']) && !empty($user['alamat']) && !empty($user['bio']);

// Tombol yang akan ditampilkan
$buttonLabel = $isProfileComplete ? 'Edit Profil' : 'Lengkapi Profil';
$buttonLink = $isProfileComplete ? '/profile/pelamar/edit-profile.php' : '/profile/pelamar/complete-profile.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path-to-tailwind.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Profil Pelamar</title>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <!-- Gambar Profil -->
            <div class="text-center">
                <img src="<?php echo $foto; ?>" alt="Profile Picture"
                    class="rounded-full border-4 border-white shadow-lg object-cover w-24 h-24 mx-auto">
                <h1 class="text-2xl font-semibold mt-4"><?php echo $nama; ?></h1>
                <p class="text-gray-600"><?php echo $isProfileComplete ? 'Kandidat' : 'Profil Belum Lengkap'; ?></p>

                <a href="<?php echo $buttonLink; ?>"
                    class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    <?php echo $buttonLabel; ?>
                </a>
            </div>

        </div>

        <!-- Profile Content -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- About -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Tentang</h2>
                <p class="text-gray-600"><?php echo $bio; ?></p>
            </div>

            <!-- Profile Details -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Detail Profil</h2>
                    <div class="space-y-4">
                        <div>
                            <strong>Alamat:</strong>
                            <p class="text-gray-600"><?php echo $alamat; ?></p>
                        </div>
                        <div>
                            <strong>Tanggal Lahir:</strong>
                            <p class="text-gray-600"><?php echo $tanggal_lahir; ?></p>
                        </div>
                        <div>
                            <strong>Gender:</strong>
                            <p class="text-gray-600"><?php echo $gender; ?></p>
                        </div>
                        <div>
                            <strong>Telepon:</strong>
                            <p class="text-gray-600"><?php echo $telepon; ?></p>
                        </div>
                        <div>
                            <strong>Email:</strong>
                            <p class="text-gray-600"><?php echo $email; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Resume -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold">Resume</h2>
                    <?php
                    if ($user['resume']) {
                        $formatted_resume = htmlspecialchars($user['resume']);
                        $formatted_resume = str_replace(
                            ["Pendidikan:", "Pengalaman Kerja:", "- ", "Keahlian:"],
                            ["<strong>Pendidikan:</strong>", "<strong>Pengalaman Kerja:</strong>", "<li>", "<strong>Keahlian:</strong>"],
                            $formatted_resume
                        );

                        // Bungkus Work Experience dengan list
                        $formatted_resume = preg_replace('/<strong>Pengalaman Kerja:<\/strong>(.*?)<strong>/s', '<strong>Work Experience:</strong><ul>$1</ul><strong>', $formatted_resume);

                        echo nl2br($formatted_resume);
                    } else {
                        echo 'Unggah resume Anda di pengaturan profil.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>