<?php

/*
 * Connect to the database
 * 
 * Load environment variables from the .env file and then use
 * the database variables to connect to a MySQL database. 
 */
$env = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($env as $value)
{
    $value = explode('=', $value);
    define($value[0], $value[1]);
}

$connect = mysqli_connect(
    DB_HOST,
    DB_USERNAME,
    DB_PASSWORD,
    DB_DATABASE
);

// Check Connection
if (!$connect) 
{
    die("Connection Failed:" . mysqli_connect_error());
}
else 
{
    // echo "Connection Successfully Establish !!!";
}
