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

// Panggil stored procedure untuk mendapatkan profil pengguna
$sql_user = "EXEC GetUserProfile ?";
$stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
sqlsrv_execute($stmt_user);

// Ambil hasil dari stored procedure
$user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC);

include "../../include/header.php";
?>

<!-- Animasi CSS -->
<style>
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade {
        animation: fadeIn 1s ease-out;
    }
</style>

<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate-fade">
            <div class="h-48 bg-gradient-to-r from-[#1C2056] to-[#2d317a]"></div>
            <div class="relative px-6 py-8" data-aos="zoom-in">
                <div class="absolute -top-16" data-aos="fade-down">
                    <img src="<?php echo $user['foto'] ? 'data:image/jpeg;base64,' . base64_encode($user['foto']) : '/asset/defaultpfp.jpg'; ?>"
                        alt="Profile Picture"
                        class="rounded-full border-4 border-white shadow-lg object-cover w-32 h-32">
                </div>
                <div class="mt-16">
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($user['nama']); ?></h1>
                    <p class="text-lg text-gray-600">
                        <?php echo $user['Role_idRole'] == 1 ? 'Admin' : ($user['Role_idRole'] == 2 ? 'Pelamar' : 'Perusahaan'); ?>
                    </p>
                    <a href="/profile/pelamar/edit-profile.php"
                        class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="space-y-8">
                <!-- About -->
                <div class="bg-white rounded-xl shadow-md p-6" data-aos="fade-right">
                    <h2 class="text-xl font-semibold mb-4">Tentang</h2>
                    <p class="text-gray-600"><?php echo htmlspecialchars($user['bio'] ?: 'Belum ada deskripsi.'); ?></p>
                </div>
            </div>
            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Resume -->
                <div class="bg-white rounded-xl shadow-md p-6" data-aos="fade-left">
                    <h2 class="text-xl font-semibold">Resume</h2>
                    <?php
                    if ($user['resume']) {
                        $formatted_resume = htmlspecialchars($user['resume']);
                        $formatted_resume = str_replace(
                            ["Professional Summary:", "Work Experience:", "- ", "Education:"],
                            ["<strong>Professional Summary:</strong>", "<strong>Work Experience:</strong>", "<li>", "<strong>Education:</strong>"],
                            $formatted_resume
                        );

                        // Bungkus Work Experience dengan list
                        $formatted_resume = preg_replace('/<strong>Work Experience:<\/strong>(.*?)<strong>/s', '<strong>Work Experience:</strong><ul>$1</ul><strong>', $formatted_resume);

                        echo nl2br($formatted_resume);
                    } else {
                        echo 'Unggah resume Anda di pengaturan profil.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../include/footer.php'; ?>