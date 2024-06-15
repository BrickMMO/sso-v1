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

<div class="w3-panel w3-green">
    <h3><i class="fa-solid fa-triangle-exclamation"></i> Success!</h3>
    <p>
        If there is an account associated to this email, you will receive a
        link with reset instructions.
    </p>
</div>

<div>
    <form action="" method="POST" onsubmit="return validateForgotForm();" novalidate>
        <input class="w3-input" type="email" id="email" name="email" autocomplete="off" value="<?php echo htmlspecialchars($email); ?>" />
        <label for="email" class="w3-text-gray">
            <i class="fa-solid fa-envelope"></i> Email
            <span id="email-error" class="w3-text-red"></span>
        </label>

        <input class="w3-input" type="password" name="password" id="password" autocomplete="off" />
        <label for="password" class="w3-text-gray">
            <i class="fa-solid fa-lock"></i>
            New Password <span id="password-error" class="w3-text-red"></span>
        </label>

        <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" name="submit">
            <i class="fa-solid fa-question"></i>
            Reset Password
        </button>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div style='color: red;'>$error</div>";
            }
        }
        ?>
    </form>
</div>

<div class="w3-container w3-center w3-margin">
    <button onclick="location.href='../';" class="w3-button w3-grey w3-text-white">
        <i class="fa-solid fa-caret-left"></i>
        Back to Login
    </button>
</div>

<script>
    function validateForgotForm() {
        let errors = 0;

        let email_pattern = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
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

<!-- Need Email Verification -->