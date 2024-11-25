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
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong";
    } else {
        // Query untuk mencari user berdasarkan username dan password
        $query = "SELECT * FROM dbo.[user] WHERE username = ? AND password = ?";



        // Menyiapkan query
        $stmt = sqlsrv_prepare($conn, $query, array(&$username, &$password, &$role));

        if ($stmt && sqlsrv_execute($stmt)) {
            // Mengecek apakah user ditemukan
            if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                // Menyimpan data user di session
                $_SESSION['username'] = $row['username'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['Role'] = $row['Role_idRole'];

                // Redirect berdasarkan role pengguna
                if ($_SESSION['Role'] == '2') {
                    header("Location: /dashboard/pelamar");
                } elseif ($_SESSION['Role'] == '3') {
                    header("Location: /dashboard/perusahaan");
                } else {
                    header("Location: /dashboard/admin");
                }
                exit;
            } else {
                $error = "Username atau password salah";
            }
        } else {
            $error = "Terjadi kesalahan saat login";
        }
    }
}
// Simpan foto ke session setelah login

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Menyertakan Tailwind CSS -->
</head>

<body class="bg-gray-50">

    <!-- Form Login -->
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Login</h1>
            <p class="text-center text-gray-600 mb-4">Unlock Endless Possibilities with Us</p>

            <!-- Menampilkan error jika ada -->
            <?php if ($error): ?>
                <div class="text-red-500 text-center mb-4">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form method="POST" action="/auth/login">
                <!-- Username -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <!-- Button Login -->
                <button type="submit"
                    class="w-full bg-amber-400 hover:bg-amber-500 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                    Login
                </button>
            </form>

            <!-- Forgot Password Link -->
            <div class="text-center mt-4">
                <a href="/forgot-password.php" class="text-sm text-gray-600 hover:underline">Forgot Password?</a>
            </div>

            <!-- Create Account Link -->
            <div class="text-center mt-4">
                <a href="/register.php" class="text-sm text-gray-600 hover:underline">Create Account</a>
            </div>
        </div>
        <script>
            const loginButton = document.getElementById('login-button');
            const loginForm = document.getElementById('login-form');
            const loginContainer = document.getElementById('login-container');


        </script>
    </div>

</body>

</html>