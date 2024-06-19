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
    header('Location: '.$url);
    exit;
}
