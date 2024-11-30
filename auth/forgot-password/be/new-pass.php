<?php
header('Content-Type: application/json');
include '../../../include/koneksi.php';
require_once '../../helpers/helpers.php';

// Tambahkan ini di awal file PHP untuk menonaktifkan tampilan warning dan error
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode JSON payload
    $data = json_decode(file_get_contents('php://input'), true);

    $token = $data['token'] ?? null;
    $password = $data['password'] ?? null;

    // echo $token . " front\n";
    // echo $password . "\n";

    // Return tabel password_resets
    $resetRequestStmt = sqlsrv_prepare($conn, "SELECT * FROM password_resets WHERE token = ?", [$token]);
    if (!sqlsrv_execute($resetRequestStmt)) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error while fetching reset request.']);
        exit;
    }
    $resetRequest = sqlsrv_fetch_array($resetRequestStmt, SQLSRV_FETCH_ASSOC);

    // debug return reset request aman gak? amann
    // echo json_encode($resetRequest);
    // http_response_code(400);
    // exit;

    // Cek validasi token ada dan valid atau tidak
    if ($resetRequest['token'] === null  || $resetRequest['token'] !== $token) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid or expired tokenn.']);
        exit;
    } else {
        // debug token masuk gak? 
        // $timestampini = strtotime($resetRequest['expires_at']['date']);
        // echo json_encode($timestampini);
        // http_response_code(400);
        //debug time huwauhwuhwau
        // echo "Current time: " . time() . "\n";
        // echo "Expires at: " .  $resetRequest['expires_at']->getTimestamp() . "\n";
        // exit;
        //buat kondisi token expired
        if (time() < $resetRequest['expires_at']->getTimestamp()) {
            // Return email dari token
            $email = $resetRequest['user_email'];
            // debug email masuk gak? 
            // echo json_encode($email);
            // http_response_code(400);
            // exit;
            if ($email) {
                // Return username dari email
                $ReturnUsernameFromEmailStmt = sqlsrv_prepare($conn, "SELECT User_username FROM pelamar WHERE email = ?", [$email]);
                if (!sqlsrv_execute($ReturnUsernameFromEmailStmt)) {
                    echo json_encode(['error' => 'Database error while fetching username.']);
                    http_response_code(500);
                    exit;
                }
                $ReturnUsernameFromEmail = sqlsrv_fetch_array($ReturnUsernameFromEmailStmt, SQLSRV_FETCH_ASSOC);
                // debug return username aman gak? amann
                // echo json_encode ($ReturnUsernameFromEmail);
                // http_response_code(400);
                // exit;

                //update password
                $updatePasswordStmt = sqlsrv_prepare($conn, "UPDATE [user] set password = ? where [user].username = ?", [&$password, $ReturnUsernameFromEmail['User_username']]);

                //debug cek isian
                // echo json_encode($updatePasswordStmt);
                // http_response_code(400);
                // exit;

                if (!sqlsrv_execute($updatePasswordStmt)) {
                    echo json_encode(['error' => 'Database error while updating password.']);
                    http_response_code(500);
                    exit;
                }

                //delete token jika update password berhasil
                if ($updatePasswordStmt) {
                    //delete token jike berhasil upadte password
                    $deleteTokenStmt = sqlsrv_prepare($conn, "DELETE FROM [password_resets] WHERE token = ?", [$token]);
                    if (sqlsrv_execute($deleteTokenStmt)) {
                        $deleteToken = sqlsrv_fetch_array($deleteTokenStmt, SQLSRV_FETCH_ASSOC);
                        echo json_encode(['message' => 'Password reset successfully. You can now log in.']);
                        http_response_code(200); // OK
                        exit;
                    }
                }
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid or expired token.']);
            exit;
        }
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}
