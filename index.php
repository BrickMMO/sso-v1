<?php

include('includes/connect.php');

define('PAGE_TITLE', 'Login');

include('includes/header.php');

include('includes/session.php');

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

// Database Connection and User Authentication
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

    // Proceed with authentication if no validation errors
    if (empty($errors)) {
        // Hash the password (assuming your password in database is hashed)
        $password = md5($password);

        // Query to fetch user details
        $query = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Authentication successful, fetch user data
            $user = $result->fetch_assoc();

            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['github'] = $user['github'];
            $_SESSION['avatar'] = $user['avatar'];

            // Redirect to dashboard or any authenticated page
            header("Location: ./pages/dashboard.php");
            exit();
        } else {
            // Authentication failed
            $errors[] = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>

<h1> SSO For BrickMMO Login </h1>
<p> Welcome to the BrickMMO project management application. </p>

<form action="" method="POST">
    <input type="hidden" name="submit" value="true">
    <label>
        Email:
        <input type="email" name="email" placeholder="Enter your email address">
        <br>
    </label>

    <label>
        Password:
        <input type="password" name="password" placeholder="Enter your password">
        <br>
    </label>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div style='color: red;'>$error</div>";
        }
    }
    ?>

    <input type="submit" value="Login">
</form>

<hr>

<div class="right">
    <a href="./pages/forgotpassword.php">Forgot Password?</a> | <a href="./pages/signup.php"> Sign Up</a>
</div>