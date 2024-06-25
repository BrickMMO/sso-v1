<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'Register');

include('../includes/header.php');

include('../includes/session.php');

$errors = [];
$name = $email = $github = $password = '';

// ===== Server-Side Validation =====
function validateName($name)
{
    // Allow only letters and spaces
    return preg_match('/^[A-Za-z\s]+$/', $name);
}

function validateEmail($email)
{
    // basic email validation
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateGitHub($github)
{
    // GitHub username can contain alphanumeric characters and hyphens/underscores
    return preg_match('/^[a-zA-Z0-9\-_]+$/', $github);
}

function validatePassword($password)
{
    // Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

// Check if email already exists
function emailExists($email, $connect)
{
    $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['count'] > 0;
}

// Check if there are active cities
function getActiveCities($connect)
{
    $query = "SELECT id FROM cities WHERE status = 'active'";
    $result = $connect->query($query);
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['id'];
    }
    return $cities;
}

// Database Connection and Insert Query After Submit Event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $github = $_POST['github'];
    $password = $_POST['password'];
    $avatar_name = '';

    // Validate inputs
    $errors = [];
    if (!validateName($name)) {
        $errors[] = "Name can only contain letters and spaces.";
    }
    if (!validateEmail($email)) {
        $errors[] = "Invalid email format.";
    }
    if (!validateGitHub($github)) {
        $errors[] = "GitHub username can only contain alphanumeric characters, hyphens, and underscores.";
    }
    if (!validatePassword($password)) {
        $errors[] = "Password must be at least 8 characters long and include one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    // Check if email already exists
    if (emailExists($email, $connect)) {
        // Redirect to login page
        header("Location: ../");
    } else {
        // Handle file upload if there are no validation errors
        if (empty($errors)) {
            // Hash the password using MD5 
            $password = md5($password);

            // Handle file upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
                $upload_dir = '../assets/uploads/';
                $avatar_tmp_name = $_FILES['avatar']['tmp_name'];
                $avatar_size = $_FILES['avatar']['size'];

                // Check file size
                if ($avatar_size > $max_file_size) {
                    $errors[] = "Error: The file size exceeds the maximum limit of 5 MB.";
                } else {
                    // Sanitize the username to use as the file name
                    $sanitized_username = strtolower(str_replace(' ', '_', $name));
                    $file_extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                    $avatar_name = $sanitized_username . '.' . $file_extension;
                    $avatar_path = $upload_dir . $avatar_name;

                    // Ensure the upload directory exists
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }

                    // Move the uploaded file to the upload directory
                    if (move_uploaded_file($avatar_tmp_name, $avatar_path)) {
                        // Insert user data into the database
                        $query = "INSERT INTO users (name, email, github, password, avatar) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $connect->prepare($query);
                        $stmt->bind_param('sssss', $name, $email, $github, $password, $avatar_name);

                        if ($stmt->execute()) {
                            // Store user data in session
                            $user_id = $stmt->insert_id;
                            $_SESSION['user_id'] = $stmt->insert_id;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $email;
                            $_SESSION['github'] = $github;
                            $_SESSION['avatar'] = $avatar_name;

                            // Check for active cities and insert into city_user table if found
                            $active_cities = getActiveCities($connect);
                            foreach ($active_cities as $city_id) {
                                $query = "INSERT INTO city_user (user_id, city_id) VALUES (?, ?)";
                                $stmt = $connect->prepare($query);
                                $stmt->bind_param('ii', $user_id, $city_id);
                                $stmt->execute();
                                $stmt->close();
                            }

                            // Redirect to dashboard after successful registration
                            header("Location: dashboard.php");
                            exit(); // Ensure no further code is executed
                        } else {
                            $errors[] = "Error: " . $stmt->error;
                        }

                        $stmt->close();
                        $connect->close();
                    } else {
                        $errors[] = "Failed to upload avatar.";
                    }
                }
            } else {
                $errors[] = "No avatar uploaded or there was an error uploading the file.";
            }
        }

        // Display errors if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div style='color: red;'>$error</div>";
            }
        }
    }
}
?>

<div>
    <form action="" method="POST" enctype="multipart/form-data" id="signupForm" onsubmit="return validateRegisterForm();" novalidate>
        <input class="w3-input" type="text" name="name" id="name" autocomplete="off" value="<?php echo isset($name) ? $name : ''; ?>" />
        <label for="name" class="w3-text-gray">
            <i class="fa-solid fa-user"></i>
            Name <span id="name-error" class="w3-text-red"></span>
        </label>

        <input class="w3-input" type="email" name="email" id="email" autocomplete="off" value="<?php echo isset($email) ? $email : ''; ?>" />
        <label for="email" class="w3-text-gray">
            <i class="fa-solid fa-envelope"></i>
            Email <span id="email-error" class="w3-text-red"></span>
        </label>

        <input class="w3-input" type="text" name="github" id="github" autocomplete="off" value="<?php echo isset($github) ? $github : ''; ?>" />
        <label for="github" class="w3-text-gray">
            <i class="fa-brands fa-github"></i>
            GitHub <span id="github-error" class="w3-text-red"></span>
        </label>

        <input class="w3-input" type="password" name="password" id="password" autocomplete="off" />
        <label for="password" class="w3-text-gray">
            <i class="fa-solid fa-lock"></i>
            Password <span id="password-error" class="w3-text-red"></span>
        </label>

        <input class="w3-input" type="file" name="avatar" id="avatar" accept="image/*" autocomplete="off" />
        <label for="avatar" class="w3-text-gray">
            <i class="fa-solid fa-image"></i>
            Avatar <span id="avatar-error" class="w3-text-red"></span>
        </label>

        <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" name="submit">
            <i class="fa-solid fa-pen"></i>
            Register
        </button>
    </form>
</div>

<div class="w3-container w3-center w3-margin">
    <button onclick="location.href='../';" class="w3-button w3-grey w3-text-white">
        <i class="fa-solid fa-caret-left"></i>
        Back to Login
    </button>
</div>

<!-- Client-side validation -->
<script>
    function validateRegisterForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        let email_pattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/;
        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(email_pattern)) {
            email_error.innerHTML = "(email is invalid)";
            errors++;
        }

        let github = document.getElementById("github");
        let github_error = document.getElementById("github-error");
        github_error.innerHTML = "";
        if (github.value == "") {
            github_error.innerHTML = "(GitHub username is required)";
            errors++;
        }

        let password = document.getElementById("password");
        let password_error = document.getElementById("password-error");
        password_error.innerHTML = "";
        if (password.value == "") {
            password_error.innerHTML = "(password is required)";
            errors++;
        }

        return errors === 0;
    }
</script>

<!-- Need to Add City by default -->