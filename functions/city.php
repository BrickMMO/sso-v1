<?php

function city_fetch($identifier)
{

    global $connect;

    $query = 'SELECT *
        FROM cities
        WHERE id = "'.addslashes($identifier).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result)) return mysqli_fetch_assoc($result);
    else return false;

}