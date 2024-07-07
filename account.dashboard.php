<?php

security_check();

define('APP_NAME', 'My Account');

define('PAGE_TITLE', 'Dashboard');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('templates/html_header.php');
include('templates/nav_header.php');
include('templates/nav_slideout.php');
include('templates/main_header.php');

?>

<?php include('templates/message.php'); ?>

<div class="w3-center">

    <img
        src="<?=user_avatar();?>"
        style="height: 100px"
        class="w3-circle w3-margin-top"
    />
    <h1 class="w3-margin-top w3-margin-bottom">
        Welcome, 
        <?=$_SESSION['user']['first']?>
        <?=$_SESSION['user']['last']?>
    </h1>

    <p>Manage your BrickMMO profile, avatar, and GitHub connection.</p>

</div>

<div class="w3-border w3-padding w3-margin-top w3-margin-bottom">

    <div class="w3-margin-top w3-margin-bottom">
        <a href="<?=ENV_ACCOUNT_DOMAIN?>/account/profile" class="w3-display-container">
            <i class="fa-solid fa-user fa-padding-right w3-text-dark-grey"></i>
            My Profile
            <i class="fa-solid fa-chevron-right fa-pull-right w3-text-dark-grey"></i>
        </a>
        <hr>
        <a href="<?=ENV_ACCOUNT_DOMAIN?>/account/avatar" class="w3-block">
            <i class="fa-solid fa-image-portrait fa-padding-right w3-text-dark-grey"></i>
            Avatar
            <i class="fa-solid fa-chevron-right fa-pull-right w3-text-dark-grey" class="w3-display-right"></i>
        </a>
        <hr>
        <a href="<?=ENV_ACCOUNT_DOMAIN?>/account/password" class="w3-block">
            <i class="fa-solid fa-lock fa-padding-right w3-text-dark-grey"></i>
            Change Password
            <i class="fa-solid fa-chevron-right fa-pull-right w3-text-dark-grey" class="w3-display-right"></i>
        </a>
        <hr>
        <a href="<?=ENV_ACCOUNT_DOMAIN?>/account/github" class="w3-block">
            <i class="fa-brands fa-github fa-padding-right w3-text-dark-grey"></i>
            GitHub Account
            <i class="fa-solid fa-chevron-right fa-pull-right w3-text-dark-grey" class="w3-display-right"></i>
        </a>
    </div>
</div>

<?php

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');
