<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../../css/style.css?v=<?php echo filemtime('../../css/style.css'); ?>">
    <link rel="stylesheet" href="../../css/snowflake.css?v=<?php echo filemtime('../../css/snowflake.css'); ?>">
    <script src="../../css/snowflake.js?v=<?php echo filemtime('../../css/snowflake.js'); ?>"></script>
</head>
<body>
    <?php include '../../common/navbar.php'; ?>
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
    <p><a href="../../index.php">Back to Home</a></p>
    <div class="snowflake-container"></div>
</body>
</html>

<?php
require '../../config/config.php'; // 包含您的資料庫配置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input data
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Generate verification token
    $verificationToken = bin2hex(random_bytes(50));

    // Insert user into database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, verification_token, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $role = 'user'; // 預設角色
        $stmt->execute([$username, $email, $hashedPassword, $role, $verificationToken]);

        // Send verification email
        $subject = "Email Verification";
        $verificationLink = "https://website-b40a0606.hgp.qtb.mybluehost.me/pages/members/verify_email.php?token=" . $verificationToken;
        $message = "Click the link below to verify your email address:<br><br><a href=\"$verificationLink\">Verify Email</a>";

        // Send email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: hgpqtbmy@hgp.qtb.mybluehost.me" . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "Verification email sent successfully. Please check your inbox.";
        } else {
            echo "Error sending verification email.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
