<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'My Account');

include('../includes/header.php');

$errors = [];
$email = $password = '';

// Server-side validation functions
function validateEmail($email)
{
    // Basic email validation
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password)
{
    // Basic validation for password (can be extended as per your requirements)
    return !empty($password); // Just checking if password is not empty
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    $errors = [];
    if (!validateEmail($email)) {
        $errors[] = "Invalid email format.";
    }
    if (!validatePassword($password)) {
        $errors[] = "Password is required.";
    }

    // Update password if no validation errors
    if (empty($errors)) {
        // Hash the password (you should use a secure hashing method in your application)
        $hashed_password = md5($password); // Example using MD5 (not recommended for production)

        // Update password in database
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('ss', $hashed_password, $email);
        if ($stmt->execute()) {
            // Password updated successfully
            echo "<script>alert('Password updated successfully. Please login with your new password.');</script>";
            echo "<script>window.location.href = '../index.php';</script>";
            exit();
        } else {
            // Error updating password
            $errors[] = "Error updating password: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<h1> Forgot Password </h1>

<form action="" method="POST">
    <input type="hidden" name="submit" value="true">

    <label>
        Email:
        <input type="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>">
        <br>
    </label>

    <label>
        New Password:
        <input type="password" name="password" placeholder="Enter your new password">
        <br>
    </label>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div style='color: red;'>$error</div>";
        }
    }
    ?>

    <input type="submit" value="Reset Password">
</form>

<hr>

<div class="right">
    <a href="../index.php"> Back to Login</a>
</div>