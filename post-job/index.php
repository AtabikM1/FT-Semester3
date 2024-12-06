<?php
// Mulai session
session_start();

// Inklusi koneksi database
include '../include/koneksi.php';

// Variabel untuk menangani error
$response = [];

if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 3) {
    header("Location: login.php");
    exit;
}

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
        $sql = "INSERT INTO dbo.loker (idLoker, judul, deskripsi, tipe_loker, lokasi, gaji, Username_perusahaan, tanggal_post, tanggal_deadline, status_approval)
                VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE(), ?, 1)";
        
        // Menyiapkan dan menjalankan query dengan parameter binding
        $params = array($idLoker, $judul, $deskripsi, $tipe_loker, $lokasi, $gaji, $username_perusahaan, $tanggal_deadline);
        $stmt = sqlsrv_prepare($conn, $sql, $params);

        if ($stmt && sqlsrv_execute($stmt)) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-slate-50 to-purple-50">
    <?php include '../include/header.php'; ?>

    <!-- Main Content -->
    <div class="min-h-screen py-24 relative">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-100 rounded-full opacity-10"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-100 rounded-full opacity-10"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Header Section -->
            <div class="text-center mb-12 fade-in">
                <h1 class="text-4xl font-bold text-slate-800 mb-4">Post a New Job</h1>
                <p class="text-lg text-slate-600">Create an engaging job posting to attract the perfect candidate</p>
            </div>

            <!-- Form Card -->
            <div class="glass-effect rounded-2xl shadow-lg border border-slate-200/60 p-8 mb-8 fade-in">
                <form method="POST" action="post-job" onsubmit="handleFormSubmit(event)" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Job Title -->
                        <div class="space-y-2">
                            <label for="judul" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-briefcase mr-2 text-blue-400"></i>Job Title
                            </label>
                            <input type="text" name="judul" id="judul" 
                                class="px-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                                required>
                        </div>

                        <!-- Job Type -->
                        <div class="space-y-2">
                            <label for="tipe_loker" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-clock mr-2 text-blue-400"></i>Job Type
                            </label>
                            <select name="tipe_loker" id="tipe_loker" 
                                class="px-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                                required>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Magang">Internship</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="deskripsi" class="block text-sm font-semibold text-slate-700">
                            <i class="fas fa-align-left mr-2 text-blue-400"></i>Job Description
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="8" 
                            class="p-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                            required></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Location -->
                        <div class="space-y-2">
                            <label for="lokasi" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-400"></i>Location
                            </label>
                            <input type="text" name="lokasi" id="lokasi" 
                                class="px-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                                required>
                        </div>

                        <!-- Salary -->
                        <div class="space-y-2">
                            <label for="gaji" class="block text-sm font-semibold text-slate-700">
                                <i class="fas fa-money-bill-wave mr-2 text-blue-400"></i>Salary (Optional)
                            </label>
                            <input type="text" name="gaji" id="gaji" 
                                class="px-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                                placeholder="e.g., 5,000,000 - 8,000,000">
                        </div>
                    </div>

                    <!-- Deadline -->
                    <div class="space-y-2">
                        <label for="tanggal_deadline" class="block text-sm font-semibold text-slate-700">
                            <i class="fas fa-calendar-alt mr-2 text-blue-400"></i>Application Deadline
                        </label>
                        <input type="date" name="tanggal_deadline" id="tanggal_deadline" 
                            class="px-2 form-input w-full rounded-lg border-slate-200 bg-white/50 shadow-sm focus:border-slate-300 focus:ring-slate-200" 
                            required>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-150 ease-in-out">
                            <i class="fas fa-paper-plane mr-2"></i>Post Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <i class="fas fa-check-circle text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Job Posted Successfully!</h3>
                <p class="text-slate-600 mb-6">Your job posting has been successfully published and is now live.</p>
                <button onclick="closeModal('successModal')" 
                    class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-lg hover:from-green-700 hover:to-green-800 transition duration-150">
                    <i class="fas fa-check mr-2"></i>Close
                </button>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <i class="fas fa-exclamation-circle text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Error</h3>
                <p id="errorMessage" class="text-slate-600 mb-6">An error occurred while posting the job.</p>
                <button onclick="closeModal('errorModal')" 
                    class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 transition duration-150">
                    <i class="fas fa-times mr-2"></i>Close
                </button>
            </div>
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
                        form.reset();
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
            const modal = document.getElementById(modalId);
            modal.style.display = 'flex';
            modal.classList.add('fade-in');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
            modal.classList.remove('fade-in');
        }
    </script>

    <?php include '../include/footer.php'; ?>
</body>

</html>