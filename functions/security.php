<?php

function security_is_logged_in()
{

    if(isset($_SESSION['user']) and isset($_SESSION['user']['id']))
    {
        return true;
    }
    else
    {
        return false;
    }

}
