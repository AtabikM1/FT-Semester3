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

// Panggil stored procedure untuk mengambil data perusahaan
$sql_perusahaan = "{CALL GetPerusahaanProfile(?)}";
$params = array($username);
$stmt_perusahaan = sqlsrv_prepare($conn, $sql_perusahaan, $params);

if ($stmt_perusahaan && sqlsrv_execute($stmt_perusahaan)) {
    $perusahaan = sqlsrv_fetch_array($stmt_perusahaan, SQLSRV_FETCH_ASSOC);
} else {
    die(print_r(sqlsrv_errors(), true)); // Debugging jika terjadi error
}
include "../../include/header.php";

// Tombol dinamis: jika data kosong, tampilkan "Lengkapi Profil"; jika terisi, "Edit Profil"
$isComplete = !empty($perusahaan['nama']) && !empty($perusahaan['alamat']) && !empty($perusahaan['email']);
$buttonText = $isComplete ? "Edit Profil" : "Lengkapi Profil";
$buttonLink = $isComplete ? "/profile/perusahaan/edit.php" : "/profile/perusahaan/complete-profile.php";
?>
<div class="min-h-screen bg-gray-50 py-12">
    <?php include '../../include/mountain-background.php' ?>
    <div class="max-w-5xl py-16 mx-auto">
        <div class="bg-white shadow rounded-lg p-6">
            <!-- Header Perusahaan -->
            <div class="flex items-center space-x-6">
                <img src="<?php echo !empty($perusahaan['foto']) ? htmlspecialchars($perusahaan['foto']) : '../asset/defaultpfp.jpg'; ?>"
                    alt="Logo Perusahaan" class="w-24 h-24 rounded-full object-cover border border-gray-200">
                <div class="flex-1">
                    <h1 class="text-2xl font-semibold">
                        <?php echo !empty($perusahaan['nama']) ? htmlspecialchars($perusahaan['nama']) : "Perusahaan"; ?>
                    </h1>
                    <p class="text-sm text-gray-600">Profil Perusahaan</p>
                </div>
                <a href="<?php echo $buttonLink; ?>"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600">
                    <?php echo $buttonText; ?>
                </a>
            </div>

            <!-- Informasi Perusahaan -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Email</h2>
                    <p class="text-gray-700">
                        <?php echo !empty($perusahaan['email']) ? htmlspecialchars($perusahaan['email']) : "Tidak tersedia"; ?>
                    </p>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Telepon</h2>
                    <p class="text-gray-700">
                        <?php echo !empty($perusahaan['telepon']) ? htmlspecialchars($perusahaan['telepon']) : "+62 123 456 789"; ?>
                    </p>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Alamat</h2>
                    <p class="text-gray-700">
                        <?php echo !empty($perusahaan['alamat']) ? htmlspecialchars($perusahaan['alamat']) : "Tidak tersedia"; ?>
                    </p>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Tanggal Berdiri</h2>
                    <p class="text-gray-700">
                        <?php echo isset($perusahaan['tanggal_berdiri']) && $perusahaan['tanggal_berdiri'] instanceof DateTime
                            ? $perusahaan['tanggal_berdiri']->format("F Y")
                            : "Tidak tersedia"; ?>
                    </p>
                </div>
            </div>

            <!-- Tentang Perusahaan -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800">Tentang Perusahaan</h2>
                <p class="mt-2 text-gray-700">
                    <?php echo !empty($perusahaan['deskripsi']) ? htmlspecialchars($perusahaan['deskripsi']) : "Belum ada deskripsi."; ?>
                </p>
            </div>

            <!-- Website -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-800">Website</h2>
                <a href="<?php echo !empty($perusahaan['website']) ? htmlspecialchars($perusahaan['website']) : '#'; ?>"
                    target="_blank" class="text-blue-500 hover:underline">
                    <?php echo !empty($perusahaan['website']) ? htmlspecialchars($perusahaan['website']) : "Belum tersedia"; ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php include '../../include/footer.php'; ?>