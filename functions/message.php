<?php

function set_message($title, $text, $colour = 'green', $icon = 'fa-triangle-exclamation')
{
    $_SESSION['message']['title'] = $title;
    $_SESSION['message']['text'] = $text;
    $_SESSION['message']['colour'] = $colour;
    $_SESSION['message']['icon'] = $icon;
}
