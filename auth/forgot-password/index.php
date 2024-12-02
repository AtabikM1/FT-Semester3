<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        .forgot-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .forgot-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .forgot-container input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .forgot-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .forgot-container button:hover {
            background-color: #0056b3;
        }

        .forgot-container p {
            font-size: 14px;
            color: #666;
        }

        .forgot-container a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="forgot-container">
        <h1>Forgot Password</h1>
        <form id="forgotPasswordForm" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p id="responseMessage" style="color: green; display: none;"></p>
        <p>Remember your password? <a href="/polka/auth/login">Login</a></p>
    </div>
    <script>
        const form = document.getElementById('forgotPasswordForm');
        const responseMessage = document.getElementById('responseMessage');

        form.addEventListener('submit', async function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            const formData = new FormData(form); // Collect form data
            const email = formData.get('email'); // Get email from form

            try {
                // Send form data to the server using fetch
                const response = await fetch('http://project.test/auth/forgot-password/backend/reset.php', {
                    method: 'POST',
                    body: formData
                });

                // Parse response from server
                const result = await response.json();

                if (response.ok) {
                    responseMessage.style.color = 'green';
                    responseMessage.textContent = result.message || "Reset link sent to your email.";
                } else {
                    responseMessage.style.color = 'red';
                    responseMessage.textContent = result.error || "Something went wrong.";
                }
                responseMessage.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                responseMessage.style.color = 'red';
                responseMessage.textContent = 'Unable to process your request.';
                responseMessage.style.display = 'block';
            }
        });
    </script>
</body>

</html>