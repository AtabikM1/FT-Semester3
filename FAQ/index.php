<?php
include '../include/header.php';
// Sample FAQ data (can be fetched from a database or static array)
$faqData = [
    [
        'question' => 'What is this website about?',
        'answer' => 'This website is a platform for developers to connect and share knowledge. It allows users to ask questions, share articles, and collaborate on projects.'
    ],
    [
        'question' => 'How do I create an account?',
        'answer' => 'Click on the \'Sign Up\' button in the top right corner and fill in the necessary details such as email, username, and password.'
    ],
    [
        'question' => 'How do I reset my password?',
        'answer' => 'Go to the login page and click on \'Forgot Password\'. You\'ll receive an email with instructions to reset your password.'
    ],
    [
        'question' => 'How can I contact support?',
        'answer' => 'You can contact support by emailing us at support@example.com or through the contact form on the support page.'
    ],
    [
        'question' => 'What are the system requirements?',
        'answer' => 'You need a modern web browser (Chrome, Firefox, Safari) and an internet connection to use the website. No specific hardware requirements.'
    ],
    [
        'question' => 'Can I collaborate on projects with others?',
        'answer' => 'Yes! You can invite other developers to collaborate on projects, share code, and communicate through the platform\'s messaging system.'
    ],
    [
        'question' => 'Is there a mobile app available?',
        'answer' => 'Currently, there is no mobile app. However, the website is fully responsive and optimized for mobile browsing.'
    ],
    [
        'question' => 'How do I delete my account?',
        'answer' => 'If you wish to delete your account, please contact our support team via email and we will assist you in the process.'
    ],
    [
        'question' => 'Is the website free to use?',
        'answer' => 'Yes, the website is completely free to use. We offer optional premium features for advanced functionality.'
    ],
    [
        'question' => 'How can I contribute to the website?',
        'answer' => 'You can contribute by writing articles, answering questions, or providing suggestions through our feedback form.'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<br><br>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // JavaScript to handle accordion behavior without reloading page
        function toggleAccordion(index) {
            const content = document.getElementById('faq-content-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            const isOpen = content.style.display === 'block';

            // Close all other FAQ answers
            document.querySelectorAll('.faq-content').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.faq-icon').forEach(el => el.classList.remove('rotate-180'));

            if (!isOpen) {
                content.style.display = 'block';
                icon.classList.add('rotate-180');
            } else {
                content.style.display = 'none';
            }
        }
    </script>
</head>

<body class="bg-gray-50 pt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- FAQ Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">Frequently Asked Questions</h1>
            <p class="text-lg text-gray-600">Here are some of the most commonly asked questions. If you can't find what
                you're looking for, feel free to contact us!</p>
        </div>

        <!-- FAQ Content -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="space-y-6">
                <?php foreach ($faqData as $index => $faq): ?>
                    <div class="border-b last:border-b-0">
                        <div class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-100 rounded-lg"
                            onclick="toggleAccordion(<?php echo $index; ?>)">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <?php echo htmlspecialchars($faq['question']); ?>
                            </h3>
                            <svg id="faq-icon-<?php echo $index; ?>"
                                class="w-6 h-6 text-gray-600 transition-transform duration-300 faq-icon" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12l-6 6m0-6l6-6" />
                            </svg>
                        </div>
                        <div id="faq-content-<?php echo $index; ?>"
                            class="faq-content p-4 text-gray-700 bg-gray-50 rounded-lg" style="display: none;">
                            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

</html>
<?php include '../include/footer.php'; ?>