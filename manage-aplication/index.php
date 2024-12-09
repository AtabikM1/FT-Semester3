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
// Menangani request JSON yang dikirimkan dengan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Membaca data JSON yang dikirim
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username'])) {
        $username = $data['username'];

        $sql_user = "EXEC GetUserProfile ?";
        $stmt_user = sqlsrv_prepare($conn, $sql_user, array($username));
        sqlsrv_execute($stmt_user);

        $user = sqlsrv_fetch_array($stmt_user, SQLSRV_FETCH_ASSOC) ?: [];

        if ($user) {
            echo json_encode([
                'nama' => htmlspecialchars($user['nama'] ?? 'Pengguna Baru'),
                'foto' => $user['foto'] ? $user['foto'] : '../../asset/defaultpfp.jpg',
                'alamat' => htmlspecialchars($user['alamat'] ?? 'Alamat belum diisi'),
                'tanggal_lahir' => isset($user['tanggal_lahir']) ? $user['tanggal_lahir']->format('Y-m-d') : 'Tanggal lahir belum diisi',
                'gender' => $user['gender'] ? ($user['gender'] == 'L' ? 'Laki-laki' : 'Perempuan') : 'Gender belum diisi',
                'telepon' => htmlspecialchars($user['telepon'] ?? 'Telepon belum diisi'),
                'email' => htmlspecialchars($user['email'] ?? 'Email belum diisi'),
                'bio' => htmlspecialchars($user['bio'] ?? 'Belum ada deskripsi.'),
                'resume' => htmlspecialchars($user['resume'] ?? 'Belum ada resume.')
            ]);
        } else {
            echo json_encode(['error' => 'Data pelamar tidak ditemukan']);
        }
    } else {
        echo json_encode(['error' => 'Username tidak ditemukan']);
    }
    exit; // Ensure no further output after JSON response
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
        u.email AS pelamar_email, 
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
                                <a href="mailto:<?php echo htmlspecialchars($pelamar['pelamar_email'] ?? ''); ?>?subject=Status Lamaran&body=Halo, %0A%0AKami ingin memberitahukan bahwa status lamaran Anda untuk posisi <?php echo htmlspecialchars($pelamar['judul_loker'] ?? ''); ?> adalah <?php echo htmlspecialchars($pelamar['deskripsi_status'] ?? ''); ?>.%0A%0ATerima kasih."
                                    class="bg-blue-500 text-white px-4 py-2 rounded">
                                    Contact the applicant's email.
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Statistik Lowongan -->
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

<!-- Modal for Dynamic Actions (Pelamar Detail, Approve/Reject) -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold mb-4" id="modalTitle">Modal Title</h3>
        <div id="modalContent" class="text-sm"></div> <!-- Konten modal akan ditambahkan di sini -->
        <div class="flex justify-between">
            <button onclick="closeModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Tutup</button>
            <button id="confirmButton" class="bg-green-500 text-black px-4 py-2 rounded hidden">Confirm</button>
            <!-- Hide this for Pelamar Detail -->
        </div>
    </div>
</div>


<script>
    function openModal(action, pelamar_id, loker_id) {
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const confirmButton = document.getElementById('confirmButton');

        // Atur judul modal berdasarkan tindakan
        if (action === 'approve') {
            modalTitle.textContent = 'Are you sure you want to approve this application?';
        } else {
            modalTitle.textContent = 'Are you sure you want to reject this application?';
        }

        // Tambahkan aksi ke tombol Confirm
        confirmButton.onclick = function () {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
            <input type="hidden" name="pelamar_id" value="${pelamar_id}">
            <input type="hidden" name="loker_id" value="${loker_id}">
            <input type="hidden" name="action" value="${action}">
        `;
            document.body.appendChild(form);
            form.submit();
        };

        // Tampilkan tombol Confirm dan modal
        confirmButton.classList.remove('hidden'); // Pastikan tombol tidak tersembunyi
        modal.classList.remove('hidden');
    }


    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }

    function showPelamarDetail(username) {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username })
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Display data in modal
                    document.getElementById('modalTitle').textContent = 'Pelamar Detail';

                    const modalContent = `
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <img src="${data.foto}" alt="Foto Pelamar" class="w-32 h-32 object-cover rounded-full border-2 border-gray-300">
                <div>
                    <h3 class="text-lg font-semibold">${data.nama}</h3>
                    <p class="text-sm text-gray-500">${data.email}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><strong>Alamat:</strong></p>
                    <p class="text-gray-700">${data.alamat}</p>
                </div>
                <div>
                    <p><strong>Tanggal Lahir:</strong></p>
                    <p class="text-gray-700">${data.tanggal_lahir}</p>
                </div>
                <div>
                    <p><strong>Gender:</strong></p>
                    <p class="text-gray-700">${data.gender}</p>
                </div>
                <div>
                    <p><strong>Telepon:</strong></p>
                    <p class="text-gray-700">${data.telepon}</p>
                </div>
            </div>
            
            <div>
                <p><strong>Resume:</strong></p>
                <p class="text-gray-700">${data.resume}</p>
            </div>
        </div>
    `;

                    document.getElementById('modalContent').innerHTML = modalContent;

                    // Show the modal
                    document.getElementById('modal').classList.remove('hidden');
                }

            });
    }
</script>