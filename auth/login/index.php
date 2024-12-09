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
    <!-- Nature-themed SVG Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden opacity-15 w-screen h-screen">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 800" class="absolute w-full h-full object-cover"
            preserveAspectRatio="xMidYMid slice">
            <!-- Sky Gradient -->
            <defs>
                <linearGradient id="skyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:#4A90E2;stop-opacity:0.2" />
                    <stop offset="100%" style="stop-color:#1C2056;stop-opacity:0.1" />
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="url(#skyGradient)" />

            <!-- Far Mountains -->
            <path fill="#1C2056" opacity="0.3" d="M0,800 
                L0,400 
                L200,500 
                L400,350 
                L600,450 
                L800,380 
                L1000,420 
                L1200,350 
                L1440,400 
                L1440,800 Z" />

            <!-- Middle Mountains -->
            <path fill="#1C2056" opacity="0.4" d="M0,800 
                L0,500 
                L240,600 
                L480,450 
                L720,550 
                L960,480 
                L1200,550 
                L1440,500 
                L1440,800 Z" />

            <!-- Front Mountains -->
            <path fill="#1C2056" opacity="0.5" d="M0,800 
                L0,600 
                L300,650 
                L600,550 
                L900,650 
                L1200,600 
                L1440,650 
                L1440,800 Z" />

            <!-- Snow Caps -->
            <path fill="white" opacity="0.4" d="M200,500 
                L240,480 
                L280,500 
                M600,450 
                L640,430 
                L680,450 
                M1000,420 
                L1040,400 
                L1080,420" />

            <!-- Clouds -->
            <g opacity="0.6">
                <!-- Cloud 1 -->
                <path fill="white" d="M100,200 
                    a20,20 0 0,1 40,0
                    a20,20 0 0,1 40,0
                    a20,20 0 0,1 40,0
                    q0,20 -60,20
                    q-60,0 -60,-20" />

                <!-- Cloud 2 -->
                <path fill="white" d="M800,150 
                    a25,25 0 0,1 50,0
                    a25,25 0 0,1 50,0
                    a25,25 0 0,1 50,0
                    q0,25 -75,25
                    q-75,0 -75,-25" />

                <!-- Cloud 3 -->
                <path fill="white" d="M400,100 
                    a15,15 0 0,1 30,0
                    a15,15 0 0,1 30,0
                    a15,15 0 0,1 30,0
                    q0,15 -45,15
                    q-45,0 -45,-15" />
            </g>

            <!-- Trees on Mountains -->
            <g opacity="0.6">
                <!-- Tree Group 1 -->
                <g transform="translate(250, 600)">
                    <path fill="#1a472a" d="M0,-40 L10,0 L-10,0 Z" />
                    <path fill="#1a472a" d="M0,-55 L15,-15 L-15,-15 Z" />
                    <rect x="-2" y="0" width="4" height="10" fill="#5d4037" />
                </g>

                <!-- Tree Group 2 -->
                <g transform="translate(700, 580)">
                    <path fill="#1a472a" d="M0,-55 L15,-15 L-15,-15 Z" />
                    <path fill="#1a472a" d="M0,-70 L20,-30 L-20,-30 Z" />
                    <rect x="-3" y="-15" width="6" height="15" fill="#5d4037" />
                </g>

                <!-- Tree Group 3 -->
                <g transform="translate(1100, 620)">
                    <path fill="#1a472a" d="M0,-48 L12,-8 L-12,-8 Z" />
                    <path fill="#1a472a" d="M0,-63 L17,-23 L-17,-23 Z" />
                    <rect x="-2.5" y="-8" width="5" height="12" fill="#5d4037" />
                </g>
            </g>

            <!-- Birds -->
            <g opacity="0.4">
                <path fill="#1C2056" d="M300,200 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
                <path fill="#1C2056" d="M850,150 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
                <path fill="#1C2056" d="M600,180 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
            </g>
        </svg>
    </div>
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