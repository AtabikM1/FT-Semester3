<?php
include '../include/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<br><br><br><br>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col justify-center items-center py-20">

    <!-- Header Section -->
    <div class="text-center mb-10">
        <h1 class="text-5xl text-amber-400 font-semibold">Contact Us</h1>
        <h2 class="text-xl font-light text-gray-600 mt-4">
            Reach out to us with your thoughts, and we'll get back to you!
        </h2>
    </div>

    <!-- Contact Information Section -->
    <div class="bg-white rounded-lg shadow-xl flex justify-center items-center p-0">
        <div class="bg-[#1C2056] text-white rounded-lg p-10 max-w-md w-full flex flex-col justify-between">
            <div class="z-10 space-y-6">
                <h1 class="text-3xl font-semibold">Contact Information</h1>
                <p class="text-gray-300">
                    Fill up the form and we will get back to you within 24 hours.
                </p>
            </div>

            <!-- Contact Details -->
            <div class="z-10 space-y-6 mt-8">
                <div class="flex items-center gap-4 group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:text-amber-400 transition-colors w-6 h-6"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.25 6.75l5.25 3.5v7.5l-5.25 3.5v-14zM12 15v4.5l5.25-3.5V6.75L12 3v12z" />
                    </svg>
                    <p class="group-hover:text-amber-400 transition-colors">081235305531</p>
                </div>
                <div class="flex items-center gap-4 group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:text-amber-400 transition-colors w-6 h-6"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 2a10 10 0 0110 10v2a2 2 0 01-2 2H14v6a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6H4a2 2 0 01-2-2V12a10 10 0 0110-10z" />
                    </svg>
                    <p class="group-hover:text-amber-400 transition-colors">contact@polinemacarrier.com</p>
                </div>
                <div class="flex items-center gap-4 group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:text-amber-400 transition-colors w-6 h-6"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.208l1.262 2.547a1 1 0 01-.25 1.17l-1.712 1.24a1 1 0 01-1.137.042l-2.537-2.016a8.948 8.948 0 01-3.616 2.68 9.045 9.045 0 01-9.788-3.232A8.948 8.948 0 011.268 9.045a1 1 0 01.042-1.137l1.24-1.712a1 1 0 011.17-.25l2.547 1.262A9.017 9.017 0 0112 3.25c1.988 0 3.902.66 5.428 1.795l1.458-2.915a1 1 0 011.172-.25l1.712 1.24a1 1 0 01.25 1.17l-1.262 2.547a8.95 8.95 0 012.68 3.616z" />
                    </svg>
                    <p class="group-hover:text-amber-400 transition-colors">Malang, East Java, Indonesia</p>
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="z-10 flex space-x-4 mt-8">
                <div class="bg-white/10 p-2 rounded-full cursor-pointer hover:bg-[#1DA1F2] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7.5 12a4.5 4.5 0 019 0 4.5 4.5 0 01-9 0zM12 3v3m0 0h-3m3 0h3M9 21v-6m3 6v-6m3 6v-6" />
                    </svg>
                </div>
                <div class="bg-white/10 p-2 rounded-full cursor-pointer hover:bg-[#E4405F] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 2a10 10 0 0110 10v2a2 2 0 01-2 2H14v6a2 2 0 01-2 2h-4a2 2 0 01-2-2v-6H4a2 2 0 01-2-2V12a10 10 0 0110-10z" />
                    </svg>
                </div>
                <div class="bg-white/10 p-2 rounded-full cursor-pointer hover:bg-[#5865F2] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.208l1.262 2.547a1 1 0 01-.25 1.17l-1.712 1.24a1 1 0 01-1.137.042l-2.537-2.016a8.948 8.948 0 01-3.616 2.68 9.045 9.045 0 01-9.788-3.232A8.948 8.948 0 011.268 9.045a1 1 0 01.042-1.137l1.24-1.712a1 1 0 011.17-.25l2.547 1.262A9.017 9.017 0 0112 3.25c1.988 0 3.902.66 5.428 1.795l1.458-2.915a1 1 0 011.172-.25l1.712 1.24a1 1 0 01.25 1.17l-1.262 2.547a8.95 8.95 0 012.68 3.616z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</body>

</html>