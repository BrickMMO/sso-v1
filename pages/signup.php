<?php

include('../includes/connect.php');

define('PAGE_TITLE', 'Sign UP');

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
        header("Location: ../index.php");
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
                            $_SESSION['user_id'] = $stmt->insert_id;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $email;
                            $_SESSION['github'] = $github;
                            $_SESSION['avatar'] = $avatar_name;

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

<h1> SSO For BrickMMO Register </h1>
<p> Welcome to the BrickMMO project management application. </p>

<form action="" method="POST" enctype="multipart/form-data" id="signupForm">
    <input type="hidden" name="submit" value="true">
    <label>
        Name:
        <input type="text" name="name" id="name" placeholder="Enter your name" value="<?php echo isset($name) ? $name : ''; ?>">
        <br>
    </label>

    <label>
        Email:
        <input type="email" name="email" id="email" placeholder="Enter your email address" value="<?php echo isset($email) ? $email : ''; ?>">
        <br>
    </label>

    <label>
        GitHub:
        <input type="text" name="github" id="github" placeholder="Enter your GitHub username" value="<?php echo isset($github) ? $github : ''; ?>">
        <br>
    </label>

    <label>
        Password:
        <input type="password" name="password" id="password" placeholder="Enter your password">
        <br>
    </label>

    <label>
        Avatar:
        <input type="file" name="avatar" id="avatar" accept="image/*">
        <br>
    </label>

    <input type="submit" value="Sign Up">
</form>

<hr>

<div class="right">
    <a href="../"> If you are an existing user? </a>
</div>