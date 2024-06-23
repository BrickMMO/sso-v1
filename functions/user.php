<?php

function user_fetch($id)
{

    global $connect;

    $query = 'SELECT * 
        FROM users
        WHERE id = "'.addslashes($id).'"
        OR email = "'.addslashes($id).'"
        OR (reset_hash = "'.addslashes($id).'" AND reset_hash != "")
        OR (verify_hash = "'.addslashes($id).'" AND verify_hash != "")
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result)) return mysqli_fetch_assoc($result);
    else return false;

}