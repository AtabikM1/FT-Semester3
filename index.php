<?php

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);
$userRole = $isLoggedIn ? $_SESSION['Role'] : null;

// Koneksi ke database
include './include/koneksi.php';

// Query untuk mengambil data artikel
$sql = "SELECT TOP 6 IdArtikel, judul, sub_judul, konten, cover
FROM dbo.artikel
ORDER BY IdArtikel DESC;
";

// Menjalankan query menggunakan SQLSRV
$stmt = sqlsrv_query($conn, $sql);

$articles = [];
if ($stmt) {
    // Fetch semua data artikel
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $articles[] = $row;
    }
} else {
    // Menangani error query
    echo json_encode([
        "success" => false,
        "message" => "Query gagal dieksekusi",
        "error" => sqlsrv_errors()
    ]);
    exit();
}

?>



<!-- Start of HTML -->
<!DOCTYPE html>
<html lang="en">
<br><br>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | PolinemaCareer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../asset/logooo.png" type="image/png">
    <link rel="stylesheet" href="asset/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/lucide.min.js"></script>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-900 font-['Inter']">
    <?php include './include/header.php'; ?>



    <script>
        function toggleContent(id) {
            const previewContent = document.getElementById(`preview-content-${id}`);
            const hiddenContent = document.getElementById(`hidden-content-${id}`);
            if (hiddenContent.classList.contains('hidden')) {
                hiddenContent.classList.remove('hidden');
                previewContent.classList.add('hidden');
            } else {
                hiddenContent.classList.add('hidden');
                previewContent.classList.remove('hidden');
            }
        }
    </script>



    <!-- Hero section -->
    <section class="relative min-h-screen bg-gradient-to-br from-[#f0f8ff] to-[#e8f4ff] overflow-hidden">
        <!-- Decorative elements -->
        <div
            class="absolute top-20 -left-96 w-[300px] md:w-[600px] h-[300px] md:h-[600px] bg-[#4a90e2]/10 rounded-full blur-3xl opacity-80 animate-pulse">
        </div>
        <div
            class="absolute bottom-20 right-20 w-[300px] md:w-[600px] h-[300px] md:h-[600px] bg-[#4a90e2]/5 rounded-full blur-3xl animate-pulse">
        </div>

        <!-- Abstract Geometric Shapes -->
        <svg class="absolute top-0 right-0 w-1/2 h-full opacity-20" viewBox="0 0 800 800" fill="none">
            <circle cx="400" cy="400" r="200" fill="url(#circleGradient1)" />
            <circle cx="600" cy="200" r="150" fill="url(#circleGradient2)" />
            <circle cx="200" cy="600" r="100" fill="url(#circleGradient3)" />

            <defs>
                <radialGradient id="circleGradient1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(400 400) rotate(90) scale(200)">
                    <stop offset="0%" stop-color="#4a90e2" stop-opacity="0.2" />
                    <stop offset="100%" stop-color="#1C2056" stop-opacity="0" />
                </radialGradient>
                <radialGradient id="circleGradient2" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(600 200) rotate(90) scale(150)">
                    <stop offset="0%" stop-color="#FFB800" stop-opacity="0.15" />
                    <stop offset="100%" stop-color="#FFD700" stop-opacity="0" />
                </radialGradient>
                <radialGradient id="circleGradient3" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(200 600) rotate(90) scale(100)">
                    <stop offset="0%" stop-color="#1C2056" stop-opacity="0.1" />
                    <stop offset="100%" stop-color="#4a90e2" stop-opacity="0" />
                </radialGradient>
            </defs>
        </svg>

        <!-- Animated Wave -->
        <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#1C2056" fill-opacity="0.05"
                d="M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                <animate attributeName="d" dur="10s" repeatCount="indefinite"
                    values="
                    M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z;
                    M0,160L48,181.3C96,203,192,245,288,261.3C384,277,480,267,576,234.7C672,203,768,149,864,138.7C960,128,1056,160,1152,165.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z;
                    M0,192L48,197.3C96,203,192,213,288,229.3C384,245,480,267,576,250.7C672,235,768,181,864,181.3C960,181,1056,235,1152,234.7C1248,235,1344,181,1392,154.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" />
            </path>
        </svg>
        <!-- Floating shapes -->
        <div
            class="absolute top-40 right-20 w-6 h-6 md:w-8 md:h-8 bg-amber-400/30 rounded-full animate-float hidden md:block">
        </div>

        <div class="container mx-auto px-4 md:px-6 lg:px-20">
            <div
                class="flex flex-col lg:flex-row max-w-7xl h-screen justify-center mx-auto gap-8 md:gap-16 items-center">
                <!-- Left content -->
                <div class="space-y-6 md:space-y-10 animate-slideInLeft text-center lg:text-left">
                    <div class="space-y-4 md:space-y-6">
                        <h1
                            class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                            Selamat Datang di
                            <span class="inline-block">
                                <span class="text-[#1C2056]">Polinema</span><span class="text-amber-400">Career</span>
                                <i data-lucide="sparkles"
                                    class="inline-block w-8 h-8 md:w-12 md:h-12 text-amber-400 ml-2 animate-bounce"></i>
                            </span>
                        </h1>
                        <p
                            class="text-lg md:text-xl text-gray-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium delay-300">
                            Your gateway to a brighter future. Connect with top companies and explore endless career
                            opportunities with us.
                        </p>
                    </div>

                    <!-- Stats with animations -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-8 pt-6 border-gray-200">

                    </div>
                </div>

                <!-- Right content with animation -->
                <div class="relative hidden lg:block animate-slideInRight">
                    <div class="absolute inset-0 bg-[#1C2056]/10 rounded-2xl blur-3xl transform rotate-6 animate-pulse">
                    </div>
                    <img src="asset/Company-rafiki.png" alt="Hero Image"
                        class="relative z-10 rounded-2xl transform hover:scale-105 transition-transform duration-500 shadow-xl w-full max-w-2xl mx-auto animate-float" />
                </div>
            </div>
        </div>
    </section>
    <!-- End of Hero Section -->


    <!-- Feature Section -->
    <section class="relative bg-white py-24 overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50/50 to-transparent"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-amber-100/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-blue-100/30 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 lg:px-20 relative">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-20 scroll-animate animate-fadeInUp">
                <span class="text-amber-400 font-semibold text-lg mb-4 block">Why Choose Us</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">
                    Why Choose <span class="text-[#1C2056] relative">
                        PolinemaCareer
                        <svg class="absolute -right-8 -top-6 w-6 h-6 text-amber-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                        </svg>
                    </span>
                </h2>
                <p class="text-gray-600 text-lg">Discover the advantages that make us stand out in connecting talents
                    with opportunities</p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                <?php
                $features = [
                    [
                        "icon" => "iconnetwork.png",
                        "title" => "Large Network",
                        "desc" => "Connect with 500+ companies worldwide and explore diverse opportunities across industries.",
                        "color" => "bg-blue-50",
                        "delay" => "delay-100"
                    ],
                    [
                        "icon" => "boost.png",
                        "title" => "Career Boost",
                        "desc" => "Access premium resources and tools designed to enhance your professional journey.",
                        "color" => "bg-amber-50",
                        "delay" => "delay-300"
                    ],
                    [
                        "icon" => "trusted.png",
                        "title" => "Trusted Platform",
                        "desc" => "Join thousands who've found their dream careers through our secure and reliable platform.",
                        "color" => "bg-indigo-50",
                        "delay" => "delay-500"
                    ]
                ];

                foreach ($features as $feature) {
                    echo '<div class="group scroll-animate animate-fadeInUp ' . $feature['delay'] . ' h-full">';
                    echo '<div class="p-8 rounded-2xl ' . $feature['color'] . ' hover:scale-105 transition-all duration-300 h-full flex flex-col">';
                    echo '<div class="flex items-center gap-6 mb-6">';
                    echo '<div class="bg-white p-3 rounded-xl shadow-md group-hover:shadow-lg transition-shadow">';
                    echo '<img src="asset/' . $feature['icon'] . '" alt="' . $feature['title'] . '" class="w-12 h-12 object-contain">';
                    echo '</div>';
                    echo '<h3 class="text-2xl font-bold text-slate-900">' . $feature['title'] . '</h3>';
                    echo '</div>';
                    echo '<p class="text-gray-600 leading-relaxed text-lg flex-grow">';
                    echo $feature['desc'];
                    echo '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <!-- Additional Stats  -->
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white shadow-sm">
                    <h4 class="text-3xl font-bold text-[#1C2056] mb-2">95%</h4>
                    <p class="text-gray-600">Success Rate</p>
                </div>
                <div class="p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white shadow-sm">
                    <h4 class="text-3xl font-bold text-[#1C2056] mb-2">24/7</h4>
                    <p class="text-gray-600">Support</p>
                </div>
                <div class="p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white shadow-sm">
                    <h4 class="text-3xl font-bold text-[#1C2056] mb-2">100+</h4>
                    <p class="text-gray-600">Industries</p>
                </div>
                <div class="p-6 rounded-xl bg-gradient-to-br from-gray-50 to-white shadow-sm">
                    <h4 class="text-3xl font-bold text-[#1C2056] mb-2">4.9/5</h4>
                    <p class="text-gray-600">User Rating</p>
                </div>
            </div>
        </div>
    </section>
    <!-- End of Feature Section -->

    <!-- Featured Jobs Section -->
    <section class="relative bg-white py-24 overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full">
            <svg class="absolute top-0 right-0 w-64 h-64 text-amber-400/10" viewBox="0 0 200 200">
                <path fill="currentColor"
                    d="M45,-78.1C58.3,-71.3,69.1,-58.9,78.1,-44.7C87.1,-30.4,94.3,-15.2,93.8,-0.3C93.3,14.7,85.1,29.3,76.1,43.5C67.1,57.7,57.3,71.3,44,78.1C30.7,84.9,15.3,84.9,0.4,84.2C-14.5,83.5,-29,82.1,-42.4,75.3C-55.8,68.5,-68.1,56.3,-77.5,42.1C-86.9,27.9,-93.4,13.9,-92.9,0.3C-92.4,-13.4,-84.9,-26.8,-75.6,-38.1C-66.3,-49.4,-55.2,-58.6,-42.5,-65.9C-29.8,-73.2,-14.9,-78.6,0.8,-79.9C16.5,-81.2,31.7,-78.5,45,-78.1Z"
                    transform="translate(100 100)" />
            </svg>
            <svg class="absolute bottom-0 left-0 w-64 h-64 text-[#1C2056]/10" viewBox="0 0 200 200">
                <path fill="currentColor"
                    d="M38.1,-64.3C51.1,-56.7,64.6,-49.5,72.7,-38.1C80.8,-26.7,83.5,-11.2,82.8,4.1C82.1,19.4,78,34.5,69.8,47.2C61.6,59.9,49.3,70.2,35.4,75.7C21.5,81.2,6,82,-9.7,79.7C-25.4,77.4,-41.3,72,-54.8,62.4C-68.3,52.8,-79.4,39,-84.1,23.4C-88.8,7.8,-87.1,-9.7,-80.9,-25.1C-74.7,-40.5,-64,-53.8,-50.6,-61.4C-37.2,-69,-18.6,-70.9,-2.2,-67.5C14.2,-64.1,28.4,-55.4,38.1,-64.3Z"
                    transform="translate(100 100)" />
            </svg>
        </div>

        <div class="container mx-auto px-6 lg:px-20 relative">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 scroll-animate animate-fadeInUp">
                <span class="text-amber-400 font-semibold text-lg mb-4 block">Explore Opportunities</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">
                    The Featured Jobs
                </h2>
                <p class="text-gray-600 text-lg">Discover diverse career paths across different industries</p>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $categories = [
                    [
                        "icon" => "code",
                        "title" => "Software Development",
                        "desc" => "Join leading tech companies and work on cutting-edge projects.",
                        "color" => "bg-blue-50 text-blue-600",
                        "delay" => "delay-100"
                    ],
                    [
                        "icon" => "briefcase",
                        "title" => "Business & Finance",
                        "desc" => "Explore opportunities in finance, consulting, and business operations.",
                        "color" => "bg-amber-50 text-amber-600",
                        "delay" => "delay-300"
                    ],
                    [
                        "icon" => "pen-tool",
                        "title" => "Design & Creative",
                        "desc" => "Create impactful designs and shape user experiences.",
                        "color" => "bg-green-50 text-green-600",
                        "delay" => "delay-500"
                    ],
                    [
                        "icon" => "trending-up",
                        "title" => "Marketing & Sales",
                        "desc" => "Drive growth and connect brands with their audiences.",
                        "color" => "bg-purple-50 text-purple-600",
                        "delay" => "delay-700"
                    ]
                ];

                foreach ($categories as $category) {
                    echo '<div class="scroll-animate animate-fadeInUp ' . $category['delay'] . ' h-full">';
                    echo '<div class="group hover:scale-105 transition-all duration-300 h-full">';
                    echo '<div class="p-8 rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 relative overflow-hidden h-full flex flex-col">';
                    echo '<div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-' . explode(" ", $category['color'])[0] . '/20 to-transparent rounded-full transform translate-x-8 -translate-y-8"></div>';
                    echo '<div class="relative flex flex-col h-full">';
                    echo '<div class="' . $category['color'] . ' w-12 h-12 rounded-xl flex items-center justify-center mb-6">';
                    echo '<i data-lucide="' . $category['icon'] . '" class="w-6 h-6"></i>';
                    echo '</div>';
                    echo '<h3 class="text-xl font-bold text-slate-900 mb-2">' . $category['title'] . '</h3>';
                    echo '<p class="text-gray-600 flex-grow">' . $category['desc'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <!-- Bottom Stats -->
            <div class="mt-20 text-center scroll-animate animate-fadeInUp delay-900">
                <a href="/browse-jobs"
                    class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-[#1C2056] rounded-xl hover:bg-amber-400 hover:text-[#1C2056] transition-all duration-300 transform hover:scale-105 shadow-lg group">
                    Explore All Categories
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <p class="mt-4 text-gray-600">Discover <?php echo rand(1000, 2000); ?>+ open positions</p>
            </div>
        </div>
    </section>
    <!-- End of Featured Jobs Section -->

    <!-- Decorative Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <!-- Top Left Circles -->
        <svg class="absolute -top-24 -left-24 w-96 h-96 text-white/5" viewBox="0 0 200 200" fill="currentColor">
            <circle cx="100" cy="100" r="80" />
            <circle cx="100" cy="100" r="60" />
            <circle cx="100" cy="100" r="40" />
        </svg>

        <!-- Bottom Right Pattern -->
        <svg class="absolute -bottom-32 -right-32 w-[40rem] h-[40rem] text-white/5" viewBox="0 0 400 400" fill="none">
            <defs>
                <pattern id="grid" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 40L40 0M0 0L40 40" stroke="currentColor" stroke-width="1" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>

        <!-- Floating Dots -->
        <div class="absolute top-1/4 left-1/3">
            <svg class="w-8 h-8 text-amber-400/20" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="4" />
            </svg>
        </div>
        <div class="absolute bottom-1/3 right-1/4">
            <svg class="w-12 h-12 text-white/10" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="4" />
            </svg>
        </div>

        <!-- Abstract Lines -->
        <svg class="absolute top-1/2 left-0 w-[30rem] h-64 text-white/5" viewBox="0 0 400 200" fill="none"
            stroke="currentColor">
            <path d="M0 100h400" stroke-width="1" stroke-dasharray="8 8" />
            <path d="M0 150h400" stroke-width="1" stroke-dasharray="8 8" />
            <path d="M0 50h400" stroke-width="1" stroke-dasharray="8 8" />
        </svg>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Testimonials Grid -->
        <!-- <div class="grid md:grid-cols-3 gap-8">
                <?php
                $testimonials = [
                    [
                        "name" => "Lorem Ipsum",
                        "role" => "Lorem Ipsum",
                        "company" => "Lorem Corp",
                        "image" => "asset/temp.png",
                        "quote" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                        "rating" => 5
                    ],
                    [
                        "name" => "Dolor Sit",
                        "role" => "Lorem Manager",
                        "company" => "Ipsum Solutions",
                        "image" => "asset/temp.png",
                        "quote" => "Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                        "rating" => 5
                    ],
                    [
                        "name" => "Amet Consectetur",
                        "role" => "Lorem Designer",
                        "company" => "Dolor Studio",
                        "image" => "asset/temp.png",
                        "quote" => "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
                        "rating" => 5
                    ]
                ];

                foreach ($testimonials as $testimonial) {
                    echo '<div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">';
                    echo '<div class="relative">';
                    echo '<svg class="absolute -top-4 -left-4 w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003z"/></svg>';

                    echo '<div class="flex items-center gap-4 mb-6">';
                    echo '<img src="' . $testimonial['image'] . '" alt="' . $testimonial['name'] . '" class="w-16 h-16 rounded-full object-cover border-2 border-amber-400">';
                    echo '<div>';
                    echo '<h3 class="text-xl font-bold text-white">' . $testimonial['name'] . '</h3>';
                    echo '<p class="text-gray-300">' . $testimonial['role'] . ' at ' . $testimonial['company'] . '</p>';
                    echo '</div>';
                    echo '</div>';

                    echo '<p class="text-gray-300 mb-6">"' . $testimonial['quote'] . '"</p>';

                    echo '<div class="flex gap-1">';
                    for ($i = 0; $i < $testimonial['rating']; $i++) {
                        echo '<svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>';
                    }
                    echo '</div>';

                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div> -->
    </div>
    <!-- </section> -->
    <!-- End of Testimonial Section -->

    <!-- Call to Action Section -->
    <section class="relative py-24 overflow-hidden">
        <!-- Background with gradient and pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#1C2056] to-[#2d317a]">
            <div class="absolute inset-0 bg-[url('../asset/pattern.svg')] opacity-10"></div>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Circle -->
            <div class="absolute top-1/4 -right-20">
                <svg class="w-40 h-40 text-amber-400/10" viewBox="0 0 200 200">
                    <circle cx="100" cy="100" r="80" fill="currentColor">
                        <animate attributeName="r" from="80" to="90" dur="3s" repeatCount="indefinite" />
                    </circle>
                </svg>
            </div>

            <!-- Abstract Wave -->
            <div class="absolute -bottom-10 left-0 w-full">
                <svg class="w-full h-24 text-white/5" viewBox="0 0 1440 100" preserveAspectRatio="none">
                    <path fill="currentColor"
                        d="M0,50 C150,20 350,0 500,10 C650,20 800,40 1000,30 C1200,20 1400,10 1440,0 L1440,100 L0,100 Z">
                    </path>
                </svg>
            </div>

            <!-- Dots Pattern -->
            <div class="absolute left-10 top-1/2 transform -translate-y-1/2">
                <div class="grid grid-cols-3 gap-4">
                    <?php for ($i = 0; $i < 9; $i++): ?>
                        <div class="w-2 h-2 rounded-full bg-white/10"></div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                <!-- Left Content -->
                <div class="flex-1 text-center lg:text-left scroll-animate animate-fadeInUp delay-100">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Ready to Take the Next Step in Your Career?
                    </h2>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto lg:mx-0">
                        Join thousands of professionals who've found their dream jobs through PolinemaCareer. Your
                        future starts here.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="<?php echo !$isLoggedIn ? './auth/login' : ($userRole == '2' ? '/browse-jobs' : '/post-job'); ?>"
                            class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-[#1C2056] bg-amber-400 rounded-xl hover:bg-amber-300 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Get Started Now
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Right Content - Image -->
                <div
                    class="flex justify-between items-center gap-6 lg:max-w-7xl scroll-animate animate-fadeInUp delay-300">
                    <div class="rounded-xl shadow-lg relative">
                        <img src="./asset/Good team-bro.png" alt="Call To Action"
                            class="relative z-10 w-[120%] h-[400px] object-cover rounded-xl">
                        <span
                            class="absolute inset-0 w-full h-full bg-gradient-to-br from-purple-500/30 to-pink-500/30 rounded-xl"></span>
                        <span
                            class="absolute top-2 left-2 w-full h-full bg-gradient-to-br from-blue-500/30 to-teal-500/30 rounded-xl"></span>
                        <span
                            class="absolute -top-3 -left-12 w-full h-full bg-gradient-to-br from-amber-500/30 to-orange-500/30 rounded-xl"></span>
                        <span
                            class="absolute top-4 -left-16 w-full h-full bg-gradient-to-br from-emerald-500/30 to-lime-500/30 rounded-xl"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of Call to Action Section -->

    <!-- Artikel Terbaru Section -->
    <section class="relative bg-white py-24 overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50/50 to-transparent"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-amber-100/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-blue-100/30 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 lg:px-20 relative">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 scroll-animate animate-fadeInUp">
                <span
                    class="w-fit mx-auto text-amber-400 font-semibold text-lg mb-4 flex justify-center items-center px-4 py-2 bg-gray-100 rounded-full">Latest
                    Updates</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">
                    Latest Artikel
                </h2>
                <p class="text-gray-600 text-lg">Find insight and tips to growth your career</p>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($articles as $article): ?>
                    <div
                        class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden scroll-animate animate-fadeInUp">
                        <div class="relative overflow-hidden aspect-video">
                            <?php if ($article['cover']): ?>
                                <img src="<?= htmlspecialchars($article['cover']) ?>"
                                    alt="<?= htmlspecialchars($article['judul']) ?>" class="w-full h-full object-cover ">
                            <?php else: ?>
                                <img src="asset/article.png" alt="Default Cover" class="w-48 h-48 object-cover">
                            <?php endif; ?>
                            <div class="absolute inset-0 from-black/50 to-transparent"></div>
                        </div>

                        <div class="p-6 flex flex-col">
                            <div>
                                <h3
                                    class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-[#1C2056] transition-colors">
                                    <?= htmlspecialchars($article['judul']) ?>
                                </h3>

                                <?php if (!empty($article['sub_judul'])): ?>
                                    <p class="text-amber-500 font-semibold mb-3">
                                        <?= htmlspecialchars($article['sub_judul']) ?>
                                    </p>
                                <?php endif; ?>

                                <?php
                                $contentWords = explode(' ', strip_tags($article['konten']));
                                $isLongContent = count($contentWords) > 23;
                                $shortContent = implode(' ', array_slice($contentWords, 0, 23));
                                ?>

                                <p id="content-<?= $article['IdArtikel'] ?>"
                                    class="text-gray-600 mb-4 line-clamp-3 transition-all duration-300">
                                    <?= htmlspecialchars($shortContent) ?>
                                    <?php if ($isLongContent): ?>
                                        <span class="hidden" id="full-content-<?= $article['IdArtikel'] ?>">
                                            <?= htmlspecialchars(implode(' ', $contentWords)) ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <?php if ($isLongContent): ?>
                                <button onclick="toggleContent(<?= $article['IdArtikel'] ?>)"
                                    class="inline-flex items-center text-[#1C2056] font-semibold hover:text-amber-500 transition-colors">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


        </div>
        <?php include './include/mountain-background.php'; ?>

    </section>

    <!-- Background -->


    <?php
    include 'include/footer.php';

    ?>

    <script>
        window.addEventListener("load", function () {
            // Cari semua link (anchor) yang mengarah ke halaman lain
            const links = document.querySelectorAll('a[href], button[data-href]'); // Menambahkan button dengan data-href

            links.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    event.preventDefault();
                    setTimeout(function () {
                        // Jika link adalah anchor, pindah ke href, jika button, ambil data-href
                        const target = link.getAttribute('href') || link.getAttribute('data-href');
                        window.location.href = target;
                    }, 500);
                });
            });
        });
    </script>
    <script>
        lucide.createIcons();
    </script>
    <script>
        // Scroll based animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.scroll-animate').forEach((element) => {
                observer.observe(element);
            });
        });
        function toggleContent(id) {
            const contentElement = document.getElementById(`content-${id}`);
            const fullContentElement = document.getElementById(`full-content-${id}`);

            if (contentElement.classList.contains('line-clamp-3')) {
                // Jika konten dipotong, tampilkan full content
                contentElement.classList.remove('line-clamp-3');
                contentElement.innerHTML = fullContentElement.innerHTML;
            } else {
                // Jika konten penuh, kembalikan ke tampilan pendek
                const shortContent = fullContentElement.innerHTML.split(' ').slice(0, 23).join(' ') + '...';
                contentElement.classList.add('line-clamp-3');
                contentElement.innerHTML = shortContent;
            }
        }

    </script>
</body>

</html>