<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai session
session_start();

// Inklusi koneksi database
require_once '../../include/koneksi.php';

// Variabel untuk menangani error
$error = '';

// Memeriksa apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong";
    } else {
        try {
            // Query untuk mencari user berdasarkan username
            $query = "SELECT * FROM dbo.[user] WHERE username = ?";

            // Menyiapkan query
            $params = array($username);
            $stmt = sqlsrv_prepare($conn, $query, $params);

            if ($stmt === false) {
                // Log error details
                $errors = sqlsrv_errors();
                $error = "Prepare statement failed: " . print_r($errors, true);
            } else {
                // Execute the statement
                if (sqlsrv_execute($stmt)) {
                    // Fetch the user
                    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        // Verify password (assuming password is hashed - replace with proper password_verify if using password_hash)
                        if ($password === $row['password']) {
                            // Menyimpan data user di session
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['nama'] = $row['nama'];
                            $_SESSION['Role'] = $row['Role_idRole'];

                            // Redirect berdasarkan role pengguna
                            switch ($row['Role_idRole']) {
                                case '2': // Pelamar
                                    $fotoQuery = "SELECT foto FROM pelamar WHERE User_username = ?";
                                    $stmtFoto = sqlsrv_prepare($conn, $fotoQuery, array($username));
                                    sqlsrv_execute($stmtFoto);
                                    if ($fotoRow = sqlsrv_fetch_array($stmtFoto, SQLSRV_FETCH_ASSOC)) {
                                        $_SESSION['userFoto'] = $fotoRow['foto'] 
                                            ? 'data:image/jpeg;base64,' . base64_encode($fotoRow['foto']) 
                                            : 'https://via.placeholder.com/40';
                                    }
                                    header("Location: /polka/dashboard/pelamar");
                                    exit;

                                case '3': // Perusahaan
                                    $fotoQuery = "SELECT foto FROM perusahaan WHERE User_username = ?";
                                    $stmtFoto = sqlsrv_prepare($conn, $fotoQuery, array($username));
                                    sqlsrv_execute($stmtFoto);
                                    if ($fotoRow = sqlsrv_fetch_array($stmtFoto, SQLSRV_FETCH_ASSOC)) {
                                        $_SESSION['perusahaanFoto'] = $fotoRow['foto'] 
                                            ? 'data:image/jpeg;base64,' . base64_encode($fotoRow['foto']) 
                                            : 'https://via.placeholder.com/40';
                                    }
                                    header("Location: /polka/dashboard/perusahaan");
                                    exit;

                                default: // Admin
                                    header("Location: /polka/dashboard/admin");
                                    exit;
                            }
                        } else {
                            $error = "Password salah";
                        }
                    } else {
                        $error = "Username tidak ditemukan";
                    }
                } else {
                    // Log execution error
                    $errors = sqlsrv_errors();
                    $error = "Kesalahan eksekusi: " . print_r($errors, true);
                }
            }
        } catch (Exception $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- <style>
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .slide-in { animation: slideIn 0.6s ease-out forwards; }
    </style> -->
</head>
<body class="bg-gray-50">
    <div class="flex justify-center items-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Login</h1>
            <p class="text-center text-gray-600 mb-4">Unlock Endless Possibilities with Us</p>

            <!-- Error Handling -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" 
                        value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200" required>
                </div>

                <button type="submit"
    class="w-full bg-green-400 hover:bg-green-500 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
    Login
</button>
            </form>

            <div class="text-center mt-4">
                <a href="/polka/auth/forgot-password" class="text-sm text-gray-600 hover:underline">Forgot Password?</a>
            </div>

            <div class="text-center mt-4">
                <a href="./register" class="text-sm text-gray-600 hover:underline">Create Account</a>
            </div>
        </div>
    </div>
</body>
</html>