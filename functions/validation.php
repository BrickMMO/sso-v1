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