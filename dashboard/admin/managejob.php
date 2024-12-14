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
        echo json_encode(['error' => 'Failed to load job data.']);
        exit;
    }

    // Memperbarui status loker
    // Memperbarui status loker
    if ($action === 'updateStatus') {
        $idLoker = $_POST['idLoker'] ?? null;
        $status = $_POST['status'] ?? null;
        if ($idLoker && in_array($status, ['approve', 'reject'])) {
            // Cek status sebelumnya
            $sql_check_status = "SELECT status_approval FROM loker WHERE idLoker = ?";
            $params_check = array(&$idLoker);
            $stmt_check = sqlsrv_prepare($conn, $sql_check_status, $params_check);

            if (sqlsrv_execute($stmt_check)) {
                $row = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);
                $current_status = $row['status_approval'];

                // Cek jika status sudah 'ter' (2) atau 'tol' (3)
                if ($current_status == 2 || $current_status == 3) {
                    echo json_encode(['message' => 'Action already performed.']);
                    exit;
                }

                // Tentukan status baru
                $statusApproval = ($status == 'approve') ? 2 : 3;

                // Update status loker
                $sql = "UPDATE loker SET status_approval = ? WHERE idLoker = ?";
                $params = array($statusApproval, $idLoker);
                $stmt = sqlsrv_prepare($conn, $sql, $params);

                // if (sqlsrv_execute($stmt)) {
                //     echo json_encode(['message' => 'Status berhasil diperbarui.']);
                //     exit;
                // } else {
                //     // Menampilkan error jika query gagal
                //     die(print_r(sqlsrv_errors(), true));
                // }
            } else {
                // Menampilkan error jika query pertama gagal
                die(print_r(sqlsrv_errors(), true));
            }
        } else {
            echo json_encode(['message' => 'Failed to update status.']);
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
    <title>Manage Jobs</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables
            $('#lokerTable').DataTable({
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entry",
                    info: "Displaying _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        });

        let selectedLokerId = null;

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
                    modalContent.innerHTML = '<p class="text-red-500">An error occurred while loading data.</p>';
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
                });
        }
    </script>
</head>

<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Manage Jobs</h2>
        <div class="overflow-x-auto bg-white shadow rounded-lg mb-6">
            <table id="lokerTable" class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Job Title</th>
                        <th class="px-6 py-3 text-left">Company</th>
                        <th class="px-6 py-3 text-left">Location</th>
                        <th class="px-6 py-3 text-left">Salary</th>
                        <th class="px-6 py-3 text-left">Date Added</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($loker = sqlsrv_fetch_array($result_loker, SQLSRV_FETCH_ASSOC)): ?>
                        <tr class="border-b">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['judul']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['nama_perusahaan']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['lokasi']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($loker['gaji']); ?></td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($loker['tanggal_post']->format('Y-m-d')); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($loker['status_approval'] == 2 || $loker['status_approval'] == 3): ?>
                                    <span class="text-gray-500">Action has been taken</span>
                                <?php else: ?>
                                    <button onclick="openModal('<?php echo htmlspecialchars($loker['idLoker']); ?>')"
                                        class="bg-blue-500 text-white px-4 py-2 rounded">Detail</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <div class="p-6">
                <h2 id="modalTitle" class="text-xl font-bold mb-4">Job Details</h2>
                <p id="modalContent" class="text-gray-700">Loading...</p>
                <div class="mt-6 flex justify-end">
                    <button onclick="updateLokerStatus('approve')"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg mr-2">Approve</button>
                    <button onclick="updateLokerStatus('reject')"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Reject</button>
                    <button onclick="closeModal()" class="ml-2 px-4 py-2 bg-gray-300 rounded-lg">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>