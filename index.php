<?php
include './include/header.php';

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']);
$userRole = $isLoggedIn ? $_SESSION['Role'] : null;
?>

<!-- Start of HTML -->
<!DOCTYPE html>
<html lang="en">
<br><br>
<link rel="stylesheet" href="/asset/css/style.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolinemaCareer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../asset/logooo.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/lucide.min.js"></script>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-900 font-['Inter']">

    <!-- Hero section -->
    <section class="relative min-h-screen bg-gradient-to-br from-[#f0f8ff] to-[#e8f4ff] overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-20 -left-96 w-[300px] md:w-[600px] h-[300px] md:h-[600px] bg-[#4a90e2]/10 rounded-full blur-3xl opacity-80 animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-[300px] md:w-[600px] h-[300px] md:h-[600px] bg-[#4a90e2]/5 rounded-full blur-3xl animate-pulse"></div>
        
        <!-- Floating shapes -->
        <div class="absolute top-40 right-20 w-6 h-6 md:w-8 md:h-8 bg-amber-400/30 rounded-full animate-float hidden md:block"></div>
        <div class="absolute bottom-44 left-28 w-8 h-8 md:w-12 md:h-12 bg-[#1C2056]/20 rounded-lg rotate-45 animate-float-delay hidden md:block"></div>
        
        <div class="container mx-auto px-4 md:px-6 lg:px-20 py-20 md:py-12 lg:py-24">
            <div class="flex flex-col lg:flex-row max-w-7xl mx-auto gap-8 md:gap-16 items-center">
                <!-- Left content -->
                <div class="space-y-6 md:space-y-10 animate-slideInLeft text-center lg:text-left">
                    <div class="space-y-4 md:space-y-6">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                            Welcome to 
                            <span class="inline-block">
                                <span class="text-[#1C2056]">Polinema</span><span class="text-amber-400">Career</span>
                                <i data-lucide="sparkles" class="inline-block w-8 h-8 md:w-12 md:h-12 text-amber-400 ml-2 animate-bounce"></i>
                            </span>
                        </h1>
                        <p class="text-lg md:text-xl text-gray-600 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                            Your gateway to a brighter future. Connect with top companies and explore endless career opportunities with us.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 md:gap-6 items-center justify-center lg:justify-start">
                        <a href="<?php echo !$isLoggedIn ? './auth/login' : ($userRole == '2' ? '/browse-jobs' : '/post-job'); ?>"
                            class="group w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 bg-[#1C2056] text-white text-base md:text-lg font-semibold rounded-xl shadow-lg hover:bg-amber-400 hover:text-indigo-900 transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                            Get Started
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 md:w-5 md:h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="/about-us" class="text-gray-600 hover:text-[#1C2056] font-medium transition-colors">
                            Learn More →
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 md:gap-8 pt-6 border-t border-gray-200">
                        <div class="text-center lg:text-left">
                            <h4 class="text-2xl md:text-4xl font-bold text-[#1C2056]">500+</h4>
                            <p class="text-sm md:text-base text-gray-600">Companies</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <h4 class="text-2xl md:text-4xl font-bold text-[#1C2056]">1000+</h4>
                            <p class="text-sm md:text-base text-gray-600">Job Posts</p>
                        </div>
                        <div class="text-center lg:text-left col-span-2 sm:col-span-1">
                            <h4 class="text-2xl md:text-4xl font-bold text-[#1C2056]">5000+</h4>
                            <p class="text-sm md:text-base text-gray-600">Candidates</p>
                        </div>
                    </div>
                </div>

                <!-- Right content -->
                <div class="relative hidden lg:block animate-slideInRight">
                    <div class="absolute inset-0 bg-[#1C2056]/10 rounded-2xl blur-3xl transform rotate-6"></div>
                    <img src="asset/Company-rafiki.png" alt="Hero Image" 
                        class="relative z-10 rounded-2xl transform hover:scale-105 transition-transform duration-500 shadow-xl w-full max-w-2xl mx-auto" />
                </div>
            </div>
        </div>
    </section>
    <!-- End of Hero Section -->


    <!-- Feature Section -->
    <section class="relative bg-white py-24 overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-gray-50/50 to-transparent"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-amber-100/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-blue-100/30 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 lg:px-20 relative">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-amber-400 font-semibold text-lg mb-4 block">Why Choose Us</span>
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">
                    Why Choose <span class="text-[#1C2056] relative">
                        PolinemaCareer
                        <svg class="absolute -right-8 -top-6 w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                        </svg>
                    </span>
                </h2>
                <p class="text-gray-600 text-lg">Discover the advantages that make us stand out in connecting talents with opportunities</p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                <?php
                $features = [
                    [
                        "icon" => "iconnetwork.png",
                        "title" => "Large Network",
                        "desc" => "Connect with 500+ companies worldwide and explore diverse opportunities across industries.",
                        "color" => "bg-blue-50"
                    ],
                    [
                        "icon" => "boost.png",
                        "title" => "Career Boost",
                        "desc" => "Access premium resources and tools designed to enhance your professional journey.",
                        "color" => "bg-amber-50"
                    ],
                    [
                        "icon" => "trusted.png",
                        "title" => "Trusted Platform",
                        "desc" => "Join thousands who've found their dream careers through our secure and reliable platform.",
                        "color" => "bg-indigo-50"
                    ]
                ];

                foreach ($features as $feature) {
                    echo '<div class="group">';
                    echo '<div class="p-8 rounded-2xl ' . $feature['color'] . ' hover:scale-105 transition-all duration-300 h-full transform hover:shadow-xl">';
                    echo '<div class="flex items-center gap-6 mb-6">';
                    echo '<div class="bg-white p-3 rounded-xl shadow-md group-hover:shadow-lg transition-shadow">';
                    echo '<img src="asset/' . $feature['icon'] . '" alt="' . $feature['title'] . '" class="w-12 h-12 object-contain">';
                    echo '</div>';
                    echo '<h3 class="text-2xl font-bold text-slate-900">' . $feature['title'] . '</h3>';
                    echo '</div>';
                    echo '<p class="text-gray-600 leading-relaxed text-lg">' . $feature['desc'] . '</p>';
                    
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <!-- Additional Stats or Trust Indicators -->
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
</body>

</html>