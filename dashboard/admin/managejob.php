<?php
session_start();
include '../../include/koneksi.php';

// Pastikan pengguna adalah admin
if ($_SESSION['Role'] != 1) {
    header("Location: login.php");
    exit;
}

// Menangani permintaan AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    // Mengambil detail loker
    if ($action === 'getDetail') {
        $idLoker = $_POST['idLoker'] ?? null;
        if ($idLoker) {
            $sql = "SELECT l.idLoker, l.judul, l.deskripsi, l.lokasi, l.gaji, l.tanggal_deadline, p.nama AS nama_perusahaan
                    FROM loker l
                    INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username
                    WHERE l.idLoker = ?";
            $params = array(&$idLoker);
            $stmt = sqlsrv_prepare($conn, $sql, $params);
            if (sqlsrv_execute($stmt) && $detail = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo json_encode($detail);
                exit;
            }
        }
        echo json_encode(['error' => 'Gagal memuat data loker.']);
        exit;
    }

    // Memperbarui status loker
    if ($action === 'updateStatus') {
        $idLoker = $_POST['idLoker'] ?? null;
        $status = $_POST['status'] ?? null;
        if ($idLoker && in_array($status, ['approve', 'reject'])) {
            $sql_check_status = "SELECT status_approval FROM loker WHERE idLoker = ?";
            $params_check = array(&$idLoker);
            $stmt_check = sqlsrv_prepare($conn, $sql_check_status, $params_check);

            if (sqlsrv_execute($stmt_check)) {
                $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
                $current_status = $row['status_approval'];

                if ($current_status == 2 || $current_status == 3) {
                    echo json_encode(['message' => 'Aksi sudah dilakukan.']);
                    exit;
                }

                $statusApproval = ($status == 'approve') ? 2 : 3;
                $sql = "UPDATE loker SET status_approval = ? WHERE idLoker = ?";
                $params = array($statusApproval, $idLoker);
                $stmt = sqlsrv_prepare($conn, $sql, $params);
            } else {
                die(print_r(sqlsrv_errors(), true));
            }
        } else {
            echo json_encode(['message' => 'Gagal memperbarui status.']);
        }
        exit;
    }
}

// Query untuk mendapatkan semua loker
$sql_loker = "SELECT l.idLoker, l.judul, l.lokasi, l.gaji, l.tanggal_post, p.nama AS nama_perusahaan, l.status_approval 
              FROM loker l
              INNER JOIN perusahaan p ON l.Username_perusahaan = p.User_username";
$result_loker = sqlsrv_query($conn, $sql_loker);

// Menyertakan header
include "./header.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Loker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        let selectedLokerId = null;

        // Fungsi pencarian
        function searchLoker() {
            let searchTerm = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#lokerTable tbody tr");

            rows.forEach(row => {
                let title = row.cells[0].textContent.toLowerCase();
                if (title.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Membuka modal dan mengambil data detail loker
        function openModal(idLoker) {
            selectedLokerId = idLoker;
            const modal = document.getElementById('detailModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Memuat data detail loker
            fetch('kelola_loker.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=getDetail&idLoker=${idLoker}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        modalContent.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                    } else {
                        modalTitle.innerText = data.judul || 'Detail Loker';
                        modalContent.innerHTML = `
                <p><strong>Perusahaan:</strong> ${data.nama_perusahaan}</p>
                <p><strong>Deskripsi:</strong> ${data.deskripsi}</p>
                <p><strong>Lokasi:</strong> ${data.lokasi}</p>
                <p><strong>Gaji:</strong> ${data.gaji}</p>
                <p><strong>Deadline:</strong> ${data.tanggal_deadline}</p>
            `;
                    }
                })
                .catch(error => {
                    modalContent.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat memuat data.</p>';
                    console.error(error);
                });
        }

        // Menutup modal
        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Fungsi approve dan reject
        function updateLokerStatus(action) {
            fetch('kelola_loker.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=updateStatus&idLoker=${selectedLokerId}&status=${action}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        closeModal();
                        location.reload();
                    } else if (data.error) {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Gagal memperbarui status.');
                    console.error(error);
                });
        }
    </script>
</head>

<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Kelola Loker</h2>

        <!-- Input Pencarian -->
        <div class="mb-4">
            <input type="text" id="searchInput" oninput="searchLoker()" class="border p-2 w-full"
                placeholder="Cari berdasarkan judul loker...">
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-lg mb-6" style="max-height: 300px; overflow-y: auto;">
            <table id="lokerTable" class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul Loker</th>
                        <th class="px-6 py-3 text-left">Perusahaan</th>
                        <th class="px-6 py-3 text-left">Lokasi</th>
                        <th class="px-6 py-3 text-left">Gaji</th>
                        <th class="px-6 py-3 text-left">Tanggal Ditambahkan</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                        <tr class="border-b">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['nama_perusahaan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['gaji']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['tanggal_post']->format('Y-m-d')); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($loker['status_approval'] == 2 || $loker['status_approval'] == 3): ?>
                                    <span class="text-gray-500">Aksi sudah dilakukan</span>
                                <?php else: ?>
                                    <button onclick="openModal('<?php echo htmlspecialchars($loker['idLoker']); ?>')"
                                        class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Lihat</button>
                                    <button onclick="updateLokerStatus('approve')"
                                        class="bg-green-500 text-white px-4 py-2 rounded mr-2">Setujui</button>
                                    <button onclick="updateLokerStatus('reject')"
                                        class="bg-red-500 text-white px-4 py-2 rounded">Tolak</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Detail Loker -->
        <div id="detailModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h3 id="modalTitle" class="text-xl font-bold mb-4">Detail Loker</h3>
                <div id="modalContent"></div>
                <div class="mt-4 flex justify-end">
                    <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
                </div>
            </div>
        </div>

    </div>
</body>

</html>