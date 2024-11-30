<?php
// Include header jika ada
include './include/header.php'; // Misalnya header.php berisi struktur HTML awal

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['username']); // Username disimpan dalam sesi
$userRole = $isLoggedIn ? $_SESSION['Role'] : null;
?>
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
</head>

<body class="bg-gray-50 text-gray-900">
    <!-- Inline Tailwind CSS for Animations -->
    <style>
        /* Animasi untuk teks dan gambar */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slideInLeft {
            animation: slideInLeft 1s ease-out forwards;
        }

        .animate-slideInRight {
            animation: slideInRight 1s ease-out forwards;
        }
    </style>

    <!-- Welcome Section -->
    <section class="relative min-h-screen bg-[#f0f8ff] overflow-hidden">
        <div class="absolute top-20 -left-96 w-[500px] h-[500px] bg-[#4a90e2]/10 rounded-full blur-3xl opacity-80">
        </div>
        <div class="absolute bottom-20 right-20 w-[500px] h-[500px] bg-[#4a90e2]/5 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-6 lg:px-20 py-28">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Konten Teks -->
                <div class="space-y-8 animate-slideInLeft">
                    <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                        Welcome to <span class="text-[#1C2056]">PolinemaCareer</span>
                    </h1>
                    <p class="text-lg text-gray-600 max-w-xl leading-relaxed">
                        Your gateway to a brighter future. Connect with top companies and explore endless career
                        opportunities with us.
                    </p>
                    <div class="flex gap-6">
                        <a href="<?php
                        if (!$isLoggedIn) {
                            echo './auth/login'; // Belum login
                        } elseif ($userRole == '2') {
                            echo '/browse-jobs'; // Pelamar
                        } elseif ($userRole == '3') {
                            echo '/post-job'; // Perusahaan
                        }
                        ?>"
                            class="px-6 py-3 bg-[#1C2056] text-white font-semibold rounded-lg shadow-lg hover:bg-amber-400 hover:text-indigo-900 transition">
                            Get Started
                        </a>

                    </div>
                </div>

                <!-- Gambar Hero -->
                <div class="relative hidden lg:block animate-slideInRight">
                    <span class="absolute inset-0 bg-[#1C2056]/10 rounded-2xl blur-3xl"></span>
                    <span
                        class="absolute top-1/2 -translate-y-1/2 right-10 w-[70%] h-[130%] bg-[#1C2056] rounded-2xl"></span>
                    <img src="asset/about-us.jpg" alt="Hero Image"
                        class="rounded-2xl shadow-lg border-4 border-[#1C2056] relative z-10" />
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-6 lg:px-20">
            <h2 class="text-4xl font-bold text-center text-slate-900 mb-12">
                Why Choose <span class="text-[#1C2056]">PolinemaCareer?</span>
            </h2>
            <div class="grid md:grid-cols-3 gap-10">
                <?php
                $features = [
                    ["icon" => "network.png", "title" => "Large Network", "desc" => "Connect with 500+ companies worldwide."],
                    ["icon" => "boost.webp", "title" => "Career Boost", "desc" => "Get resources to enhance your professional journey."],
                    ["icon" => "trusted.png", "title" => "Trusted Platform", "desc" => "Your security and success are our priorities."],
                ];
                foreach ($features as $feature) {
                    echo '<div class="p-6 bg-[#f0f8ff] rounded-lg shadow-lg hover:shadow-xl transition">';
                    echo '<div class="flex items-center gap-4 mb-4">';
                    echo '<img src="asset/' . $feature['icon'] . '" alt="' . $feature['title'] . '" class="w-12 h-12">';
                    echo '<h3 class="text-xl font-bold text-slate-900">' . $feature['title'] . '</h3>';
                    echo '</div>';
                    echo '<p class="text-gray-600">' . $feature['desc'] . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <?php
    // Include footer jika ada
    include 'include/footer.php';
    ?>

    <script>// Tunggu hingga halaman selesai dimuat
        window.addEventListener("load", function () {
            // Cari semua link (anchor) yang mengarah ke halaman lain
            const links = document.querySelectorAll('a[href], button[data-href]'); // Menambahkan button dengan data-href

            links.forEach(function (link) {
                link.addEventListener("click", function (event) {
                    // Cegah aksi default link (pindah halaman langsung)
                    event.preventDefault();

                    // Tambahkan kelas slide-out untuk transisi ke kiri
                    document.querySelector('body').classList.add('slide-out');

                    // Tunggu durasi transisi selesai (500ms) sebelum arahkan ke halaman tujuan
                    setTimeout(function () {
                        // Jika link adalah anchor, pindah ke href, jika button, ambil data-href
                        const target = link.getAttribute('href') || link.getAttribute('data-href');
                        window.location.href = target; // Arahkan ke halaman yang dituju
                    }, 500); // Durasi animasi slide-out
                });
            });
        });
    </script>
</body>

</html>