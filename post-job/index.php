<?php
// Mulai session
session_start();

// Inklusi koneksi database
include '../include/koneksi.php';

// Variabel untuk menangani error
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tipe_loker = $_POST['tipe_loker'] ?? '';
    $lokasi = $_POST['lokasi'] ?? '';
    $gaji = $_POST['gaji'] ?? null; // Gaji bisa null
    $tanggal_deadline = $_POST['tanggal_deadline'] ?? '';
    $username_perusahaan = $_SESSION['username'] ?? '';

    // Validasi input
    if (empty($judul) || empty($deskripsi) || empty($tipe_loker) || empty($lokasi) || empty($tanggal_deadline) || empty($username_perusahaan)) {
        $response = ['status' => 'error', 'message' => 'Semua kolom wajib diisi kecuali gaji.'];
    } elseif (!in_array($tipe_loker, ['Part Time', 'Magang', 'Full Time'])) {
        $response = ['status' => 'error', 'message' => 'Tipe loker tidak valid.'];
    } else {
        // Generate ID Loker
        $idLoker = strtoupper(bin2hex(random_bytes(4)));

        // Query untuk insert data loker baru
        $query = "INSERT INTO dbo.loker (idLoker, judul, deskripsi, tipe_loker, lokasi, gaji, Username_perusahaan, tanggal_post, tanggal_deadline)
                  VALUES ('$idLoker', '$judul', '$deskripsi', '$tipe_loker', '$lokasi', '$gaji', '$username_perusahaan', GETDATE(), '$tanggal_deadline')";

        // Menyiapkan dan menjalankan query
        $stmt = sqlsrv_query($conn, $query);

        if ($stmt) {
            $response = ['status' => 'success', 'message' => 'Lowongan kerja berhasil diposting.'];
        } else {
            $errorMessages = [];
            if (($errors = sqlsrv_errors()) != null) {
                foreach ($errors as $err) {
                    $errorMessages[] = "SQLSTATE: " . $err['SQLSTATE'] . " - Error Code: " . $err['code'] . " - Message: " . $err['message'];
                }
            }
            $response = ['status' => 'error', 'message' => implode(' | ', $errorMessages)];
        }
    }

    // Kembalikan JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.45s ease-out forwards;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="bg-gray-50">

    <?php include '../include/header.php'; ?>
    <br><br><br><br>
    <!-- Form Post a Job -->
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg slide-in">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Post a Job</h1>
            <form method="POST" action="post-job" onsubmit="handleFormSubmit(event)">
                <div class="mb-4">
                    <label for="judul" class="block text-sm font-medium text-gray-700">Job Title</label>
                    <input type="text" name="judul" id="judul" class="w-full p-3 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full p-3 border rounded-lg"
                        required></textarea>
                </div>
                <div class="mb-4">
                    <label for="tipe_loker" class="block text-sm font-medium text-gray-700">Job Type</label>
                    <select name="tipe_loker" id="tipe_loker" class="w-full p-3 border rounded-lg" required>
                        <option value="Part Time">Part Time</option>
                        <option value="Magang">Magang</option>
                        <option value="Full Time">Full Time</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="lokasi" id="lokasi" class="w-full p-3 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="gaji" class="block text-sm font-medium text-gray-700">Salary (Optional)</label>
                    <input type="text" name="gaji" id="gaji" class="w-full p-3 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700">Application
                        Deadline</label>
                    <input type="date" name="tanggal_deadline" id="tanggal_deadline"
                        class="w-full p-3 border rounded-lg" required>
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition duration-200">
                    Post Job
                </button>
            </form>
        </div>
    </div>

    <script>
        function handleFormSubmit(event) {
            event.preventDefault();
            const form = event.target;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        openModal('successModal');
                    } else {
                        document.getElementById('errorMessage').innerText = data.message || 'An error occurred.';
                        openModal('errorModal');
                    }
                })
                .catch(error => {
                    document.getElementById('errorMessage').innerText = 'An error occurred: ' + error.message;
                    openModal('errorModal');
                });
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
    <!-- Modal Sukses -->
    <div id="successModal" class="modal">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-green-900 font-bold text-xl mb-4">Berhasil!</h2>
            <p>Lowongan kerja berhasil diposting.</p>
            <button onclick="closeModal('successModal')"
                class="mt-4 bg-green-900 hover:bg-green-700 text-white py-2 px-4 rounded-lg">Tutup</button>
        </div>
    </div>

    <!-- Modal Gagal -->
    <div id="errorModal" class="modal">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-red-600 font-bold text-xl mb-4">Gagal!</h2>
            <p id="errorMessage">Ada kesalahan saat memposting lowongan kerja.</p>
            <button onclick="closeModal('errorModal')"
                class="mt-4 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">Tutup</button>
        </div>
    </div><br><br><br>
    <?php include '../include/footer.php'; ?>
</body>

</html>