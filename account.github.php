<?php

security_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_email($_POST['email']) || 
        !validate_blank($_POST['first']) || 
        !validate_blank($_POST['last']) || 
        validate_email_exists($_POST['email'], 'users', $_SESSION['user']['id']))
    {
        message_set('Login Error', 'There was an error with your profile information.', 'red');
        header_redirect('/account/profile');
    }

    $query = 'UPDATE users SET
        first = "'.addslashes($_POST['first']).'",
        last = "'.addslashes($_POST['last']).'",
        email = "'.addslashes($_POST['email']).'"
        WHERE id = '.$_SESSION['user']['id'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Success', 'Your profile has been updated.');
    header_redirect('/account/profile');
    
}

define('APP_NAME', 'My Account');

define('PAGE_TITLE', 'GitHub Account');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('templates/html_header.php');
include('templates/nav_header.php');
include('templates/nav_slideout.php');
include('templates/main_header.php');

include('templates/message.php');

$user = user_fetch($_SESSION['user']['id']);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/bricksum.png"
        height="50"
        style="vertical-align: top"
    />
    My Account
</h1>
<p>
    <a href="/account/dashboard">Dashboard</a> / 
    GitHub Account
</p>
<hr />
<h2>GitHub Account</h2>


    
<?php

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');
