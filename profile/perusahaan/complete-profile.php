<?php
session_start();
include '../../include/koneksi.php'; // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil username dari session
$username = $_SESSION['username'];

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $alamat = trim($_POST['alamat']);
    $email = trim($_POST['email']);
    $website = trim($_POST['website']);
    $telepon = trim($_POST['telepon']);
    $tanggal_berdiri = !empty($_POST['tanggal_berdiri']) ? $_POST['tanggal_berdiri'] : null;

    // Validasi input
    if (empty($nama) || empty($deskripsi) || empty($alamat) || empty($email)) {
        $error_message = "Harap isi semua kolom wajib.";
    } else {
        // Simpan data ke database
        $sql = "INSERT INTO dbo.perusahaan (nama, deskripsi, alamat, email, website, telepon, tanggal_berdiri, User_username, foto) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)";
        $params = array($nama, $deskripsi, $alamat, $email, $website, $telepon, $tanggal_berdiri, $username);

        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt) {
            header("Location: /profile/perusahaan/index.php");
            exit;
        } else {
            $error_message = "Gagal menyimpan data. " . print_r(sqlsrv_errors(), true);
        }
    }
}

include "../../include/header.php";
?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-8">
        <h1 class="text-2xl font-semibold mb-6">Complete Company Profile</h1>
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="nama" id="nama" required class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <!-- Telepon -->
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700">Telephone</label>
                    <input type="text" name="telepon" id="telepon" class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['telepon'] ?? ''); ?>">
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                    <input type="url" name="website" id="website" class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['website'] ?? ''); ?>">
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="alamat" id="alamat" required class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['alamat'] ?? ''); ?>">
                </div>

                <!-- Tanggal Berdiri -->
                <div>
                    <label for="tanggal_berdiri" class="block text-sm font-medium text-gray-700">Established Date</label>
                    <input type="date" name="tanggal_berdiri" id="tanggal_berdiri"
                        class="mt-1 p-2 w-full border rounded"
                        value="<?php echo htmlspecialchars($_POST['tanggal_berdiri'] ?? ''); ?>">
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="deskripsi" id="deskripsi" required class="mt-1 p-2 w-full border rounded"
                        rows="4"><?php echo htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../../include/footer.php'; ?>