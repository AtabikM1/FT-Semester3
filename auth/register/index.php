<?php
// Mulai session
session_start();

// Inklusi koneksi database
include '../../include/koneksi.php';

// Variabel untuk menangani error
$error = '';

// Memeriksa apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $username = $_POST['username'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validasi input
    if (empty($username) || empty($nama) || empty($password) || empty($role)) {
        $error = "Semua kolom harus diisi";
    } else {
        // Enkripsi password menggunakan MD5
        $hashedPassword = md5($password);

        // Query untuk insert data pengguna baru
        $query = "INSERT INTO dbo.[user] (username, nama, Role_idRole, password) VALUES (?, ?, ?, ?)";

        // Menyiapkan query
        $stmt = sqlsrv_prepare($conn, $query, array(&$username, &$nama, &$role, &$hashedPassword));

        if ($stmt && sqlsrv_execute($stmt)) {
            // Redirect setelah berhasil daftar
            header("Location: /auth/login");
            exit;
        } else {
            $error = "Terjadi kesalahan saat mendaftar";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Animasi register form dari kanan ke tengah */
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
    </style>
</head>

<body class="bg-gray-50">

    <!-- Form Register -->
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md slide-in">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Register</h1>
            <p class="text-center text-gray-600 mb-4">Create an Account with Us</p>

            <!-- Menampilkan error jika ada -->
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <!-- Form Register -->
            <form method="POST" action="/auth/register" onsubmit="return validatePassword()">
                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                        <option value="2">Pelamar</option>
                        <option value="3">Perusahaan</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" id="confirmPassword"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Button Register -->
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                    Register
                </button>
            </form>

            <!-- Already have an account Link -->
            <div class="text-center mt-4">
                <a href="/auth/login" class="text-sm text-blue-600 hover:underline">Already have an account? Login</a>
            </div>
        </div>
    </div>

    <script>
        // Validasi Confirm Password
        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                alert("Password and Confirm Password must match.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>