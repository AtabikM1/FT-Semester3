<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengecek status login
$isUserLoggedIn = isset($_SESSION['username']);
$currentUser = $isUserLoggedIn ? $_SESSION['username'] : null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolinemaCareer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-[#1C2056] shadow-lg text-white backdrop-blur-md">
        <div class="h-16 px-6 md:px-8 mx-auto max-w-7xl">
            <div class="flex items-center justify-between h-full">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="../../asset/logooo.png" alt="Logo" class="w-10 h-10">
                    <a href="/dashboard/admin"
                        class="font-bold text-2xl md:text-3xl text-amber-400 hover:text-amber-300 hover:scale-105 transition-all duration-500">
                        PolinemaCareer
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="hidden md:flex items-center gap-10">
                    <a href="/dashboard/admin/contact.php"
                        class="text-gray-200 font-medium hover:text-amber-300 hover:scale-105 transition duration-300">Contact
                        Us</a>
                </nav>

                <!-- Admin Profile -->
                <div class="hidden md:flex items-center gap-8 font-semibold">
                    <?php if ($isUserLoggedIn): ?>
                        <div class="ml-44 relative group">
                            <div
                                class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity duration-300">
                                <div class="relative">
                                    <img src="../../asset/admin.jpg" alt="Admin Photo"
                                        class="w-8 h-8 rounded-full shadow-md border-amber-400">
                                </div>
                                <span class="group-hover:rotate-180 transition-transform duration-300">▼</span>
                            </div>
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <a href="/dashboard/admin"
                                    class="block px-4 py-2 text-gray-800 hover:bg-amber-100 hover:text-amber-600">Dashboard</a>
                                <div class="h-px bg-gray-200 my-2"></div>
                                <a href="/auth/logout" class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/auth/login"
                            class="text-gray-200 font-medium hover:text-amber-300 hover:scale-105 transition duration-300">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="flex md:hidden">
            <button id="mobile-menu-btn" class="text-gray-200 hover:text-amber-300 transition duration-300">
                ☰
            </button>
        </div>
    </header>

    <!-- Mobile Dropdown -->
    <div id="mobile-menu" class="hidden fixed inset-0 bg-[#1C2056] bg-opacity-95 z-50 p-8">
        <button id="close-mobile-menu" class="text-gray-300 hover:text-amber-300 absolute top-4 right-4">✕</button>
        <nav class="mt-10 space-y-6">
            <a href="/contact"
                class="text-lg font-medium text-gray-200 hover:text-amber-300 transition duration-300">Contact
                Us</a>
            <?php if ($isUserLoggedIn): ?>
                <a href="/dashboard/admin"
                    class="text-lg font-medium text-gray-200 hover:text-amber-300 transition duration-300">Dashboard</a>
                <a href="/auth/logout"
                    class="text-lg font-medium text-red-600 hover:text-red-400 transition duration-300">Logout</a>
            <?php else: ?>
                <a href="/auth/login"
                    class="text-lg font-medium text-gray-200 hover:text-amber-300 transition duration-300">Login</a>
            <?php endif; ?>
        </nav>
    </div>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const closeMenuBtn = document.getElementById('close-mobile-menu');

        menuBtn.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
        closeMenuBtn.addEventListener('click', () => mobileMenu.classList.add('hidden'));
    </script>

</body>

</html>