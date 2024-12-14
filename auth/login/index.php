<?php
// Mulai session
session_start();

// Inklusi koneksi database
// include '../../include/koneksi.php';

// Variabel untuk menangani error
$error = '';

// Memeriksa apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username and password cannot be empty";
    } else {
        // Mengubah password menjadi hash MD5
        $hashedPassword = md5($password);

        // Query untuk mencari user berdasarkan username dan hashed password
        $query = "SELECT * FROM dbo.[user] WHERE username = ? AND password = ?";

        // Menyiapkan query
        $stmt = sqlsrv_prepare($conn, $query, array(&$username, &$hashedPassword));

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

                // Fetch foto berdasarkan role
                if ($row['Role_idRole'] == 3) { // Role perusahaan
                    $fotoQuery = "SELECT foto FROM perusahaan WHERE User_username = ?";
                    $stmtFoto = sqlsrv_prepare($conn, $fotoQuery, array($username));
                    sqlsrv_execute($stmtFoto);
                    if ($fotoRow = sqlsrv_fetch_array($stmtFoto, SQLSRV_FETCH_ASSOC)) {
                        $_SESSION['perusahaanFoto'] = $fotoRow['foto']
                            ? $fotoRow['foto']
                            : '../../asset/defaultpfp.jpg';
                    }
                } elseif ($row['Role_idRole'] == 2) { // Role pelamar
                    $fotoQuery = "SELECT foto FROM pelamar WHERE User_username = ?";
                    $stmtFoto = sqlsrv_prepare($conn, $fotoQuery, array($username));
                    sqlsrv_execute($stmtFoto);
                    if ($fotoRow = sqlsrv_fetch_array($stmtFoto, SQLSRV_FETCH_ASSOC)) {
                        // Ambil path foto dari database, jika kosong gunakan default
                        $_SESSION['userFoto'] = !empty($fotoRow['foto'])
                            ? $fotoRow['foto']
                            : '../../asset/defaultpfp.jpg';
                    }
                }

                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "An error occurred during login";
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
    <title>Login | PolinemaCareer</title>
    <link rel="icon" href="../asset/logooo.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Animasi login form dari kanan ke tengah */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../../include/mountain-background.php'; ?>
    <!-- Back Button -->
    <a href="/" class="absolute top-8 left-8 flex items-center text-gray-600 hover:text-gray-900 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="ml-2">Back to Home</span>
    </a>
    <!-- Form Login -->
    <div class="flex justify-center items-center h-screen">
        <div
            class="bg-white p-8 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300 w-full max-w-md slide-in border-4 border-gray-200">
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
                <div class="mb-4 relative">
                    <label for="username" class="mb-1 block text-sm font-medium text-gray-700">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input type="text" name="username" id="username" placeholder="Username"
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Password"
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
                            required>
                    </div>
                    <div class="text-right mt-1">
                        <a href="/auth/forgot-password" class="text-sm text-gray-600 hover:underline">Forgot
                            Password?</a>
                    </div>
                </div>

                <!-- Button Login -->
                <button type="submit"
                    class="w-full bg-gray-200 hover:bg-gray-300 font-semibold py-3 px-4 rounded-xl transition duration-200">
                    Login
                </button>

            </form>

            <div class="text-center mt-4 inline-flex items-center gap-2 justify-center w-full">
                <p class="text-sm text-gray-600">Don't have an account?</p>
                <a href="./register" class="text-sm text-gray-700 hover:underline">Create Account</a>
            </div>
        </div>
    </div>

</body>

</html>