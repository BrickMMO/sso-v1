<?php

security_check();

define('PAGE_TITLE', 'My Account | Dashboard');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('templates/html_header.php');
include('templates/nav_header.php');
include('templates/nav_slideout.php');
include('templates/main_header.php');

?>

<h1>Dashboard</h1>

<?php include('templates/message.php'); ?>

<a href="/logout">Logout</a> | 
<a href="/login">Login</a> | 
<a href="/dashboard">Dashboard</a>

<?php

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');
