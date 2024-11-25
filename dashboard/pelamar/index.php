<?php
session_start();
include '../../include/koneksi.php'; // Pastikan koneksi database sudah ada

if ($_SESSION['Role'] != 2) {
    header("Location: login.php");
    exit;
}

$sql_loker = "SELECT * FROM loker";
$result_loker = sqlsrv_query($conn, $sql_loker);
include "../../include/header.php";


// Query untuk mendapatkan data aplikasi pelamar jika ada
$sql_aplikasi = "SELECT loker.judul, aplikasi.status 
                 FROM aplikasi
                 JOIN loker ON aplikasi.id_loker = loker.idLoker
                 WHERE aplikasi.username_pelamar = ?";
$stmt_aplikasi = sqlsrv_prepare($conn, $sql_aplikasi, array($_SESSION['Role']));
sqlsrv_execute($stmt_aplikasi);
?>
<br><br>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Sertakan jQuery -->
<script>
    $(document).ready(function () {
        $(".apply-btn").on("click", function (event) {
            event.preventDefault(); // Cegah reload halaman

            var id_loker = $(this).data("id_loker");
            var username_pelamar = "<?php echo $_SESSION['username']; ?>"; // Ambil username pelamar

            $.ajax({
                url: "apply.php",
                method: "POST",
                data: {
                    id_loker: id_loker,
                    username_pelamar: username_pelamar
                },
                success: function (response) {
                    // Tampilkan pesan sukses atau status aplikasi
                    alert(response); // Tampilkan pesan yang dikirim oleh server
                    // Tambahkan baris aplikasi baru ke tabel tanpa reload
                    var newRow = `<tr class="border-b">
                    <td class="px-6 py-4">${response.lowongan}</td>
                    <td class="px-6 py-4">${response.status}</td>
                  </tr>`;
                    $("table tbody").append(newRow); // Menambahkan baris aplikasi baru ke tabel
                }
                error: function () {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            });
        });
    });
</script>

<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">

    <!-- Daftar Lowongan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Lowongan</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Perusahaan</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['Username_perusahaan']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4">
                            <button data-id_loker="<?php echo $loker['idLoker']; ?>"
                                class="apply-btn bg-blue-500 text-white px-4 py-2 rounded">
                                Apply
                            </button>
                        </td>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Aplikasi -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Aplikasi Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Lowongan</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($aplikasi = sqlsrv_fetch_array($stmt_aplikasi, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($aplikasi['id_loker']); ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php echo htmlspecialchars($aplikasi['status']); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../include/footer.php'; ?>