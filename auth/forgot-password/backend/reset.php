<?php
header('Content-Type: application/json');
include '../../../include/koneksi.php';
require_once '../../helpers/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email address.']);
        http_response_code(response_code: 400); // Bad request
        exit;
    }

    // Cek apakah email ada di database
    $query = "SELECT * FROM pelamar WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_prepare($conn, $query, $params);

    if ($stmt === false) {
        echo json_encode(['error' => 'Database error.']);
        http_response_code(500); // Internal server error
        exit;
    }

    if (sqlsrv_execute($stmt)) {
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($user) { // Jika email terdaftar
            // Generate token
            $token = generateToken();
            // Simpan token di database
            date_default_timezone_set('Asia/Jakarta'); // Set zona waktu ke Jakarta
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valid selama 1 jam
            $query = "INSERT INTO password_resets (user_email, token, expires_at) VALUES (?, ?, ?)";
            $params = array($user['email'], $token, $expiresAt);
            $stmt = sqlsrv_prepare($conn, $query, $params);

            if ($stmt === false) {
                echo json_encode(['error' => 'Database error.']);
                http_response_code(500); // Internal server error
                exit;
            }

            if (sqlsrv_execute($stmt)) {
                // Kirim email menggunakan Brevo
                $sendEmail = sendResetEmail($email, $token);

                if ($sendEmail) {
                    echo json_encode(['message' => 'Password reset link sent to your email.']);
                    http_response_code(200); // OK
                } else {
                    echo json_encode(['error' => 'Failed to send reset email.']);
                    http_response_code(500); // Internal server error
                }
            } else {
                echo json_encode(['error' => 'Failed to save token.']);
                http_response_code(500); // Internal server error
            }
        } else {
            echo json_encode(['error' => 'Email not registered.']);
            http_response_code(404); // Not found
        }
    } else {
        echo json_encode(['error' => 'Database error.']);
        http_response_code(500); // Internal server error
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}