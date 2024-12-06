<?php
// Contoh cara mendapatkan token (misalnya dari URL)
$token = $_GET['token'] ?? '';

// Amankan output untuk mencegah XSS
$token = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .reset-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .reset-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .reset-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .reset-container button:hover {
            background-color: #0056b3;
        }

        .reset-container p {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <?php include "../../include/header.php"; ?>
    <div class="reset-container">
        <h1>Reset Password</h1>
        <form id="reset-password-form">
            <?php
            //print token
            // echo   $token;
            ?>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Reset Password</button>
        </form>
        <p id="responseMessage" style="color: green; display: none;"></p><br>
        <!-- <p>Need help? <a href="/support">Contact Support</a></p> -->
    </div>

    <script>
        const form = document.getElementById('reset-password-form');
        const responseMessage = document.getElementById('responseMessage');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const token = form.elements.token.value;
            const password = form.elements.password.value;
            const confirmPassword = form.elements.confirm_password.value;

            if (password !== confirmPassword) {
                responseMessage.style.display = 'block';
                responseMessage.style.color = 'red';
                responseMessage.innerText = 'Passwords do not match';
                return;
            }

            try {
                const response = await fetch('http://project.test/auth/forgot-password/backend/new-pass.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        token,
                        password
                    })
                });

                const result = await response.json();

                if (response.ok) {
                    responseMessage.style.color = 'green';
                    responseMessage.textContent = result.message || "Password reset successfully.";
                } else {
                    responseMessage.style.color = 'red';
                    responseMessage.textContent = result.error || "Something went wrong.";
                }

                responseMessage.style.display = 'block';
            } catch (error) {
                responseMessage.style.color = 'red';
                responseMessage.textContent = 'Unable to process your request.';
                responseMessage.style.display = 'block';
            }
        });
    </script>

</body>

</html>