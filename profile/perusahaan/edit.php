<?php
session_start();
include '../../include/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Ambil data perusahaan berdasarkan User_username (dari session)
$username = $_SESSION['username']; // Menggunakan session untuk mendapatkan username
$sql_perusahaan = "SELECT * FROM perusahaan WHERE User_username = ?";
$stmt = sqlsrv_prepare($conn, $sql_perusahaan, array($username));
sqlsrv_execute($stmt);
$perusahaan = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);



// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $telepon = $_POST['telepon'];
    $tanggal_berdiri = $_POST['tanggal_berdiri'];

    // Update database
    $sql_update = "UPDATE perusahaan SET 
        nama = ?, 
        deskripsi = ?, 
        alamat = ?, 
        email = ?, 
        website = ?, 
        telepon = ?, 
        tanggal_berdiri = ? 
        WHERE User_username = ?";
    $params = array($nama, $deskripsi, $alamat, $email, $website, $telepon, $tanggal_berdiri, $idPerusahaan);
    $stmt_update = sqlsrv_prepare($conn, $sql_update, $params);

    if (sqlsrv_execute($stmt_update)) {
        header("Location: profile/perusahaan");
        exit;
    } else {
        echo "Gagal memperbarui data.";
    }
}

include "../../include/header.php";
?>

<div class="min-h-screen bg-gray-50 pt-20">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-2xl font-bold mb-6">Edit Profile Perusahaan</h1>
        <form method="POST" class="space-y-6 bg-white p-6 rounded-xl shadow-md">
            <div>
                <label for="nama" class="block text-sm font-medium">Nama Perusahaan</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($perusahaan['nama']); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400"
                    required>
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400"><?php echo htmlspecialchars($perusahaan['deskripsi']); ?></textarea>
            </div>
            <div>
                <label for="alamat" class="block text-sm font-medium">Alamat</label>
                <input type="text" id="alamat" name="alamat"
                    value="<?php echo htmlspecialchars($perusahaan['alamat']); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400"
                    required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" id="email" name="email"
                    value="<?php echo htmlspecialchars($perusahaan['email']); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400"
                    required>
            </div>
            <div>
                <label for="website" class="block text-sm font-medium">Website</label>
                <input type="url" id="website" name="website"
                    value="<?php echo htmlspecialchars($perusahaan['website']); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div>
                <label for="telepon" class="block text-sm font-medium">Telepon</label>
                <input type="text" id="telepon" name="telepon"
                    value="<?php echo htmlspecialchars($perusahaan['telepon']); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div>
                <label for="tanggal_berdiri" class="block text-sm font-medium">Tanggal Berdiri</label>
                <input type="date" id="tanggal_berdiri" name="tanggal_berdiri"
                    value="<?php echo htmlspecialchars($perusahaan['tanggal_berdiri'] ? $perusahaan['tanggal_berdiri']->format('Y-m-d') : ''); ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-amber-400 focus:border-amber-400">
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-amber-400 text-white rounded-lg hover:bg-amber-500 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php include "../../include/footer.php"; ?>