<?php

if(!isset($_GET['code']) || isset($_GET['error']))
{
    message_set('GitHub Error', 'There was an error authenticating your GitHub account.', 'red');
    header_redirect('/login');
}

$token = github_access_token($_GET['code']);
debug_pre($token);

if(!is_array($token) or !isset($token['access_token']))
{
    message_set('GitHub Error', 'There was an error authenticating your GitHub account.', 'red');
    header_redirect('/login');
}

$emails = github_emails($token['access_token']);
debug_pre($emails);

if(!is_array($emails) or !count($emails))
{
    message_set('GitHub Error', 'There was an error authenticating your GitHub account.', 'red');
    header_redirect('/login');
}

$user = github_user($token['access_token']);
debug_pre($user);

$names = string_split_name($user['name']);

// $avatar 

die();

$query = 'INSERT INTO users (
        first,
        last,
        email,
        github_username,
        github_access_token,
        verify_hash
    ) VALUES (
        "'.addslashes($names['first']).'",
        "'.addslashes($names['last']).'",
        "'.addslashes($emails[0]['email']).'",
        "'.addslashes($user['login']).'",
        "'.addslashes($token['access_token']).'",
        "'.string_hash().'"
    )';
mysqli_query($connect, $query);

$user = user_fetch($emails[0]['email']);

ob_start();
include(__DIR__.'/templates/email_register.php');
$message = ob_get_contents();
ob_end_clean();

email_send($user['email'], $user['first'].' '.$user['last'], $message);

// Start session and store user data
$_SESSION['user'] = $user;

message_set('Success', 'Your account has been created nad you have been logged in. Please confirm your email address.');
header_redirect('/dashboard');
