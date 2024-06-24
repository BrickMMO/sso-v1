<?php

if(!isset($_GET['hash']) or !user_fetch($_GET['hash']))
{
    message_set('Hash Error', 'There was an error with the password reset link, please try again.', 'red');
    header_redirect('/forgot');
}
else
{

    $user = user_fetch($_GET['hash']);
    
    $query = 'UPDATE users SET
        email_verified_at = NOW()
        WHERE verify_hash = "'.addslashes($_GET['hash']).'"
        AND email_verified_at IS NULL
        LIMIT 1';
    mysqli_query($connect, $query);
    
}

define('PAGE_TITLE', 'Verify Email Address');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<div class="w3-panel w3-green">
    <h3><i class="fa-solid fa-triangle-exclamation"></i> Email Verified</h3>
    <p>Your email address has been verified.</p>
</div>

<div class="w3-container w3-center w3-margin">
    <button
        onclick="location.href='/login';"
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
