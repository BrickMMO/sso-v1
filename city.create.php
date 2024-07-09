<?php

security_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_blank($_POST['name']) || 
        !validate_blank($_POST['width']) || 
        !validate_blank($_POST['length']))
    {
        message_set('Login Error', 'There was an error with your city information.', 'red');
        header_redirect('/city/create');
    }

    $query = 'INSERT INTO cities (
        name,
        width,
        length,
        user_id,
        date_multiplier,
        date_at,
        created_at,
        updated_at
        ) VALUES (
         "'.addslashes($_POST['name']).'",
         "'.addslashes($_POST['width']).'",
         "'.addslashes($_POST['length']).'",
         '.$_SESSION['user']['id'].',
         1,
         NOW(),
         NOW(),
         NOW()
        )';
    $result = mysqli_query($connect, $query);

    $city_id = mysqli_insert_id($connect);

    $query = 'INSERT INTO city_user (
            city_id, 
            user_id
        ) VALUES (
            '.$city_id.',
            '.$_SESSION['user']['id'].'
        )';
    mysqli_query($connect, $query);

    $query = 'UPDATE users SET
        city_id = '.$city_id.'
        WHERE id = '.$_SESSION['user']['id'].'
        LIMIT 1';
    mysqli_query($connect, $query);


    message_set('Congratulations', 'A new city has been created.');
    header_redirect('/console/dashboard');
    
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
        id="name" 
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

    function validateCityForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        let width = document.getElementById("width");
        let width_error = document.getElementById("width-error");
        width_error.innerHTML = "";
        if (width.value == "") {
            width_error.innerHTML = "(width is required)";
            errors++;
        }

        let length = document.getElementById("length");
        let length_error = document.getElementById("length-error");
        length_error.innerHTML = "";
        if (length.value == "") {
            length_error.innerHTML = "(length is required)";
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
