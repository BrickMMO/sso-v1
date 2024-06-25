<?php

function string_hash($length = 10)
{

    $length--;
    return rand(pow(10, $length), pow(10, $length + 1) - 1);

}

function string_split_name($name)
{
    $names = explode(' ', $name);

    debug_pre($names);
    $result['first'] = $names[0];
    $result['last'] = $names[count($names)-1];

    return $result;
}
