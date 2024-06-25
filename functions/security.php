<?php

function security_is_logged_in()
{

    if(isset($_SESSION['user']) and isset($_SESSION['user']['id']))
    {

        if(isset($_SESSION['user']['session_id']))
        {

            $user = user_fetch($_SESSION['user']['id']);

            if(password_verify($user['session_id'], $_SESSION['user']['session_id']))
            {
                return true;
            }
            else
            {
                return false;
            }

        }
        else
        {
            return false;     
        }
    }
    else
    {
        return false;
    }

}

function security_set_user_session($id)
{

    $user = user_fetch($id);

    $_SESSION['user']['id'] = $user['id'];
    $_SESSION['user']['first'] = $user['first'];
    $_SESSION['user']['last'] = $user['last'];
    $_SESSION['user']['session_id'] = password_hash($user['session_id'], PASSWORD_BCRYPT);

    // $_SESSION['user']['session_id_tmp'] = $user['session_id'];

}

function security_check()
{

    security_is_logged_in();

}