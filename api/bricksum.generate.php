<?php

$bricksum_wordlist = setting_fetch('BRICKSUM_WORDLIST', 'comma_2_array');
$bricksum_stopwords = setting_fetch('BRICKSUM_STOPWORDS', 'comma_2_array');

if(isset($_GET['words']))
{

    if(!is_numeric($_GET['words']))
    {
        $data = array(
            'message' => 'Words parameter muct be numerice.',
            'error' => false,
        );
        return;
    }

    if($_GET['words'] < 10) $sentence = $_GET['words'];
    else $sentence = rand(3, 10);

    for($i = 0; $i < $_GET['words']; $i ++)
    {

        

    }

}

$data = array(
    'message' => 'Wordlist retrieved successfully.',
    'error' => false, 
    'wordlist' => $bricksum_wordlist,
);
