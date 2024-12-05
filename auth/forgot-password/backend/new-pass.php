<?php
header('Content-Type: application/json');
include '../../../include/koneksi.php';

// Menonaktifkan tampilan warning dan error
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    $token = $data['token'] ?? null;
    $password = $data['password'] ?? null;

    if (!$token || !$password) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Token and password are required.']);
        exit;
    }

    // Query untuk mendapatkan informasi reset berdasarkan token
    $resetRequestStmt = sqlsrv_prepare($conn, "SELECT * FROM password_resets WHERE token = ?", [$token]);
    if (!sqlsrv_execute($resetRequestStmt) || !($resetRequest = sqlsrv_fetch_array($resetRequestStmt, SQLSRV_FETCH_ASSOC))) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or expired token.']);
        exit;
    }

    // Cek apakah token telah kadaluwarsa
    if (time() > $resetRequest['expires_at']->getTimestamp()) {
        http_response_code(400);
        echo json_encode(['error' => 'Token has expired.']);
        exit;
    }

    // Ambil email dari token reset
    $email = $resetRequest['user_email'];
    if (!$email) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid token data.']);
        exit;
    }

    // Ambil username dari tabel pelamar berdasarkan email
    $userStmt = sqlsrv_prepare($conn, "SELECT User_username FROM pelamar WHERE email = ?", [$email]);
    if (!sqlsrv_execute($userStmt) || !($user = sqlsrv_fetch_array($userStmt, SQLSRV_FETCH_ASSOC))) {
        http_response_code(400);
        echo json_encode(['error' => 'User not found.']);
        exit;
    }

    // Update password ke database (hash dengan MD5)
    $hashedPassword = md5($password);
    $updateStmt = sqlsrv_prepare($conn, "UPDATE [user] SET password = ? WHERE username = ?", [$hashedPassword, $user['User_username']]);
    if (!sqlsrv_execute($updateStmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error while updating password.']);
        exit;
    }

    // Hapus token setelah password berhasil diperbarui
    $deleteTokenStmt = sqlsrv_prepare($conn, "DELETE FROM password_resets WHERE token = ?", [$token]);
    if (!sqlsrv_execute($deleteTokenStmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to clean up reset token.']);
        exit;
    }

    // Berikan respons berhasil
    http_response_code(200);
    echo json_encode(['message' => 'Password reset successfully.']);
    exit;
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}
?>