<?php
session_start();
include '../../include/koneksi.php'; // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data user perusahaan berdasarkan session
$username = $_SESSION['username'];
$sql_perusahaan = "SELECT 
    perusahaan.nama,
    perusahaan.alamat,
    perusahaan.tanggal_berdiri,
    perusahaan.deskripsi,
    perusahaan.website,
    perusahaan.telepon,
    perusahaan.email,
    perusahaan.foto
FROM 
    perusahaan
WHERE 
    perusahaan.User_username = ?";
$stmt_perusahaan = sqlsrv_prepare($conn, $sql_perusahaan, array($username));
sqlsrv_execute($stmt_perusahaan);
$perusahaan = sqlsrv_fetch_array($stmt_perusahaan, SQLSRV_FETCH_ASSOC);

include "../../include/header.php";
?>
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="h-48 bg-gradient-to-r from-[#1C2056] to-[#2d317a]"></div>
            <div class="relative px-6 py-8">
                <div class="absolute -top-16">
                    <img src="<?php echo $perusahaan['foto'] ? 'data:image/jpeg;base64,' . base64_encode($perusahaan['foto']) : '/path/to/default-logo.jpeg'; ?>"
                        alt="Company Logo" class="rounded-full border-4 border-white shadow-lg object-cover w-32 h-32">
                </div>
                <div class="mt-16">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                <?php echo htmlspecialchars($perusahaan['nama']); ?>
                            </h1>
                            <p class="text-lg text-gray-600">Perusahaan</p>
                        </div>
                        <a href="/profile/perusahaan/edit.php<?php echo htmlspecialchars($perusahaan['username'] ?? ''); ?>"
                            class="inline-flex items-center px-4 py-2 bg-amber-400 text-gray-900 rounded-lg hover:bg-amber-500 transition">
                            Edit Profile
                        </a>

                    </div>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm8-4a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            <span><?php echo htmlspecialchars($perusahaan['email'] ?: 'Tidak tersedia'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M2.003 5.884L10 2l7.997 3.884v7.232L10 18l-7.997-4.884V5.884z" />
                            </svg>
                            <span><?php echo htmlspecialchars($perusahaan['telepon'] ?: '+62 123 456 789'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 3a3 3 0 00-3 3v8a3 3 0 003 3h10a3 3 0 003-3V6a3 3 0 00-3-3H5z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span><?php echo htmlspecialchars($perusahaan['alamat'] ?: 'Tidak tersedia'); ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10V4a8 8 0 014 0v6h2V4a8 8 0 014 0v6h2a8 8 0 01-8 8V4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Didirikan pada
                                <?php echo $perusahaan['tanggal_berdiri'] instanceof DateTime ? $perusahaan['tanggal_berdiri']->format("F Y") : date("F Y", strtotime($perusahaan['tanggal_berdiri'])); ?></span>
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
                    <h2 class="text-xl font-semibold mb-4">Tentang Perusahaan</h2>
                    <p class="text-gray-600">
                        <?php echo htmlspecialchars($perusahaan['deskripsi'] ?: 'Belum ada deskripsi.'); ?>
                    </p>
                </div>
            </div>
            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Website -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold">Website Perusahaan</h2>
                    <a href="<?php echo htmlspecialchars($perusahaan['website']); ?>" target="_blank"
                        class="text-blue-500 hover:underline">
                        <?php echo htmlspecialchars($perusahaan['website'] ?: 'Belum tersedia'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../include/footer.php'; ?>