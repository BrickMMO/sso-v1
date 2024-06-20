<?php

include('includes/connect.php');
include('includes/session.php');
include('functions/functions.php');

require __DIR__ . '/vendor/autoload.php';

$parts = explode("/", trim( $_SERVER['REQUEST_URI'], "/" ));

if(!count($parts))
{
    header_redirect('/dashboard');
}
else
{
    define('PAGE_FILE', array_shift($parts));

    for($i = 0; $i < count($parts); $i += 2)
    {
        $_GET[$parts[$i]] = isset($parts[$i+1]) ? $parts[$i+1] : true;
    }

    if(file_exists(PAGE_FILE.'.php')) include(PAGE_FILE.'.php');
    else include('404.php');
}