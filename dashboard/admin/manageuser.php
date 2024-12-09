<?php
session_start();
include '../../include/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Query untuk mendapatkan daftar pelamar (Role 2)
$sql_pelamar = "SELECT * FROM [user] WHERE Role_idRole = 2 AND username != 'jpc'";
$result_pelamar = sqlsrv_query($conn, $sql_pelamar);

// Query untuk mendapatkan daftar perusahaan (Role 3)
$sql_perusahaan = "SELECT * FROM [user] WHERE Role_idRole = 3 AND username != 'jpc'";
$result_perusahaan = sqlsrv_query($conn, $sql_perusahaan);

// Menangani penghapusan user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['type'])) {
    $id = $_POST['id'];
    $type = $_POST['type'];

    if ($type == 'pelamar' || $type == 'perusahaan') {
        // Mulai transaksi
        sqlsrv_begin_transaction($conn);

        try {
            // Menghapus data terkait
            if ($type == 'pelamar') {
                // Hapus data pelamar
                sqlsrv_query($conn, "DELETE FROM [studi] WHERE Profile_User_username = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [sertifikat] WHERE Profile_User_username = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [pengalaman] WHERE Profile_User_username = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [melamar] WHERE User_pelamar = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [pelamar] WHERE User_username = ?", array($id));
            } else if ($type == 'perusahaan') {
                // Hapus data perusahaan
                sqlsrv_query($conn, "DELETE FROM [artikel] WHERE User_username = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [loker] WHERE Username_perusahaan = ?", array($id));
                sqlsrv_query($conn, "DELETE FROM [perusahaan] WHERE User_username = ?", array($id));
            }

            // Hapus user
            sqlsrv_query($conn, "DELETE FROM [user] WHERE username = ?", array($id));

            // Commit transaksi
            sqlsrv_commit($conn);
            echo json_encode(['status' => 'success', 'message' => 'User berhasil dihapus!']);
        } catch (Exception $e) {
            // Rollback transaksi jika ada error
            sqlsrv_rollback($conn);
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus user!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tipe tidak valid']);
    }

    exit; // Menyelesaikan request setelah penghapusan
}

// Menyertakan header
include "./header.php";
?>

<!-- HTML Content -->
<br><br><br>
<div class="max-w-7xl mx-auto p-6">
    <!-- Daftar Pelamar -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pelamar</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = sqlsrv_fetch_array($result_pelamar, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="px-6 py-4">Pelamar</td>
                        <td class="px-6 py-4">
                            <button class="bg-red-500 text-white px-4 py-2 rounded delete-btn"
                                data-id="<?php echo $user['username']; ?>" data-type="pelamar">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Perusahaan -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Perusahaan</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Nama Perusahaan</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = sqlsrv_fetch_array($result_perusahaan, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                        <td class="px-6 py-4">Perusahaan</td>
                        <td class="px-6 py-4">
                            <button class="bg-red-500 text-white px-4 py-2 rounded delete-btn"
                                data-id="<?php echo $user['username']; ?>" data-type="perusahaan">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded shadow-lg">
        <h3 class="text-lg font-bold mb-4">Konfirmasi Penghapusan</h3>
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus user ini?</p>
        <div class="mt-4">
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Batal</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let userId = '';
        let userType = '';

        // Menampilkan modal
        $('.delete-btn').click(function () {
            userId = $(this).data('id');
            userType = $(this).data('type');
            $('#deleteMessage').text(`Apakah Anda yakin ingin menghapus ${userType} dengan username ${userId}?`);
            $('#deleteModal').removeClass('hidden');
        });

        // Menangani konfirmasi hapus
        $('#confirmDelete').click(function () {
            $.ajax({
                type: 'POST',
                url: '', // Menggunakan file yang sama
                data: { id: userId, type: userType },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.status == 'success') {
                        // Menghilangkan baris yang dihapus
                        $(`button[data-id="${userId}"]`).closest('tr').remove();
                        alert(res.message);
                    } else {
                        alert(res.message);
                    }
                    // Reset userId dan userType setelah penghapusan
                    userId = '';
                    userType = '';
                    $('#deleteModal').addClass('hidden');
                },
                error: function () {
                    alert('Terjadi kesalahan saat menghapus user.');
                    $('#deleteModal').addClass('hidden');
                }
            });
        });

        // Menutup modal
        $('#cancelDelete').click(function () {
            // Reset userId dan userType jika batal
            userId = '';
            userType = '';
            $('#deleteModal').addClass('hidden');
        });
    });
</script>