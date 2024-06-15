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

<div>
    <form action="" method="POST" onsubmit="return validateLoginForm();" novalidate>
        <div class="w3-margin-bottom">
            <input class="w3-input" type="email" id="email" name="email" autocomplete="off" />
            <label for="email" class="w3-text-gray">
                <i class="fa-solid fa-envelope"></i> Email
                <span id="email-error" class="w3-text-red"></span>
            </label>
        </div>

        <div class="w3-margin-bottom">
            <input class="w3-input" type="password" name="password" id="password" autocomplete="off" />
            <label for="password" class="w3-text-gray">
                <i class="fa-solid fa-lock"></i> Password
                <span id="password-error" class="w3-text-red"></span>
            </label>
        </div>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div style='color: red;'>$error</div>";
            }
        }
        ?>

        <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-bottom" name="submit">
            <i class="fa-solid fa-right-to-bracket"></i>
            Login
        </button>
    </form>
</div>

<div class="w3-center">
    <button onclick="location.href='./pages/forgotpassword.php';" class="w3-button w3-grey w3-text-white">
        <i class="fa-solid fa-question"></i>
        Forgot Password
    </button>
    <button onclick="location.href='./pages/register.php';" class="w3-button w3-grey w3-text-white">
        <i class="fa-solid fa-pen"></i>
        Create Account
    </button>
</div>

<!-- Client-side validation -->
<script>
    function validateLoginForm() {
        let errors = 0;

        let email_pattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(email_pattern)) {
            email_error.innerHTML = "(email is invalid)";
            errors++;
        }

        let password = document.getElementById("password");
        let password_error = document.getElementById("password-error");
        password_error.innerHTML = "";
        if (password.value == "") {
            password_error.innerHTML = "(password is required)";
            errors++;
        }

        if (errors) return false;
    }
</script>