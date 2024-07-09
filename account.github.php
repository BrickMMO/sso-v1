<?php

security_check();

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

<?php if($user['github_username']): ?>

    <p>
        Your BrickMMO account is currently connected to your 
        <a href="https://github.com/<?=$_SESSION['user']['github_username']?>">
            <i class="fa-brands fa-github"></i>
            <?=$_SESSION['user']['github_username']?>
        </a> 
        GitHub account.
    </p>

    <hr>

    <p>Revoking GitHub acccess from your BrickMMO account will:</p>
    <ul>
        <li>Remove all GitHub access from your BrickMMO account.</li>
        <li>Disable your BrickMMO public profile.</li>
        <li>Prevent retrieving of BrickMMO contribution stats.</li>
        <li>Not remove your GitHub avatar from your BrickMMO account.</li>
    </ul>
    <a href="<?=ENV_ACCOUNT_DOMAIN?>/action/github/revoke" class="w3-button w3-white w3-border">
        <i class="fa-solid fa-xmark fa-padding-right"></i>
        Revoke GitHub Account Access
    </a>

<?php else: ?>
    
    <a href="<?=github_url()?>">Connect my GitHub Account</a>

<?php endif; ?>


    
<?php

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');
