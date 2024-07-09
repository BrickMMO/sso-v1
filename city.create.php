<?php

security_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_email($_POST['email']) || 
        !validate_blank($_POST['first']) || 
        !validate_blank($_POST['last']) || 
        validate_email_exists($_POST['email'], 'users', $_SESSION['user']['id']))
    {
        message_set('Login Error', 'There was an error with your profile information.', 'red');
        header_redirect('/account/profile');
    }

    $query = 'UPDATE users SET
        first = "'.addslashes($_POST['first']).'",
        last = "'.addslashes($_POST['last']).'",
        email = "'.addslashes($_POST['email']).'"
        WHERE id = '.$_SESSION['user']['id'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Success', 'Your profile has been updated.');
    header_redirect('/account/dashboard');
    
}

define('APP_NAME', 'My Account');

define('PAGE_TITLE', 'Create City');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('templates/html_header.php');
include('templates/nav_header.php');
include('templates/nav_slideout.php');
include('templates/main_header.php');

include('templates/message.php');

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/bricksum.png"
        height="50"
        style="vertical-align: top"
    />
    My Account
</h1>
<p>
    <a href="/account/dashboard">Dashboard</a> / 
    Create City
</p>
<hr />

<h2>Create City</h2>

<form
    method="post"
    novalidate
    id="city-form"
>

    <input  
        name="name" 
        class="w3-input w3-border" 
        type="text" 
        id="city" 
        autocomplete="off"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <input 
        name="width" 
        class="w3-input w3-margin-top w3-border" 
        type="number" 
        id="width" 
        autocomplete="off"
    />
    <label for="width" class="w3-text-gray">
        <i class="fa-solid fa-ruler"></i>
        Width <span id="width-error" class="w3-text-red"></span>
    </label>

    <input 
        name="length" 
        class="w3-input w3-border w3-margin-top" 
        type="number" 
        id="length" 
        autocomplete="off" 
    />  
    <label for="length" class="w3-text-gray">
        <i class="fa-solid fa-ruler"></i>
        Length <span id="length-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateCityForm();">
        <i class="fa-solid fa-plus fa-padding-right"></i>
        Create City
    </button>
</form>

<script>

    async function validateCityForm() {
        let errors = 0;

        let first = document.getElementById("first");
        let first_error = document.getElementById("first-error");
        first_error.innerHTML = "";
        if (first.value == "") {
            first_error.innerHTML = "(first name is required)";
            errors++;
        }

        let last = document.getElementById("last");
        let last_error = document.getElementById("last-error");
        last_error.innerHTML = "";
        if (last.value == "") {
            last_error.innerHTML = "(last name is required)";
            errors++;
        }

        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        }

        if (errors) return false;
        
        let form = document.getElementById('city-form');
        form.submit();
    }

</script>
    
<?php

include('templates/modal_city.php');

include('templates/debug.php');

include('templates/main_footer.php');
include('templates/html_footer.php');
