<?php

include('includes/connect.php');
include('includes/session.php');
include('functions/functions.php');

require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

$errors = [];
$email = $password = '';

// Database Connection and User Authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

    // Basic serverside validation
    if (!validateEmail($email) || !validatePassword($password)) 
    {
        redirect('login.php');
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

            // Generate JWT token
            $secret_key = "your secret key"; // secure random string
            $issuer_claim = "sso-site-name.com"; // Issuer of the token
            $audience_claim = "brickMMO-sub-site.com"; // Audience of the token
            $issuedat_claim = time(); // Issued at (current timestamp)
            $expire_claim = $issuedat_claim + 3600; // Token expiration time (1 hour)

            $token = array(
                "iss" => $issuer_claim,
                "aud" => $audience_claim,
                "iat" => $issuedat_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "user_id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "avatar" => $user['avatar'],
                )
            );

            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['github'] = $user['github'];
            $_SESSION['avatar'] = $user['avatar'];

            // Encode JWT and set cookie for main site
            $jwt = JWT::encode($token, $secret_key, 'HS256');
            setcookie("jwt", $jwt, $expire_claim, "/", "sso-site-name.com", true, true);

            // Determine redirect URL
            $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] . "?sub=" . urlencode($jwt) : './pages/dashboard.php';

            header("Location: $redirect_url");
            exit();

            // -- Old Version -- [Not For Sub Site only for main website]
            // Redirect to dashboard or any authenticated page
            // header("Location: ./pages/dashboard.php");
            // exit();
        } else {
            // Authentication failed
            $errors[] = "Invalid email or password.";
        }

        $stmt->close();
    }
}

define('PAGE_TITLE', 'Login');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<div>
    <form
        method="post"
        action="welcome.html"
        onsubmit="return validateLoginForm()"
        novalidate
    >
        <div class="w3-margin-bottom">
        <input
            class="w3-input"
            type="email"
            id="email"
            autocomplete="off"
        />
        <label for="email" class="w3-text-gray">
            <i class="fa-solid fa-envelope"></i> Email
            <span id="email-error" class="w3-text-red"></span>
        </label>
        </div>

        <div class="w3-margin-bottom">
        <input
            class="w3-input"
            type="password"
            id="password"
            autocomplete="off"
        />
        <label for="password" class="w3-text-gray">
            <i class="fa-solid fa-lock"></i> Password
            <span id="password-error" class="w3-text-red"></span>
        </label>
        </div>

        <button
        class="w3-block w3-btn w3-orange w3-text-white w3-margin-bottom"
        >
        <i class="fa-solid fa-right-to-bracket"></i>
        Login
        </button>
    </form>
    </div>

    <div class="w3-center">
    <button
        onclick="location.href='/forgot.html';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-question"></i>
        Forgot Password
    </button>
    <button
        onclick="location.href='/register.html';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-pen"></i>
        Create Account
    </button>
</div>

<script>
    function validateLoginForm() {
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

<?php

include('templates/html_footer.php');
include('templates/login_footer.php');