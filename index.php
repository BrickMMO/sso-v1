<?php

require __DIR__ . '/vendor/autoload.php';

include('includes/connect.php');
include('includes/session.php');
include('functions/functions.php');

if(strpos($_SERVER['REQUEST_URI'], '?'))
{
    $url = $_SERVER['REQUEST_URI'];
    $url = str_replace(array('?','='), '/', $url);
    header_redirect($url);
}

$parts = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

if(!count($parts))
{
    header_redirect('/dashboard');
}
else
{
    if($parts[0] == 'ajax')
    {
        define('PAGE_AJAX', true);
        array_shift($parts);
    }
    else
    {
        define('PAGE_AJAX', false);
    }
    
    $file = array_shift($parts).'php';
    if(!file_exists($file))
    {
        die('here');
    }
    else
    {
        die('more');
    }
    $counter = 0;

    if(file_exists($parts[1].'.php'))
    {
        define('PAGE_FILE', array_shift($parts));    
    }
    else
    {
        die('here');
    }
    die($parts[0]);

    define('PAGE_FILE', array_shift($parts));

    for($i = 0; $i < count($parts); $i += 2)
    {
        $_GET[$parts[$i]] = isset($parts[$i+1]) ? $parts[$i+1] : true;
    }

    $file = PAGE_FILE.'.php';
    if(PAGE_AJAX) 
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $file = 'ajax/'.$file;
    }



    if(file_exists($file)) include($file);
    else include('404.php');
}