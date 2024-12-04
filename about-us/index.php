<?php
include '../include/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isUserLoggedIn = isset($_SESSION['username']);
$currentUser = $isUserLoggedIn ? $_SESSION['username'] : null;
$userRole = $isUserLoggedIn ? $_SESSION['Role'] : null;
?>

<!-- Custom Animations -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes floatAnimation {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-scaleIn {
        animation: scaleIn 0.8s ease-out forwards;
    }

    .animate-float {
        animation: floatAnimation 3s ease-in-out infinite;
    }

    .animate-delay-1 {
        animation-delay: 0.2s;
    }

    .animate-delay-2 {
        animation-delay: 0.4s;
    }

    .animate-delay-3 {
        animation-delay: 0.6s;
    }
</style>

<!-- Hero Section-->
<section class="relative bg-gradient-to-b from-[#1C2056] to-[#2d317a] text-white py-32 overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-0 left-0 w-full h-full bg-[#1C2056] opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#grid-pattern)"></path>
            </svg>
        </div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-40 pt-20">
        <div class="text-center opacity-0 animate-fadeInUp">
            <h1 class="text-5xl font-bold mb-6">About PolinemaCareer</h1>
            <p class="text-xl text-gray-200 max-w-3xl mx-auto">
                <span class="text-amber-400">Bridging the gap</span> between talent and opportunity, empowering Polinema students and alumni 
                to achieve their career aspirations.
            </p>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="w-full" preserveAspectRatio="none">
            <path fill="#ffffff" fill-opacity="1" d="M0,160L48,144C96,128,192,96,288,106.7C384,117,480,171,576,181.3C672,192,768,160,864,138.7C960,117,1056,107,1152,112C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 opacity-0 animate-fadeInUp animate-delay-1">
                <div class="transform hover:scale-105 transition-transform duration-300 bg-white rounded-xl shadow-lg p-8 border-l-4 border-[#1C2056]">
                    <h2 class="text-3xl font-bold text-[#1C2056] mb-4 flex items-center">
                        <span class="mr-3">Our Vision</span>
                        <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </h2>
                    <p class="text-gray-600 leading-relaxed">
                        To be the leading platform connecting Polinema talent with outstanding career opportunities, 
                        fostering professional growth and transforming the recruitment landscape.
                    </p>
                </div>

                <div class="transform hover:scale-105 transition-transform duration-300 bg-white rounded-xl shadow-lg p-8 border-l-4 border-amber-400">
                    <h2 class="text-3xl font-bold text-[#1C2056] mb-4 flex items-center">
                        <span class="mr-3">Our Mission</span>
                        <svg class="w-6 h-6 text-[#1C2056]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </h2>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-center transform hover:translate-x-2 transition-transform duration-300">
                            <svg class="w-5 h-5 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Provide innovative solutions for employers and job seekers
                        </li>
                        <li class="flex items-center transform hover:translate-x-2 transition-transform duration-300">
                            <svg class="w-5 h-5 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Create an efficient hiring process
                        </li>
                        <li class="flex items-center transform hover:translate-x-2 transition-transform duration-300">
                            <svg class="w-5 h-5 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Foster diversity and equal opportunities
                        </li>
                    </ul>
                </div>
            </div>
            <div class="opacity-0 animate-scaleIn animate-delay-2">
                <img src="../asset/Company-amico.png" alt="Mission and Vision" class="w-full animate-float">
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="py-20 bg-gray-50 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-100 rounded-full filter blur-3xl opacity-50 transform translate-x-32 -translate-y-16"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-100 rounded-full filter blur-3xl opacity-50 transform -translate-x-32 translate-y-16"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16 opacity-0 animate-fadeInUp">
            <h2 class="text-4xl font-bold text-[#1C2056]">Our Core Values</h2>
            <p class="mt-4 text-xl text-gray-600">The principles that guide everything we do</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <?php
            $coreValues = [
                [
                    'icon' => 'shield-check',
                    'title' => 'Integrity',
                    'description' => 'We maintain the highest standards of professionalism and ethics in all our operations.'
                ],
                [
                    'icon' => 'star',
                    'title' => 'Excellence',
                    'description' => 'We strive for excellence in every aspect of our service delivery.'
                ],
                [
                    'icon' => 'users',
                    'title' => 'Collaboration',
                    'description' => 'We believe in the power of partnerships and working together for mutual success.'
                ]
            ];

            foreach ($coreValues as $index => $value) {
                echo '<div class="bg-white p-8 rounded-xl shadow-lg opacity-0 animate-fadeInUp animate-delay-' . ($index + 1) . ' transform hover:scale-105 transition-all duration-300 border-t-4 border-amber-400">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#1C2056] to-[#2d317a] rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="' . $value['icon'] . '" class="w-6 h-6 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1C2056] mb-4">' . $value['title'] . '</h3>
                    <p class="text-gray-600">' . $value['description'] . '</p>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 opacity-0 animate-fadeInUp">
            <h2 class="text-4xl font-bold text-[#1C2056]">Meet Our Team</h2>
            <p class="mt-4 text-xl text-gray-600">The passionate individuals behind PolinemaCareer</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <?php
            $team = [
                [
                    'name' => 'Pramudya Surya',
                    'role' => 'CEO & Founder',
                    'img' => '../asset/team/pram.jpg',
                    'social' => [
                        'github' => 'https://github.com/KrystalMood',
                        'instagram' => 'https://www.instagram.com/pramudya.ap'
                    ]
                ],
                [
                    'name' => 'Giovano Alkandri',
                    'role' => 'COO',
                    'img' => '../asset/team/gio.jpg',
                    'social' => [
                        'github' => '#',
                        'instagram' => '#'
                    ]
                ],
                [
                    'name' => 'Atabik M',
                    'role' => 'Head of Marketing',
                    'img' => '../asset/team/atabik.jpg',
                    'social' => [
                        'github' => '#',
                        'instagram' => '#'
                    ]
                ],
                [
                    'name' => 'Fauzie Ikhsanul',
                    'role' => 'Lead Developer',
                    'img' => '../asset/team/fauzie.jpg',
                    'social' => [
                        'github' => '#',
                        'instagram' => '#'
                    ]
                ],
                [
                    'name' => 'Tiara Mera',
                    'role' => 'Lead Developer',
                    'img' => '../asset/team/tiara.jpg',
                    'social' => [
                        'github' => '#',
                        'instagram' => '#'
                    ]
                ],
                [
                    'name' => 'Zannur',
                    'role' => 'Lead Developer',
                    'img' => '../asset/team/zannur.jpg',
                    'social' => [
                        'github' => '#',
                        'instagram' => '#'
                    ]
                ]
            ];

            foreach ($team as $index => $member) {
                echo '<div class="bg-white rounded-xl shadow-lg p-6 opacity-0 animate-fadeInUp animate-delay-' . ($index % 3 + 1) . ' group hover:shadow-2xl transition-all duration-300">
                    <div class="relative mb-6 overflow-hidden rounded-xl">
                        <img src="' . '../asset/kera.jpg' . '" alt="' . $member['name'] . '" 
                            class="w-full h-64 object-cover transform transition duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1C2056]/80 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-6">
                            <div class="flex space-x-4">
                                <a href="' . $member['social']['github'] . '" class="text-white hover:text-amber-400 transition-colors">
                                    <i data-lucide="github" class="w-6 h-6"></i>
                                </a>
                                <a href="' . $member['social']['instagram'] . '" class="text-white hover:text-amber-400 transition-colors">
                                    <i data-lucide="instagram" class="w-6 h-6"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-[#1C2056] group-hover:text-amber-400 transition-colors">' . $member['name'] . '</h3>
                        <p class="text-gray-600 mt-2">' . $member['role'] . '</p>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-gradient-to-r from-[#1C2056] to-[#2d317a] text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('../asset/pattern.svg')] opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="max-w-3xl mx-auto opacity-0 animate-fadeInUp">
            <h2 class="text-3xl font-bold mb-6">Ready to Start Your Journey?</h2>
            <p class="text-xl text-gray-200 mb-8">Join PolinemaCareer today and take the first step towards your dream career</p>
            <a href="/auth/register" 
               class="inline-block px-8 py-3 bg-amber-400 text-[#1C2056] font-semibold rounded-lg hover:bg-amber-300 transform hover:scale-105 transition-all duration-300 shadow-lg">
                Get Started
            </a>
        </div>
    </div>
</section>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

<?php include '../include/footer.php'; ?>