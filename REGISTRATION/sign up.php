<?php
include_once "dbs_connect.php";

$valid = true;
$errorMessages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];

    // Validation patterns
    if (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
        $valid = false;
        $errorMessages[] = "Please provide a valid email address.";
    }

    if (!preg_match("/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,15}$/", $username)) {
        $valid = false;
        $errorMessages[] = "Username must be 8-15 characters, contain 1 uppercase letter, and 1 number.";
    }

    if (!preg_match("/^(?=.*\d)[A-Za-z\d]{8,12}$/", $password)) {
        $valid = false;
        $errorMessages[] = "Password must be 8-12 characters and contain at least 1 number.";
    }

    if ($password !== $confirm_password) {
        $valid = false;
        $errorMessages[] = "Passwords do not match.";
    }

    if (!preg_match("/^[A-Z][a-zA-Z\s]*$/", $fullname)) {
        $valid = false;
        $errorMessages[] = "Full name must start with a capital letter and contain only letters & spaces.";
    }

    if (!preg_match("/^\d{11}$/", $phone)) {
        $valid = false;
        $errorMessages[] = "Phone number must be exactly 11 digits.";
    }

    if ($valid) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $username, $hashed_password, $phone);

        if ($stmt->execute()) {
            header("Location: success.php");
            exit();
        } else {
            $errorMessages[] = "Error inserting data: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style5.css">
</head>
<body>

<div class="signup-container">
    <h1>Sign Up</h1>

    <?php if (!empty($errorMessages)): ?>
        <div class="error-box">
            <ul>
                <?php foreach ($errorMessages as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="fullname" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" required>

        <button type="submit">Sign Up</button>
        <p>Already have an account? <a href="log in.php" class="signup-link">Log in</a></p>
    </form>
</div>

</body>
</html>
