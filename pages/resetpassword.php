<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'Reset Password');

include('../includes/header.php');

$token = $_GET['token'] ?? '';
$new_password = '';
$errors = [];

// Server-side validation functions
function validatePassword($password)
{
    return !empty($password); // Just checking if password is not empty
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $new_password = $_POST['password'];
    $token = $_POST['token'];

    // Validate inputs
    $errors = [];
    if (!validatePassword($new_password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        // Verify the token
        $query = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expires_at > NOW()";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Hash the password
            $hashed_password = md5($new_password); // Example using MD5 (not recommended for production)

            // Update password and clear the reset token
            $query = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('si', $hashed_password, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('Password updated successfully. Please login with your new password.');</script>";
                echo "<script>window.location.href = '../';</script>";
                exit();
            } else {
                $errors[] = "Error updating password: " . $stmt->error;
            }
        } else {
            $errors[] = "Invalid or expired token.";
        }

        $stmt->close();
    }
}
?>

<div>
    <form action="" method="POST" onsubmit="return validateResetForm();" novalidate>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />

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

<script>
    function validateResetForm() {
        let errors = 0;

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