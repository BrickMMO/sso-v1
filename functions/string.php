<?php

function string_hash($length = 10)
{

    $length--;
    return rand(pow(10, $length), pow(10, $length + 1) - 1);

}
