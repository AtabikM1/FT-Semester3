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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validasi input
    if (empty($username) || empty($nama) || empty($password) || empty($role)) {
        $error = "Semua kolom harus diisi";
    } else {
        // Query untuk insert data pengguna baru
        $query = "INSERT INTO dbo.[user] (username, nama, Role_idRole, password) VALUES (?, ?, ?, ?)";

        // Menyiapkan query
        $stmt = sqlsrv_prepare($conn, $query, array(&$username, &$nama, &$role, &$password));

        if ($stmt && sqlsrv_execute($stmt)) {
            // Redirect setelah berhasil daftar
            header("Location: /polka/auth/login");
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirmPassword');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const message = document.getElementById('message');

            // Toggle visibility for Password
            togglePassword.addEventListener('click', () => {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    togglePassword.textContent = 'Hide';
                } else {
                    passwordField.type = 'password';
                    togglePassword.textContent = 'Show';
                }
            });

            // Toggle visibility for Confirm Password
            toggleConfirmPassword.addEventListener('click', () => {
                if (confirmPasswordField.type === 'password') {
                    confirmPasswordField.type = 'text';
                    toggleConfirmPassword.textContent = 'Hide';
                } else {
                    confirmPasswordField.type = 'password';
                    toggleConfirmPassword.textContent = 'Show';
                }
            });

            // Validate Confirm Password
            confirmPasswordField.addEventListener('input', () => {
                if (confirmPasswordField.value !== passwordField.value) {
                    message.classList.remove('hidden');
                } else {
                    message.classList.add('hidden');
                }
            });
        });
    </script>
    <!-- <style>
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
    </style> -->
</head>

<body class="bg-gray-50">

    <!-- Form Register -->
    <div class="flex justify-center items-center h-screen pt-12 pb-12">
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
            <form method="POST" action="/auth/register">
                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                        <option value="" disabled selected>--pilih role--</option>
                        <option value="2">Pelamar</option>
                        <option value="3">Perusahaan</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm font-medium text-blue-600">
                            Show
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="confirmPassword" id="confirmPassword"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                        <button type="button" id="toggleConfirmPassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-sm font-medium text-blue-600">
                            Show
                        </button>
                    </div>
                </div>

                <!-- Menampilkan error jika password tidak cocok -->
                <!-- backend tetep dikasi error 404 , bisa dimatiin js soalnya  -->
                <div id="message" class="text-sm text-red-500 hidden">
                    Passwords do not match!
                </div>

                <!-- Button Register -->
                <button type="submit"
                    class="w-full bg-green-400 hover:bg-green-500 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                    Register
                </button>
            </form>

            <!-- Already have an account Link -->
            <div class="text-center mt-4">
                <p>Already have an account? <a href="/polka/auth/login" class="text-sm text-gray-600 hover:underline hover:text-green-500">Login</a></p>

            </div>
        </div>
    </div>

</body>

</html>