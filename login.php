<?php

use \Firebase\JWT\JWT;

if(security_is_logged_in())
{

    message_set('Already Logged In', 'You are currently logged in.');
    header_redirect(isset($_GET['url']) ? $_GET['url'] : '/dashboard');

}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_email($_POST['email']) || 
        !validate_password($_POST['password'])) 
    {
        message_set('Login Error', 'There was an error with your login informaiton.', 'red');
        header_redirect('/login');
    }

    // Query to fetch user details
    $query = 'SELECT * 
        FROM users 
        WHERE email = "'.addslashes($_POST['email']).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result) == 0)
    {
        message_set('Login Error', 'There was an error with your login informaiton.', 'red');
        header_redirect('/login');
    }

    $user = mysqli_fetch_assoc($result);

    /*
    echo 'Verify: '.password_verify($_POST['password'], $user['password']);
    echo 'Rows: '.mysqli_num_rows($result);
    die();
    */

    if (!password_verify($_POST['password'], $user['password']))
    {
        message_set('Login Error', 'There was an error with your login informaiton.', 'red');
        header_redirect('/login');
    }

    // Start session and store user data
    security_set_user_session($user['id']);

    // Set cookie
    $hash = password_hash($user['password'], PASSWORD_DEFAULT);
    setcookie('hash_id', security_encrypt($user['id']), time() + (60 * 60 * 24 * 30), '/', 'brickmmo.com');
    setcookie('hash_string', security_encrypt($user['password']), time() + (60 * 60 * 24 * 30), '/', 'brickmmo.com');

    // Determine redirect URL
    $redirect_url = isset($_GET['url']) ? $_GET['url'] : '/dashboard';

    message_set('Login Success', 'You have been logged in.');
    header_redirect($redirect_url);
    
}

define('APP_NAME', 'My Account');

define('PAGE_TITLE', 'Login');

include('templates/html_header.php');
include('templates/login_header.php');

include('templates/message.php'); 

?>

<div>
    <form
        method="post"
        onsubmit="return validateLoginForm()"
        novalidate
    >

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
        

        <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top">
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

<div class="w3-center w3-margin-top">
    <a class="w3-btn w3-white w3-text-orange w3-border" href="<?=github_url()?>">
        <i class="fa-brands fa-github"></i>
        Login using GitHub
    </a>
</div>

<div class="w3-center w3-margin-top">
    <button
        onclick="location.href='/forgot';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-question"></i>
        Forgot Password
    </button>
    <button
        onclick="location.href='/register';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-pen"></i>
        Register
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

include('templates/debug.php');

include('templates/login_footer.php');
include('templates/html_footer.php');
