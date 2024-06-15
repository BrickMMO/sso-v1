<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'Forgot Password');

include('../includes/header.php');

require '../vendor/autoload.php'; // Ensure SendGrid is autoloaded

$errors = [];
$email = $password = '';

// Server-side validation functions
function validateEmail($email)
{
    // Basic email validation
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Validate inputs
    $errors = [];
    if (!validateEmail($email)) {
        $errors[] = "Invalid email format.";
    }

    // If no validation errors
    if (empty($errors)) {
        // Check if email exists
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            // Generate reset token
            $reset_token = bin2hex(random_bytes(16));
            $reset_token_expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token in the database
            $query = "UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('ssi', $reset_token, $reset_token_expires_at, $user_id);
            $stmt->execute();

            // Send verification email using SendGrid
            $sendgrid = new \SendGrid('Secret Key Of Send Grid');
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("maysonchris025@gmail.com", "BrickMMO");
            $email->setSubject("Password Reset Request");
            $email->addTo($email, "User");
            $email->addContent(
                "text/plain",
                "Click the link below to reset your password:\n\n" .
                    "http://yourdomain.com/resetpassword.php?token=$reset_token"
            );

            try {
                $response = $sendgrid->send($email);
                echo 'If there is an account associated to this email, you will receive a link with reset instructions.';
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage();
            }
        } else {
            echo 'If there is an account associated to this email, you will receive a link with reset instructions.';
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

        if (errors) return false;
    }
</script>