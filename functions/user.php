<?php

function user_fetch($id)
{

    global $connect;

    $query = 'SELECT * 
        FROM users
        WHERE id = "'.addslashes($id).'"
        OR email = "'.addslashes($id).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result)) return mysqli_fetch_assoc($result);
    else return false;

}