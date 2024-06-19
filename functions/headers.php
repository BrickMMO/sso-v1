<?php

/*
 * Header Functions
 * 
 */

/*
 * Basic location header
 */
function redirect($url)
{
    echo 'Redirect: <a href="'.$url.'">'.$url.'</a>';
    // header('Location: '.$url);
    exit;
}
