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
    <!-- Form Register -->
    <div class="flex justify-center items-center h-screen">
        <div
            class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md slide-in shadow-lg hover:shadow-2xl transition-all duration-300">
            <h1 class="text-3xl font-bold text-center text-gray-900 mb-1">Register</h1>
            <p class="text-center text-gray-600 mb-2">Create an Account with Us</p>

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
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
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

                <!-- Nama -->
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input type="text" name="nama" id="nama" placeholder="Nama"
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
                            required>
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <select name="role" id="role" placeholder="Role"
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
                            required>
                            <option value="2">Pelamar</option>
                            <option value="3">Perusahaan</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                        Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input type="password" id="confirmPassword" placeholder="Confirm Password"
                            class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-200"
                            required>
                    </div>
                </div>

                <!-- Button Register -->
                <button type="submit"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg transition duration-200">
                    Register
                </button>
            </form>

            <!-- Already have an account Link -->
            <div class="text-center mt-4">
                <a href="/auth/login" class="text-sm text-gray-700 hover:underline">Already have an account? Login</a>
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