<?php
session_start();

$xmlFile = 'users.xml';

// Load or create a valid XML
if (file_exists($xmlFile)) {
    libxml_use_internal_errors(true); // Suppress XML warnings
    $xml = simplexml_load_file($xmlFile);

    if ($xml === false) {
        // If the file exists but is corrupted, recreate it
        $xml = new SimpleXMLElement('<users></users>');
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($xmlFile);
    }
} else {
    // If file doesn't exist, create it
    $xml = new SimpleXMLElement('<users></users>');
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());
    $dom->save($xmlFile);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $exists = false;
    foreach ($xml->user as $user) {
        if ((string)$user->username === $username) {
            $exists = true;
            break;
        }
    }

    if ($exists) {
        $error = "Username already taken";
    } else {
        $newUser = $xml->addChild('user');
        $newUser->addChild('username', $username);
        $newUser->addChild('password', $password);

        // Format XML before saving
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());
        $dom->save($xmlFile);

        $_SESSION['username'] = $username;

        echo "<script>alert('User registered successfully!'); window.location.href='index.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
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

        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            width: 300px;
            transition: transform 0.3s ease-in-out;
        }

        .signup-container:hover {
            transform: scale(1.02);
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color:rgb(76, 107, 175);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color:#357ABD;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
