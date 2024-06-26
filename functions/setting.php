<?php

function setting_fetch($name, $format = 'plain')
{

    global $connect;

    $query = 'SELECT value
        FROM settings
        WHERE name = "'.$name.'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);
    $record = mysqli_fetch_assoc($result);

    switch($format)
    {
        case 'comma': 
            $record['value'] = str_replace(array(', ', ','), ',', $record['value']);
            return explode(',', $record['value']);
        default:
            return $record['value'];
    }

}

function setting_update($name, $value)
{

    global $connect;

    $query = 'UPDATE settings SET
        value = "'.addslashes($value).'"
        WHERE name = "'.$name.'"
        LIMIT 1';
    mysqli_query($connect, $query);

}
