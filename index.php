<?php
session_start();

// Load the XML file
$xmlFile = 'users.xml';
if (!file_exists($xmlFile)) {
    die("User data file not found.");
}

$xml = simplexml_load_file($xmlFile);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Use plain text passwords as in XML

    foreach ($xml->user as $user) {
        if ((string)$user->username === $username && (string)$user->password === $password) {
            $_SESSION['username'] = $username;
            header("Location: Home.php");
            exit();
        }
    }

    $error = "Invalid credentials";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('Images/Background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 50px 50px 50px 50px;  /* more padding for bigger form */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;  /* wider width */
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px;  /* slightly bigger */
            margin-bottom: 20px; /* more space between inputs */
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 18px;  /* bigger font */
        }

        .password-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        #togglePassword {
            position: absolute;
            top: 36%;
            right: 5px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            color: #357ABD;
            user-select: none;
        }

        #loginBtn {
            width: 100%;
            padding: 14px;
            background-color: rgb(76, 107, 175);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #loginBtn:hover {
            background-color: #357ABD;
        }

        #signupLink {
            display: inline-block;
            color: #357ABD;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s ease, color 0.3s ease, text-shadow 0.3s ease;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        p.signup-text {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required>

            <div class="password-wrapper">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="button" id="togglePassword">Show</button>
            </div>

            <input type="submit" id="loginBtn" value="Login">
            <p class="signup-text">No account? <a href="signup.php" id="signupLink">Sign up here</a></p>
        </form>
    </div>

    <script>
        // Hover effect for login button
        const loginBtn = document.getElementById('loginBtn');
        loginBtn.addEventListener('mouseenter', () => {
            loginBtn.style.transform = 'scale(1.05)';
            loginBtn.style.boxShadow = '0 4px 10px rgba(53, 122, 189, 0.5)';
        });
        loginBtn.addEventListener('mouseleave', () => {
            loginBtn.style.transform = 'scale(1)';
            loginBtn.style.boxShadow = 'none';
        });

        // Hover effect for signup link
        const signupLink = document.getElementById('signupLink');
        signupLink.addEventListener('mouseenter', () => {
            signupLink.style.transform = 'scale(1.1)';
            signupLink.style.color = '#1a4f9c';
            signupLink.style.textShadow = '1px 1px 2px rgba(0,0,0,0.2)';
        });
        signupLink.addEventListener('mouseleave', () => {
            signupLink.style.transform = 'scale(1)';
            signupLink.style.color = '#357ABD';
            signupLink.style.textShadow = 'none';
        });

        // Show/hide password toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "Hide";
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "Show";
            }
        });
    </script>
</body>
</html>
