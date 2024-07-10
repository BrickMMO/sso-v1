<?php

define('APP_NAME', 'Profile');

define('PAGE_TITLE', 'TEST');

include('templates/html_header.php');
include('templates/login_header.php');

$user = user_fetch($_GET['key']);

?>

<div class="w3-center">

    <h1>PUBLIC PROFILE</h1>

    <h2>    
        <?=$user['first']?> <?=$user['last']?>
        <br>
        <a href="https://github.com/<?=$user['github_username']?>">
            <i class="fa-brands fa-github"></i>
            <?=$user['github_username']?>
        </a>
    </h2>

</div>

<?php

include('templates/login_footer.php');
include('templates/debug.php');
include('templates/html_footer.php');
