<?php

// Database Connection and User Authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_email($_POST['email']))
    {
        message_set('Login Error', 'There was an error with your email address.', 'red');
        header_redirect('/forgot');
    }

    if(validate_email_exists($_POST['email'], 'users'))
    {

        $user = user_fetch($_POST['email']);

        // TODO
        // Send email

        $data['reset_hash'] = rand(100000000000, 999999999999);

        $query = 'UPDATE users SET
            hash = "'.$data['reset_hash'].'"
            WHERE email = "'.$_POST['email'].'"
            LIMIT 1';
        mysqli_query($connect, $query);

        ob_start();
        include(__DIR__.'/templates/mail_reset.php');
        $message = ob_get_contents();
        ob_end_clean();

        email_send($user['email'], $user['first'].' '.$user['last'], $message);
        die();


    }

    message_set('Email Sent', 'If there is an account associated to this email address, you will receive an email with reset instructions.');
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
            onsubmit="return validateForgotForm();"
            novalidate
        >
            <input name="email" class="w3-input" type="email" id="email" autocomplete="off" />
            <label for="email" class="w3-text-gray">
                <i class="fa-solid fa-envelope"></i> Email
                <span id="email-error" class="w3-text-red"></span>
            </label>

            <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top">
                <i class="fa-solid fa-question"></i>
                Reset Password
            </button>
        </form>
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


<?php

include('templates/html_footer.php');
include('templates/login_footer.php');

include('includes/debug.php');
