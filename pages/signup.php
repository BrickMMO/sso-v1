<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'Sign UP');

include('../includes/header.php');
?>

<h1> SSO For BrickMMO Register </h1>
<p> Welcome to the BrickMMO project management application. </p>

<form action="" method="POST">
    <input type="hidden" name="submit" value="true">
    <label>
        Name:
        <input type="text" name="name" placeholder="Enter your name">
        <br>
    </label>

    <label>
        Email:
        <input type="email" name="email" placeholder="Enter your email address">
        <br>
    </label>

    <label>
        GitHub:
        <input type="text" name="github" placeholder="Enter your github username">
        <br>
    </label>

    <label>
        Password:
        <input type="password" name="password" placeholder="Enter your password">
        <br>
    </label>

    <input type="submit" value="Sign Up">
</form>

<hr>

<div class="right">
    <a href="../index.php"> Login </a>
</div>