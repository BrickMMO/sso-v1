<?php

if(!isset($_POST['email']))
{
    header_bad_request();
    $data = array('message'=>'Missing Paramater', 'error' => true);
    die(json_encode($data));
}

$query = 'SELECT *
    FROM users
    WHERE email = "'.addslashes($_POST['email']).'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result))
{
    $data = array('message' => 'Email exists', 'error' => true);
}
else
{
    $data = array('message' => 'Email does not exists', 'error' => false);
}
die(json_encode($data));