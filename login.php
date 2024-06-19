<?php

use \Firebase\JWT\JWT;

include('includes/connect.php');
include('includes/session.php');
include('functions/functions.php');

require __DIR__ . '/vendor/autoload.php';

// Database Connection and User Authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_email($_POST['email']) || !validate_password($_POST['password'])) 
    {
        redirect('login.php');
    }

    // Hash the password (assuming your password in database is hashed)
    $password = md5($password);

    // Query to fetch user details
    $query = 'SELECT * 
        FROM users 
        WHERE email = "'.addslashes($_POST['email']).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result) == 0)
    {
        redirect('login.php?error');
    }

    $user = mysqli_fetch_assoc($result);

    /*
    echo 'Verify: '.password_verify($_POST['password'], $user['password']);
    echo 'Rows: '.mysqli_num_rows($result);
    */

    if (!password_verify($_POST['password'], $user['password']))
    {
        redirect('login.php?error');
    }

    // Generate JWT token
    $secret_key = 'BRICKMMO-HS256';
    $issuer_claim = 'brickmmo.com';
    $audience_claim = '*.brickmmo.com';
    $issuedat_claim = time();
    $expire_claim = $issuedat_claim + 3600 * 24 * 30;

    $token = array(
        'iss' => $issuer_claim,
        'aud' => $audience_claim,
        'iat' => $issuedat_claim,
        'exp' => $expire_claim,
        'data' => $user,
    );

    // Start session and store user data
    $_SESSION['user'] = $user;

    // Encode JWT and set cookie for main site
    $jwt = JWT::encode($token, $secret_key, 'HS256');
    setcookie('jwt', $jwt, $expire_claim, '/', 'brickmmo.com', true, true);

    // Determine redirect URL
    $redirect_url = isset($_POST['url']) ? $_POST['url'] . '?sub=' . urlencode($jwt) : '/dashboard.php';

    redirect($redirect_url);
    
}

define('PAGE_TITLE', 'Login');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<div>
    <form
        method="post"
        onsubmit="return validateLoginForm()"
        novalidate
    >

        <?php if(isset($_GET['error'])): ?>
            <div class="w3-panel w3-green">
                <h3><i class="fa-solid fa-triangle-exclamation"></i> Login Error!</h3>
                <p>
                    There was an error with your email or password.
                </p>
            </div>
        <?php endif; ?>

        <div class="w3-margin-bottom">
            <input
                name="email"
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
                name="password"
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

        <?php if(isset($_GET['url'])):?>
            <input 
                name="url"
                type="hidden"
                value="<?=$_GET['url']?>" 
            />
        <?php endif; ?>
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