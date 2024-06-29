<?php

function user_fetch($identifier)
{

    global $connect;

    $query = 'SELECT *
        FROM users
        WHERE id = "'.addslashes($identifier).'"
        OR email = "'.addslashes($identifier).'"
        OR (reset_hash = "'.addslashes($identifier).'" AND reset_hash != "")
        OR (verify_hash = "'.addslashes($identifier).'" AND verify_hash != "")
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result)) return mysqli_fetch_assoc($result);
    else return false;

}
