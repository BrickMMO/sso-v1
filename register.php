<?php

// Database Connection and User Authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (
        !validate_email($_POST['email']) || 
        !validate_email_exists($_POST['email'], 'users') || 
        !validate_password($_POST['password']) || 
        !validate_blank($_POST['first']) || 
        !validate_blank($_POST['last']))
    {
        message_set('Login Error', 'There was an error with your registration informaiton.', 'red');
        header_redirect('/register');
    }

    $query = 'INSERT INTO users (
            first,
            last,
            email,
            password
        ) VALUES (
            "'.addslashes( $_POST['first'] ).'",
            "'.addslashes( $_POST['last'] ).'",
            "'.addslashes( $_POST['email'] ).'",
            "$2y$10$FAaj2VuRXAetPxgzzQP73.HBrhjtLEV1C.3ObYkoF.Lg/FI12SgbO"
        )';
    mysqli_query($connect, $query);

    // Send email

        message_set('Success', 'Your account has been created. Please confirm your email address and then login.');
    header_redirect('/login');
    
}

define('PAGE_TITLE', 'Login');

include('templates/html_header.php');
include('templates/login_header.php');

?>

<?php include('templates/message.php'); ?>

<div>

    <form
        method="post"
        id="register-form"
        novalidate
    >
        <input 
            name="first" 
            class="w3-input" 
            type="text" 
            id="first" 
            autocomplete="off"
        />
        <label for="first" class="w3-text-gray">
            First Name <span id="first-error" class="w3-text-red"></span>
        </label>

        <input 
            name="last" 
            class="w3-input" 
            type="text" 
            id="last" 
            autocomplete="off"
        />
        <label for="last" class="w3-text-gray">
            Last Name <span id="last-error" class="w3-text-red"></span>
        </label>

        <input 
            name="email" 
            class="w3-input" 
            type="email" 
            id="email" 
            autocomplete="off" 
        />  
        <label for="email" class="w3-text-gray">
            <i class="fa-solid fa-envelope"></i>
            Email <span id="email-error" class="w3-text-red"></span>
        </label>

        <input
            name="password" 
            class="w3-input"
            type="password"
            id="password"
            autocomplete="off"
        />
        <label for="password" class="w3-text-gray">
            <i class="fa-solid fa-lock"></i>
            Password <span id="password-error" class="w3-text-red"></span>
        </label>

        <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="validateRegisterForm(); return false;">
            <i class="fa-solid fa-pen"></i>
            Register
        </button>
    </form>
</div>

<div class="w3-container w3-center w3-margin">
    <button
        onclick="location.href='/login';"
        class="w3-button w3-grey w3-text-white"
    >
        <i class="fa-solid fa-caret-left"></i>
        Back to Login
    </button>
</div>

<script>

    async function validateExistingEmail(email) {
        return fetch('/ajax/email_exists',{
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({email: email})
            })  
            .then((response)=>response.json())
            .then((responseJson)=>{return responseJson});
    }

    async function validateRegisterForm() {
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

        let email_pattern = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(email_pattern)) {
            email_error.innerHTML = "(email is invalid)";
            errors++;
        } else {
            const json = await validateExistingEmail(email.value);
            if(json.error == true)
            {
                email_error.innerHTML = "(email already exists)";
                errors ++;
            }
        }

        let password = document.getElementById("password");
        let password_error = document.getElementById("password-error");
        password_error.innerHTML = "";
        if (password.value == "") {
            password_error.innerHTML = "(password is required)";
            errors++;
        }

        console.log('errors');
        console.log(errors);

        if (errors) return false;
        
        let registerForm = document.getElementById('register-form');
        registerForm.submit();
    }
</script>


<?php

include('templates/html_footer.php');
include('templates/login_footer.php');

include('includes/debug.php');