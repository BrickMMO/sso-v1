<?php

define('DEBUG', true);

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

$parts = array_filter(explode("/", trim($_SERVER['REQUEST_URI'], "/")));

if(!count($parts))
{
    header_redirect('/dashboard');
}
else
{
    if($parts[0] == 'ajax')
    {
        define('PAGE_TYPE', 'ajax');
        array_shift($parts);

        $file = array_shift($parts).'.php';
    }
    if($parts[0] == 'api')
    {
        define('PAGE_TYPE', 'api');
        array_shift($parts);

        $file = array_shift($parts).'.php';
    }
    else
    {
        define('PAGE_TYPE', 'web');

        $file = '';

        foreach($parts as $part)
        {
            
            $file = str_replace('php', '', $file);
            $file .= array_shift($parts).'.php';

            if(file_exists($file)) 
            {
                define('PAGE_FILE', $file);
                break;
            }

        }
        
    }

    for($i = 0; $i < count($parts); $i += 2)
    {
        $_GET[$parts[$i]] = isset($parts[$i+1]) ? $parts[$i+1] : true;
    }

    if(PAGE_TYPE == 'ajax') 
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        include('ajax/'.$file);
    }
    elseif(PAGE_TYPE == 'api') 
    {
        include('api/'.$file);
        echo json_encode($data);
    }
    elseif(file_exists($file)) 
    {
        include($file);
    }
    else include('404.php');
}