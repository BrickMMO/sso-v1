<?php

function github_url()
{
    return 'https://github.com/login/oauth/authorize?scope=read:user,user:email&client_id='.GITHUB_CLIENT_ID;
}

function github_access_token($code)
{
    $url = 'https://github.com/login/oauth/access_token';

    $data = [
        'client_id' => GITHUB_CLIENT_ID,
        'client_secret' => GITHUB_CLIENT_SECRET,
        'code' => $code,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    parse_str(curl_exec($ch), $result);
    curl_close($ch);

    return $result;

}

function github_emails($access_token)
{

    $url = "https://api.github.com/user/emails";
    
    $headers = [
        'Accept: application/json',
        'Authorization: Bearer '.$access_token,
        'User-Agent: BrickMMO',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result;

}

function github_user($access_token)
{

    $url = "https://api.github.com/user";

    $headers = [
        'Accept: application/json',
        'Authorization: Bearer '.$access_token,
        'User-Agent: BrickMMO',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result;

}