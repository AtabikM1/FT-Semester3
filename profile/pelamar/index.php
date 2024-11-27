<?php
session_start();
include '../../include/koneksi.php'; // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user berdasarkan session
$username = $_SESSION['username'];
$sql_user = "SELECT 
    [user].username,
    [user].nama,
    [user].password,
    [user].Role_idRole,
    pelamar.foto,
    pelamar.alamat,
    pelamar.tanggal_lahir,
    pelamar.gender,
    pelamar.tanggal_daftar,
    pelamar.telepon,
    pelamar.email,
    pelamar.bio
FROM 
    [user]
LEFT JOIN 
    pelamar 
ON 
    [user].username = pelamar.User_username
WHERE 
    [user].username = 'atabikm';
";
$stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
sqlsrv_execute($stmt_user);
$user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);

// if (!$user) {
//     echo "<p class='text-center text-red-500 mt-10'>User not found.</p>";
//     exit;
// }

include "../../include/header.php";
?>
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-[#1C2056] to-[#2d317a]"></div>
            <div class="relative px-6 py-8">
                <div class="absolute -top-16">
                    <img src="<?php echo $user['foto'] ? 'data:image/jpeg;base64,' . base64_encode($user['foto']) : '/path/to/default-image.jpeg'; ?>"
                        alt="Profile Picture"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-32 h-32">
                </div>
                <div class="mt-16">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($user['nama']); ?>
                            </h1>
                            <p class="text-lg text-gray-600">
                                <?php echo $user['Role_idRole'] == 1 ? 'Admin' : ($user['Role_idRole'] == 2 ? 'Pelamar' : 'Perusahaan'); ?>
                            </p>
                        </div>
                        <a href="edit_profile.php"
                            class="flex items-center gap-2 px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12l-6 6m0-6l6-6" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm8-4a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            <span><?php echo htmlspecialchars($user['email'] ?: 'Tidak tersedia'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M2.003 5.884L10 2l7.997 3.884v7.232L10 18l-7.997-4.884V5.884z" />
                            </svg>
                            <span><?php echo htmlspecialchars($user['telepon'] ?: '+62 123 456 789'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 3a3 3 0 00-3 3v8a3 3 0 003 3h10a3 3 0 003-3V6a3 3 0 00-3-3H5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><?php echo htmlspecialchars($user['alamat'] ?: 'Tidak tersedia'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10V4a8 8 0 014 0v6h2V4a8 8 0 014 0v6h2a8 8 0 01-8 8V4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Bergabung sejak
                                <?php echo $user['tanggal_daftar'] instanceof DateTime ? $user['tanggal_daftar']->format("F Y") : date("F Y", strtotime($user['tanggal_daftar'])); ?></span>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="space-y-8">
                <!-- About -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Tentang</h2>
                    <p class="text-gray-600"><?php echo htmlspecialchars($user['bio'] ?: 'Belum ada deskripsi.'); ?></p>
                </div>
            </div>
            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Resume -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold">Resume</h2>
                    <p class="mt-2 text-gray-500">Unggah resume Anda di pengaturan profil.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../include/footer.php'; ?>