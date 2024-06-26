<?php

/*
 * Validation Functions
 * 
 */

/*
 * Basic email validation
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/*
 * Basic email validation
 */
function validate_email_exists($email, $table)
{
    global $connect;

    $query = 'SELECT email
        FROM '.$table.'
        WHERE email = "'.addslashes($email).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    return mysqli_num_rows($result) ? true : false;
}

/*
 * Basic validation for password
 */
function validate_password($password)
{
    return !empty($password); // Just checking if password is not empty
}

/*
 * Basic validation for blanks
 */
function validate_blank($value)
{
    return !empty($value);
}

function validate_github($github)
{
    // GitHub username can contain alphanumeric characters and hyphens/underscores
    return preg_match('/^[a-zA-Z0-9\-]{6,39}$/', $github);
}
