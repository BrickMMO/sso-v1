<?php

/*
 * Header Functions
 * 
 */

/*
 * Basic location header
 */
function header_redirect($url)
{
    echo 'Redirect: <a href="'.$url.'">'.$url.'</a>';
    // header('Location: '.$url);
    exit;
}

function header_bad_request()
{
    header('HTTP/1.1 400 BAD REQUEST');
}

function header_not_found()
{
    header('HTTP/1.1 404 NOT FOUND');
}