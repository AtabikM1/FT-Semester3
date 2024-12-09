<?php
session_start();
include '../include/koneksi.php'; // Pastikan koneksi database ada

// Aktifkan error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan pengguna adalah perusahaan
if ($_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}


// Proses perubahan status lamaran
// Proses perubahan status lamaran
if (isset($_POST['action'], $_POST['pelamar_id'], $_POST['loker_id'])) {
    $status = ($_POST['action'] === 'approve') ? 2 : 3; // 2 untuk diterima, 3 untuk ditolak
    $pelamar_id = $_POST['pelamar_id'];
    $loker_id = $_POST['loker_id'];

    $sql_update_status = "
        UPDATE melamar 
        SET status_lamaran_id = ? 
        WHERE User_pelamar = ? AND Loker_idLoker = ?";
    $stmt_update_status = sqlsrv_prepare($conn, $sql_update_status, array($status, $pelamar_id, $loker_id));

    if (sqlsrv_execute($stmt_update_status)) {
        // Redirect tanpa mengirimkan pesan ke client
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect ke halaman yang sama
        exit;
    } else {
        // Redirect dengan error
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=true");
        exit;
    }
}


if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $sql_user = "EXEC GetUserProfile ?";
    $stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
    sqlsrv_execute($stmt_user);

    $user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC) ?: [];

    if ($user) {
        // Menyusun data yang akan dikirim ke client
        echo json_encode([
            'nama' => htmlspecialchars($user['nama'] ?? 'Pengguna Baru'),
            'foto' => $user['foto'] ? $user['foto'] : '../../asset/defaultpfp.jpg',
            'alamat' => htmlspecialchars($user['alamat'] ?? 'Alamat belum diisi'),
            'tanggal_lahir' => isset($user['tanggal_lahir']) ? $user['tanggal_lahir']->format('Y-m-d') : 'Tanggal lahir belum diisi',
            'gender' => $user['gender'] ? ($user['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') : 'Gender belum diisi',
            'telepon' => htmlspecialchars($user['telepon'] ?? 'Telepon belum diisi'),
            'email' => htmlspecialchars($user['email'] ?? 'Email belum diisi'),
            'bio' => htmlspecialchars($user['bio'] ?? 'Belum ada deskripsi.')
        ]);
    } else {
        echo json_encode(['error' => 'Data pelamar tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'Username tidak ditemukan']);
}

// Query untuk mendapatkan lowongan yang diposting oleh perusahaan ini
$sql_loker = "SELECT * FROM loker WHERE Username_perusahaan = ?";
$stmt_loker = sqlsrv_prepare($conn, $sql_loker, array($_SESSION['username']));
sqlsrv_execute($stmt_loker);

// Query untuk mendapatkan pelamar yang melamar ke lowongan perusahaan ini
$sql_pelamar = "
    SELECT 
        m.User_pelamar AS pelamar_username, 
        u.nama AS pelamar_nama, 
        u.email AS pelamar_email,  -- pastikan kolom email ada di sini
        l.judul AS judul_loker, 
        sl.status_code AS status_lamaran, 
        sl.description AS deskripsi_status, 
        m.Loker_idLoker, 
        m.waktu
    FROM 
        melamar m
    INNER JOIN 
        pelamar p ON m.User_pelamar = p.User_username
    INNER JOIN 
        [user] u ON p.User_username = u.username
    INNER JOIN 
        loker l ON m.Loker_idLoker = l.idLoker
    INNER JOIN 
        status_lamaran sl ON m.status_lamaran_id = sl.id
    WHERE 
        l.Username_perusahaan = ?";

$stmt_pelamar = sqlsrv_prepare($conn, $sql_pelamar, array($_SESSION['username']));
sqlsrv_execute($stmt_pelamar);
// Query untuk menghitung jumlah pelamar per lowongan
$sql_loker_stats = "
    SELECT 
        l.judul AS judul_loker, 
        COUNT(m.User_pelamar) AS jumlah_pelamar
    FROM 
        melamar m
    INNER JOIN 
        loker l ON m.Loker_idLoker = l.idLoker
    WHERE 
        l.Username_perusahaan = ?
    GROUP BY 
        l.judul
    ORDER BY 
        jumlah_pelamar DESC";
$stmt_loker_stats = sqlsrv_prepare($conn, $sql_loker_stats, array($_SESSION['username']));
sqlsrv_execute($stmt_loker_stats);

// // Statistik Aplikasi
// $sql_stats = "
//     SELECT 
//         SUM(CASE WHEN status_lamaran_id = 1 THEN 1 ELSE 0 END) AS tertunda,
//         SUM(CASE WHEN status_lamaran_id = 2 THEN 1 ELSE 0 END) AS diterima,
//         SUM(CASE WHEN status_lamaran_id = 3 THEN 1 ELSE 0 END) AS ditolak,
//         COUNT(*) AS total
//     FROM melamar 
//     WHERE Loker_idLoker IN (SELECT idLoker FROM loker WHERE Username_perusahaan = ?)";
// $stmt_stats = sqlsrv_prepare($conn, $sql_stats, array($_SESSION['username']));
// sqlsrv_execute($stmt_stats);
// $stats = sqlsrv_fetch_array($stmt_stats, SQLSRV_FETCH_ASSOC);

include "../include/header.php";
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<br><br><br>
<!-- Dashboard Content -->
<div class="max-w-7xl mx-auto p-6 flex flex-col min-h-screen">

    <!-- Daftar Pelamar -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pelamar</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Nama Pelamar</th>
                    <th class="px-6 py-3 text-left">Lowongan</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pelamar = sqlsrv_fetch_array($stmt_pelamar, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4">
                            <a href="#" onclick="showPelamarDetail('<?php echo $pelamar['pelamar_username']; ?>')">
                                <?php echo htmlspecialchars($pelamar['pelamar_nama']); ?>
                            </a>
                        </td>

                        <td class="px-6 py-4"><?php echo htmlspecialchars($pelamar['judul_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($pelamar['deskripsi_status']); ?></td>
                        <td class="px-6 py-4">
                            <?php if ((int) $pelamar['status_lamaran'] === 1): ?>
                                <form id="approveForm" method="POST" style="display:inline;">
                                    <input type="hidden" name="pelamar_id" value="<?php echo $pelamar['pelamar_username']; ?>">
                                    <input type="hidden" name="loker_id" value="<?php echo $pelamar['Loker_idLoker']; ?>">
                                    <button type="button" class="bg-green-500 text-white px-4 py-2 rounded"
                                        onclick="openModal('approve', '<?php echo $pelamar['pelamar_username']; ?>', '<?php echo $pelamar['Loker_idLoker']; ?>')">Approve</button>
                                </form>
                                <form id="rejectForm" method="POST" style="display:inline;">
                                    <input type="hidden" name="pelamar_id" value="<?php echo $pelamar['pelamar_username']; ?>">
                                    <input type="hidden" name="loker_id" value="<?php echo $pelamar['Loker_idLoker']; ?>">
                                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded"
                                        onclick="openModal('reject', '<?php echo $pelamar['pelamar_username']; ?>', '<?php echo $pelamar['Loker_idLoker']; ?>')">Reject</button>
                                </form>
                            <?php else: ?>
                                <!-- Tambahkan link mailto untuk menghubungi email pelamar -->
                                <a href="mailto:<?php echo htmlspecialchars($pelamar['pelamar_email'] ?? ''); ?>?subject=Status Lamaran&body=Halo, %0A%0AKami ingin memberitahukan bahwa status lamaran Anda untuk posisi <?php echo htmlspecialchars($pelamar['judul_loker'] ?? ''); ?> adalah <?php echo htmlspecialchars($pelamar['deskripsi_status'] ?? ''); ?>.%0A%0ATerima kasih."
                                    class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Hubungi Email Pelamar
                                </a>


                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Daftar Lowongan -->
    <!-- <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Lokasi</th>

                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker = sqlsrv_fetch_array($stmt_loker, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tipe_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>

                        <td class="px-6 py-4">

                            <a href="delete_loker.php?id=<?php echo $loker['idLoker']; ?>"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Delete</a>

                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div> -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Lowongan Saya</h2>
    <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Lowongan</th>
                    <th class="px-6 py-3 text-left">Jumlah Pelamar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loker_stats = sqlsrv_fetch_array($stmt_loker_stats, SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="border-b">
                        <td class="px-6 py-4"><?php echo htmlspecialchars($loker_stats['judul_loker']); ?></td>
                        <td class="px-6 py-4"><?php echo $loker_stats['jumlah_pelamar']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
<!-- Modal Konfirmasi -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded shadow-lg max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Konfirmasi</h2>
        <p class="mb-4">Apakah Anda yakin ingin memproses status lamaran ini?</p>
        <form id="statusForm" method="POST">
            <input type="hidden" name="pelamar_id" id="pelamar_id">
            <input type="hidden" name="loker_id" id="loker_id">
            <button type="submit" name="action" value="approve"
                class="bg-green-500 text-white px-4 py-2 rounded mr-2">Approve</button>
            <button type="submit" name="action" value="reject"
                class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
        </form>
        <button id="closeModal" class="mt-4 bg-gray-300 px-4 py-2 rounded">Tutup</button>
    </div>
</div>
<!-- Modal Detail Pelamar -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded shadow-lg max-w-md w-full">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Detail Pelamar</h2>
        <div id="modalContent"></div>
        <button id="closeModal" class="mt-4 bg-gray-300 px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<script>
    document.getElementById('closeModal').onclick = function () {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>

<?php include '../include/footer.php'; ?>

<script>
    // Ambil data lowongan dan jumlah pelamar
    var lokerLabels = [];
    var lokerData = [];
    <?php while ($loker_stats = sqlsrv_fetch_array($stmt_loker_stats, SQLSRV_FETCH_ASSOC)): ?>
        lokerLabels.push("<?php echo addslashes($loker_stats['judul_loker']); ?>");
        lokerData.push(<?php echo $loker_stats['jumlah_pelamar']; ?>);
    <?php endwhile; ?>

    // Grafik Bar - Lowongan Paling Banyak Diminati
    var ctx = document.getElementById('lokerChart').getContext('2d');
    var lokerChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: lokerLabels,
            datasets: [{
                label: 'Jumlah Pelamar',
                data: lokerData,
                backgroundColor: '#4CAF50',
                borderColor: '#388E3C',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    function openModal(action, pelamarId, lokerId) {
        // Set form action dan input yang sesuai
        document.getElementById('statusForm').action = window.location.href;
        document.getElementById('pelamar_id').value = pelamarId;
        document.getElementById('loker_id').value = lokerId;

        // Tampilkan modal
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    document.getElementById('closeModal').onclick = function () {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    // Handle form submission (approve or reject)
    document.getElementById('statusForm').onsubmit = function (event) {
        event.preventDefault(); // Mencegah form submit secara normal

        var form = event.target;
        var action = form.querySelector('button[type="submit"]:focus').value;

        // Ambil data dari form
        var formData = new FormData(form);

        // Kirim data menggunakan AJAX (fetch)
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())  // Mengambil response JSON
            .then(data => {
                // Menampilkan feedback dari server
                alert(data.message);  // Tampilkan pesan sukses/gagal

                if (data.status === 'success') {
                    // Update status di tabel tanpa reload halaman
                    // Misalnya, update kolom status di baris yang bersangkutan
                    var statusCell = document.querySelector(`[data-pelamar-id="${formData.get('pelamar_id')}"] .status-cell`);
                    statusCell.textContent = action === 'approve' ? 'Diterima' : 'Ditolak';
                }

                // Tutup modal setelah aksi
                document.getElementById('confirmModal').classList.add('hidden');
            })
            .catch(error => {
                alert("Terjadi kesalahan saat memproses permintaan.");
                console.error(error);
            });
    };
    function showPelamarDetail(username) {
        const modal = document.getElementById('detailModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');

        // Tampilkan modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Ambil data detail pelamar menggunakan fetch
        fetch('get_user_details.php', {
            method: 'GET', // atau 'POST' jika diperlukan
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    // Proses data untuk ditampilkan di frontend
                    document.getElementById('nama').innerText = data.nama;
                    document.getElementById('foto').src = data.foto;
                    document.getElementById('alamat').innerText = data.alamat;
                    document.getElementById('tanggal_lahir').innerText = data.tanggal_lahir;
                    document.getElementById('gender').innerText = data.gender;
                    document.getElementById('telepon').innerText = data.telepon;
                    document.getElementById('email').innerText = data.email;
                    document.getElementById('bio').innerText = data.bio;
                }
            })
            .catch(error => console.error('Error fetching user details:', error));

    }



</script>