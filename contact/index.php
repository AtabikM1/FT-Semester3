<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isUserLoggedIn = isset($_SESSION['username']);
$currentUser = $isUserLoggedIn ? $_SESSION['username'] : null;
$userRole = $isUserLoggedIn ? $_SESSION['Role'] : null;
$perusahaanFoto = isset($_SESSION['perusahaanFoto']) ? $_SESSION['perusahaanFoto'] : 'https://via.placeholder.com/40';
$userFoto = isset($_SESSION['userFoto']) ? $_SESSION['userFoto'] : 'https://via.placeholder.com/40';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | PolinemaCareer</title>
    <link rel="icon" href="../asset/logooo.png" type="image/png">
    <link rel="stylesheet" href="../asset/css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
    </style>
</head>

<body>
    <?php include '../include/header.php'; ?>
    <section class="relative py-24 overflow-hidden fade-in">
        <div class="container mx-auto px-4 md:px-6 lg:px-8 relative">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mt-4 mb-6">
                    Contact <span class="text-[#1C2056]">Us</span>
                </h2>
                <p class="text-gray-600 text-lg">
                    Have questions? We're here to help and provide you with the best support possible.
                </p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-1">
                    <div
                        class="bg-[#1C2056] text-white rounded-2xl p-8 shadow-xl transform hover:scale-105 transition-all duration-300">
                        <h3 class="text-2xl font-bold mb-8">Contact Information</h3>

                        <div class="space-y-6">
                            <div class="flex items-center gap-4 group">
                                <div class="p-3 bg-white/10 rounded-lg group-hover:bg-amber-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium group-hover:text-amber-400 transition-colors">Phone</p>
                                    <p class="text-gray-300">081235305531</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 group">
                                <div class="p-3 bg-white/10 rounded-lg group-hover:bg-amber-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium group-hover:text-amber-400 transition-colors">Email</p>
                                    <p class="text-gray-300">contact@polinemacarrier.com</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 group">
                                <div class="p-3 bg-white/10 rounded-lg group-hover:bg-amber-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium group-hover:text-amber-400 transition-colors">Location</p>
                                    <p class="text-gray-300">Malang, East Java, Indonesia</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex gap-4">
                            <a href="https://www.x.com/polinemacarrier/" target="_blank"
                                class="p-3 bg-white/10 rounded-full hover:bg-amber-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/polinemacarrier/" target="_blank"
                                class="p-3 bg-white/10 rounded-full hover:bg-amber-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z" />
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/company/polinemacarrier/" target="_blank"
                                class="p-3 bg-white/10 rounded-full hover:bg-amber-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="aspect-w-16 aspect-h-7 rounded-xl overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.4905335321547!2d112.61243491477913!3d-7.946611494277532!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827687d272e7%3A0x789ce9a636cd3aa2!2sPoliteknik%20Negeri%20Malang!5e0!3m2!1sen!2sid!4v1645523456789!5m2!1sen!2sid"
                            width="100%" height="410" style="border:0;" allowfullscreen="" loading="lazy"
                            class="rounded-xl">
                        </iframe>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php include '../include/mountain-background.php'; ?>
    <?php include '../include/footer.php'; ?>
</body>

</html>