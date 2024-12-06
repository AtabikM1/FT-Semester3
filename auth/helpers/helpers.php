<?php

include '../../../include/koneksi.php';

/**
 * Sanitize user input to prevent XSS attacks.
 */
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a secure random token for password resets.
 */
function generateToken($length = 32)
{
    return bin2hex(random_bytes($length / 2)); // Generates a hexadecimal token
}

/**
 * Validate if a token is still valid based on its expiration time.
 */
function isTokenExpired($expiresAt)
{
    return strtotime($expiresAt) < time(); // Returns true if expired
}

//fetch single row
function fetchSingleRow($conn, $query, $params = [])
{
    $stmt = sqlsrv_prepare($conn, $query, $params);

    if (!$stmt || !sqlsrv_execute($stmt)) {
        // Log error jika diperlukan
        error_log(print_r(sqlsrv_errors(), true));
        // return null; // Mengembalikan null jika terjadi kesalahan
    }

    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}




/**
 * Send an email (example implementation; replace with your email logic).
 */

function sendResetEmail($email, $token)
{
    $apiKey = ''; // Ganti dengan API key Anda
    $url = 'https://api.brevo.com/v3/smtp/email'; // Ganti dengan URL API email Anda

    $resetLink = "http://project.test/auth/forgot-password/new-pass.php?token=$token"; // Ganti dengan URL reset password Anda

    $data = [
        'sender' => ['Administrator' => 'Polinema Career', 'email' => 'mutawakilatabik@gmail.com'],
        'to' => [['email' => $email]],
        'subject' => 'Password Reset Request',
        'htmlContent' => "<html><body><p>Klik <a href='$resetLink'>disini</a> untuk mereset password anda.</p></body></html>"
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n" .
                "api-key: $apiKey\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result !== false;
}