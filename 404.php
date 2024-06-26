<?php

header_not_found();

define('PAGE_TITLE', 'Account Dashboard');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<h1>404 Error</h1>

<?php include('templates/message.php'); ?>

<a href="/logout">Logout</a> | 
<a href="/login">Login</a> | 
<a href="/dashboard">Dashboard</a>

<?php

include('templates/debug.php');

include('templates/login_footer.php');
include('templates/html_footer.php');
