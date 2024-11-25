<?php
include '../include/header.php';
// Privacy Policy Content in PHP
$sections = [
    [
        'title' => 'Introduction',
        'paragraphs' => [
            'PolinemaCareer is a job-seeking platform created exclusively for Politeknik Negeri Malang students and alumni. This Privacy Policy outlines how we handle your personal information when you use our platform to find or post jobs.',
            'By using PolinemaCareer, you agree to the terms of this Privacy Policy. If you do not agree, please refrain from using the platform.'
        ]
    ],
    [
        'title' => 'Information We Collect',
        'paragraphs' => [
            'To provide our services effectively, we collect certain information from users, including:'
        ],
        'listItems' => [
            'Personal details such as your full name, student ID (if applicable), email address, and phone number.',
            'Educational background and career-related information, such as your major, graduation year, resume, or portfolio links.',
            'Technical information, including your IP address, browser type, and device details.',
            'Activity data, such as job applications, saved jobs, and profile visits.',
            'Information provided by companies posting job listings on our platform.'
        ]
    ],
    [
        'title' => 'How We Use Your Information',
        'paragraphs' => ['The information we collect is used to:'],
        'listItems' => [
            'Facilitate job matching and improve your experience on the platform.',
            'Allow companies to view your profile and contact you for job opportunities.',
            'Verify the authenticity of student or alumni accounts.',
            'Provide career guidance and personalized recommendations.',
            'Ensure the security and functionality of the platform.',
            'Send notifications about job openings, events, or updates relevant to your profile.'
        ]
    ],
    [
        'title' => 'Cookies and Tracking Technologies',
        'paragraphs' => [
            'PolinemaCareer uses cookies and similar technologies to improve your user experience. Cookies allow us to remember your preferences, track your activity, and provide relevant job suggestions.',
            'You can manage cookies through your browser settings, but disabling them may limit some features of the platform.'
        ]
    ],
    [
        'title' => 'Data Sharing and Disclosure',
        'paragraphs' => [
            'Your personal information is only shared in the following circumstances:'
        ],
        'listItems' => [
            'With employers or recruiters who post job openings on PolinemaCareer, allowing them to view your profile and contact you for job opportunities.',
            'With service providers who assist in operating and maintaining the platform, such as hosting and email communication services. These providers are bound by confidentiality agreements.',
            'When required by law, such as in response to legal requests or to protect the rights and safety of PolinemaCareer, its users, or others.'
        ]
    ],
    [
        'title' => 'Data Security',
        'paragraphs' => [
            'We prioritize the security of your personal data. PolinemaCareer implements robust measures to protect your information from unauthorized access or misuse.',
            'However, no system is completely secure. We encourage you to protect your account by using a strong password and avoiding sharing login credentials.'
        ]
    ],
    [
        'title' => 'Your Rights and Choices',
        'paragraphs' => [
            'As a user of PolinemaCareer, you have rights regarding your personal information:'
        ],
        'listItems' => [
            'Access your personal data and review the information we hold about you.',
            'Request corrections to inaccuracies in your profile information.',
            'Delete your account and associated data, subject to legal obligations.',
            'Opt out of receiving promotional emails or notifications.'
        ]
    ],
    [
        'title' => 'Retention of Data',
        'paragraphs' => [
            'Your data is retained as long as your account is active or as required to fulfill our services. Once your account is deleted, we will remove your data unless retention is required for legal or operational purposes.'
        ]
    ],
    [
        'title' => 'Changes to This Privacy Policy',
        'paragraphs' => [
            'PolinemaCareer may update this Privacy Policy from time to time. Any updates will be posted on this page with the revised date. Please review it periodically to stay informed about how we protect your information.'
        ]
    ],
    [
        'title' => 'Contact Us',
        'paragraphs' => [
            'For questions or concerns about this Privacy Policy or how your data is handled, please contact us at:'
        ]
    ]
];

// Function to render the sections dynamically
function renderSection($section)
{
    echo '<div class="mb-8">';
    echo '<h2 class="text-xl font-semibold text-gray-900 mb-4">' . htmlspecialchars($section['title']) . '</h2>';

    // Render paragraphs
    foreach ($section['paragraphs'] as $paragraph) {
        echo '<p class="text-gray-700 mb-4 leading-relaxed">' . htmlspecialchars($paragraph) . '</p>';
    }

    // Render list items if available
    if (isset($section['listItems'])) {
        echo '<ul class="list-disc pl-6 text-gray-700 space-y-2">';
        foreach ($section['listItems'] as $item) {
            echo '<li>' . htmlspecialchars($item) . '</li>';
        }
        echo '</ul>';
    }

    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<br><br><br>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-20">
    <div class="max-w-5xl mx-auto px-6">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-semibold text-gray-900 mb-4">Privacy Policy</h1>
            <p class="text-lg text-justify text-gray-600 leading-relaxed">
                Welcome to PolinemaCareer, your privacy is important to us. This Privacy Policy explains how we collect,
                use, and safeguard your personal information while helping you connect with career opportunities.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <?php foreach ($sections as $section): ?>
                <?php renderSection($section); ?>
            <?php endforeach; ?>

            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                <p class="text-gray-700">
                    <strong>Email :</strong> contact@polinemacarrier.com
                    <br />
                    <strong>Phone Number :</strong> 081235305531
                </p>
            </div>
        </div>
    </div>
</body>
<br>

</html>
<?php include '../include/footer.php'; ?>