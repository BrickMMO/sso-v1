<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= PAGE_TITLE ?> | SSO For BrickMMO</title>

    <?php
    // Get the current page URL 
    $current_url = 'http://localhost' . $_SERVER['REQUEST_URI'];
    ?>

    <link href=<?php echo $current_url . "assets/style/styles.css"; ?> type="text/css" rel="stylesheet">

</head>

<body>

    <div id="container">

        <div class="center">

            <span style="font-size: 90px;">&#8660;</span>

        </div>

        <hr>