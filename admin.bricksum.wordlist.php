<?php

security_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_blank($_POST['bricksum_words']))
    {
        message_set('Word List Error', 'There was an error with your word list.', 'red');
        header_redirect('/admin/bricksum/wordlist');
    }

    setting_update('BRICKSUM_WORDS', $_POST['bricksum_words']);

    message_set('Success', 'Word list has been updated.');
    header_redirect('/admin/bricksum/wordlist');
    
}

define('PAGE_TITLE', 'GitHub Scanner | Dashboard');
define('PAGE_SELECTED_SECTION', 'admin-tools');
define('PAGE_SELECTED_SUB_PAGE', '/admin/github/dashboard');

include('templates/html_header.php');
include('templates/nav_header.php');
include('templates/nav_slideout.php');
include('templates/nav_sidebar.php');
include('templates/main_header.php');

include('templates/message.php');

$bricksum_words = setting_fetch('BRICKSUM_WORDS');

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/bricksum.png"
        height="50"
        style="vertical-align: top"
    />
    Bricksum
</h1>
<p><a href="/admin/bricksum/dashboard">Dashboard</a> / Modify Word List</p>
<hr />
<h2>Modify Word List</h2>

<form
    method="post"
    onsubmit="return validateLoginForm()"
    novalidate
>
    <textarea name="bricksum_words" class="w3-input w3-border"><?=$bricksum_words?> </textarea>
    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-bottom w3-margin-top">
        <i class="fa-solid fa-save"></i>
        Save Word List
    </button>
</form>
    
<?php

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');