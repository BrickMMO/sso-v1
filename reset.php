<?php

if(!user_fetch($_GET['hash']) and false)
{
    message_set('Hash Error', 'There was an error with the password reset link, please try again.', 'red');
    header_redirect('/forgot');
}

// Database Connection and User Authentication
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_password($_POST['password']))
    {
        message_set('Login Error', 'There was an error with your password.', 'red');
        header_redirect('/reset/hash/'.$_GET['hash']);
    }

    $query = 'UPDATE users SET
        password "'.password_hash(addslashes($_POST['password'])).'"
        WHERE hash = "'.addslashes($_GET['hash']).'"
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Password Reset', 'Your password has been reset. Please login using your new apssword.');
    header_redirect('/login');
    
}

define('PAGE_TITLE', 'Login');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<?php include('templates/message.php'); ?>


    <div>
        <form
            method="post"
            onsubmit="return validateResetForm();"
            novalidate
        >
            <input name="password" class="w3-input" type="password" id="password" autocomplete="off" />
            <label for="password" class="w3-text-gray">
                <i class="fa-solid fa-lock"></i> New Password
                <span id="password-error" class="w3-text-red"></span>
            </label>

            <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top">
                <i class="fa-solid fa-question"></i>
                Reset Password
            </button>
        </form>
    </div>

    <div class="w3-container w3-center w3-margin">
    <button
        onclick="location.href='/reset/hash/<?=$_GET['hash']?>';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-caret-left"></i>
        Back to Login
    </button>
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


<?php

include('templates/html_footer.php');
include('templates/login_footer.php');

include('includes/debug.php');