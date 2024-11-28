<?php
// Include header file for the website's header
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

<!-- Inline Tailwind CSS and custom CSS for Animations -->
<style>
    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 1s ease-in-out forwards;
    }

    .animate-fadeInDelay {
        animation: fadeIn 1s ease-in-out forwards;
        animation-delay: 0.3s;
    }
</style>

<!-- About Us Section -->
<section class="bg-white py-20 px-4 lg:px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
        <!-- Left side: About Us Image -->
        <div class="w-full md:w-1/2">
            <img src="../asset/about-us.jpg" alt="Polinema Career" class="w-full h-auto rounded-lg shadow-lg opacity-0 animate-fadeInDelay">
        </div> 

        <!-- Right side: About Us Text -->
        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <h2 class="text-4xl font-bold text-[#1C2056] mb-6 opacity-0 animate-fadeIn">About Us</h2>
            <p class="text-lg text-[#627785] mb-12 max-w-3xl mx-auto opacity-0 animate-fadeInDelay">
                Polinema Career is here to simplify the job and internship search process for Polinema alumni and students.
                We provide a platform that connects job seekers with companies looking for top talent, while also helping
                students and alumni prepare for the workforce. By streamlining the process, we make finding a job or internship
                more efficient and targeted, opening doors to a brighter future.
            </p>
            <a
    class="w-32 px-4 py-3 bg-[#1C2056] text-white font-semibold rounded-3xl shadow-lg hover:bg-amber-400 hover:text-indigo-900 transition text-center inline-block">
    Join Us
</a>


        </div>
        
    </div>
    <div class="flex gap-6">
    <br><br><br>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
        <!-- Left side: About Us Image -->
       

        <!-- Right side:visi misi Text -->
        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <h2 class="text-4xl font-bold text-[#1C2056] mb-6 opacity-0 animate-fadeIn">Our mission & vision</h2>
            <p class="text-lg text-[#627785] mb-12 max-w-3xl mx-auto opacity-0 animate-fadeInDelay">
            We are your digital enablement partner to accelerate your transformation journey. Whether it’s augmenting your
            existing team, leveraging our global talent centers for specialized skills, or delivering managed programs
            for your digital journey, we are here to help you succeed.
            </p>
        </div>
        <div class="w-full md:w-1/2">
            <img src="../asset/about-us.jpg" alt="Polinema Career" class="w-full h-auto rounded-lg shadow-lg opacity-0 animate-fadeInDelay">
        </div> 
    </div>

    <!-- Our Mission & Vision Section -->
    <div class="max-w-7xl mx-auto text-center mt-16">
        <h3 class="text-3xl font-semibold text-[#0d5c91] mb-8 opacity-0 animate-fadeInDelay">Reasons to Work With Us:</h3>
        <ul class="list-none space-y-4 text-lg text-[#627785] max-w-2xl mx-auto opacity-0 animate-fadeInDelay">
            <li class="flex items-start space-x-3">
                <span class="text-[#0d5c91]">✔️</span>
                <p>Weekly coaching check-ins</p>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-[#0d5c91]">✔️</span>
                <p>Optimize your candidacy for interviews</p>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-[#0d5c91]">✔️</span>
                <p>We now support videos and articles</p>
            </li>
            <li class="flex items-start space-x-3">
                <span class="text-[#0d5c91]">✔️</span>
                <p>Leveraging global talent</p>
            </li>
        </ul>

        <!-- Hero Image Section -->
        <div class="mt-12">
            <img src="../asset/about-us.jpg" alt="Hero image"
                class="w-full rounded-lg shadow-xl object-cover opacity-0 animate-fadeInDelay">
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="bg-sky-50 py-20 px-4 lg:px-8">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-[#0d5c91] mb-6 opacity-0 animate-fadeIn">What’s Our Core Value</h2>
        <p class="text-lg text-[#627785] mb-12 max-w-3xl mx-auto opacity-0 animate-fadeInDelay">
            We are passionate about delivering managed programs to help overcome the tedious tasks of risk and ensure
            excellence in every project. Our values center around integrity, excellence, and partnerships.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Core Value Cards -->
            <div
                class="p-8 bg-white rounded-lg shadow-lg transition transform hover:scale-105 hover:shadow-xl opacity-0 animate-fadeInDelay">
                <h3 class="text-2xl font-semibold text-[#0d5c91] mb-4">Integrity</h3>
                <p class="text-lg text-[#627785]">
                    Delivering managed programs across the tedious tasks of risk with utmost integrity.
                </p>
            </div>
            <div
                class="p-8 bg-white rounded-lg shadow-lg transition transform hover:scale-105 hover:shadow-xl opacity-0 animate-fadeInDelay">
                <h3 class="text-2xl font-semibold text-[#0d5c91] mb-4">Excellence</h3>
                <p class="text-lg text-[#627785]">
                    We aim for excellence in every project, ensuring the best results through innovation and precision.
                </p>
            </div>
            <div
                class="p-8 bg-white rounded-lg shadow-lg transition transform hover:scale-105 hover:shadow-xl opacity-0 animate-fadeInDelay">
                <h3 class="text-2xl font-semibold text-[#0d5c91] mb-4">Partnerships</h3>
                <p class="text-lg text-[#627785]">
                    Building lasting partnerships with our clients to create sustainable growth and success.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Meet the Team Section -->
<section class="bg-white py-20 px-4 lg:px-8">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-[#0d5c91] mb-8 opacity-0 animate-fadeIn">Meet Our Team</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            <!-- Team member -->
            <?php
            // List of team members
            $team_members = [
                ['name' => 'Pramudya Surya', 'role' => 'CEO & Founder', 'img' => '../asset/kera.jpg'],
                ['name' => 'Giovano Alkandri', 'role' => 'COO', 'img' => '../asset/kera.jpg'],
                ['name' => 'Atabik M', 'role' => 'Head of Marketing', 'img' => '../asset/kera.jpg'],
                ['name' => 'Fauzie Ikhsanul', 'role' => 'Lead Developer', 'img' => '../asset/kera.jpg'],
                ['name' => 'Tiara Mera', 'role' => 'Lead Developer', 'img' => '../asset/kera.jpg'],
                ['name' => 'Zannur', 'role' => 'Lead Developer', 'img' => '../asset/kera.jpg']
            ];

            // Loop through the team members and display them
            foreach ($team_members as $member) {
                echo '
                        <div class="space-y-4 text-center opacity-0 animate-fadeInDelay">
                            <div class="w-32 h-32 rounded-full overflow-hidden mx-auto transform transition duration-500 hover:scale-110">
                                <img src="' . $member['img'] . '" alt="' . $member['name'] . '" class="object-cover w-full h-full">
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">' . $member['name'] . '</h3>
                            <p class="text-gray-600">' . $member['role'] . '</p>
                        </div>';
            }
            ?>
        </div>
    </div>
</section>

<?php
// Include footer file for the website's footer
include '../include/footer.php';
?>
