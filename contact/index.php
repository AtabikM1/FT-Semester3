<?php
include '../include/header.php';
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isUserLoggedIn = isset($_SESSION['username']);
$currentUser = $isUserLoggedIn ? $_SESSION['username'] : null;
$userRole = $isUserLoggedIn ? $_SESSION['Role'] : null;

// Ambil foto profil dari session
$perusahaanFoto = isset($_SESSION['perusahaanFoto']) ? $_SESSION['perusahaanFoto'] : 'https://via.placeholder.com/40';
$userFoto = isset($_SESSION['userFoto']) ? $_SESSION['userFoto'] : 'https://via.placeholder.com/40';
?>

<!DOCTYPE html>
<html lang="en">
<br><br><br><br>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom fade-in animation */
        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom transition for smooth hover effects */
        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }

        /* Hover effect for icons */
        .hover-effect:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col justify-center items-center py-20 fade-in">

    <!-- Header Section -->
    <div class="text-center mb-10 fade-in">
        <h1 class="text-5xl text-amber-400 font-semibold">Contact Us</h1>
        <h2 class="text-xl font-light text-gray-600 mt-4">
            Reach out to us with your thoughts, and we'll get back to you!
        </h2>
    </div>

    <!-- Contact Information Section -->
    <div class="flex justify-center items-center w-full fade-in">
        <div class="bg-[#1C2056] text-white rounded-lg p-10">
            <div class="z-10 space-y-6">
                <h1 class="text-3xl font-semibold">Contact Information</h1>
            </div>

            <!-- Contact Details -->
            <div class="z-10 space-y-6 mt-8">
                <div class="flex items-center gap-4 group cursor-pointer smooth-transition hover-effect">
                   <img src="../asset/phone.png" class="group-hover:text-amber-400 transition-colors w-6 h-6" alt="">
                    <p class="group-hover:text-amber-400 transition-colors">081235305531</p>
                </div>
                <div class="flex items-center gap-4 group cursor-pointer smooth-transition hover-effect">
                    <img src="../asset/mail.png" class="group-hover:text-amber-400 transition-colors w-6 h-6" alt="">
                    <p class="group-hover:text-amber-400 transition-colors">contact@polinemacarrier.com</p>
                </div>
                <div class="flex items-center gap-4 group cursor-pointer smooth-transition hover-effect">
                    <img src="../asset/location.png" class="group-hover:text-amber-400 transition-colors w-6 h-6" alt="">
                    <p class="group-hover:text-amber-400 transition-colors">Malang, East Java, Indonesia</p>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="z-10 flex space-x-4 mt-8">
                <div class="bg-white/10 p-2 rounded-full cursor-pointer smooth-transition hover:bg-[#1DA1F2]">
                    <img src="../asset/phone.png" class="w-5 h-5 text-white" >
                       
                    </img>
                </div>
                <div class="bg-white/10 p-2 rounded-full cursor-pointer smooth-transition hover:bg-[#E4405F]">
                    <img src="../asset/mail.png" class="w-5 h-5 text-white" >
                        
                    </img>
                </div>
                <div class="bg-white/10 p-2 rounded-full cursor-pointer smooth-transition hover:bg-[#5865F2]">
                    <img src="../asset/location.png" class="w-5 h-5 text-white" >
                       
                    </img>
                </div>
            </div>
        </div>
    </div>

</body>

</html>