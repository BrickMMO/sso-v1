<?php

include('includes/connect.php');

define('PAGE_TITLE', 'Login');

include('includes/header.php');
?>

<h1> SSO For BrickMMO Login </h1>
<p> Welcome to the BrickMMO project management application. </p>

<form action="" method="POST">
    <input type="hidden" name="submit" value="true">
    <label>
        Email:
        <input type="email" name="email">
        <br>
    </label>

    <label>
        Password:
        <input type="password" name="password">
        <br>
    </label>

    <div class="flex-container">
        <div>
            <input type="checkbox" id="remember_me" name="remember_me" value="remember_me">
            <label for="remember_me" style="display:inline;"> Remember me </label>
        </div>
    </div>

    <input type="submit" value="Login">
</form>

<hr>

<div class="right">
    <a href="forgot.php">Forgot Password?</a> | <a href="/pages/signup.php"> Sign Up</a>
</div>