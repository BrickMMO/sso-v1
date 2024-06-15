<?php
define('PAGE_TITLE', 'My Account');

include('../includes/mainheader.php');

include('../includes/sidebar.php');

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

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, github, avatar FROM users WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $github, $avatar);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $github = $_POST['github'];
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

    if (empty($errors)) {
        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "../assets/uploads/";
            $file_extension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
            $avatar_name = uniqid('avatar_', true) . '.' . $file_extension;
            $target_file = $target_dir . $avatar_name;

            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                // Update avatar in session and database
                $_SESSION['avatar'] = $avatar_name;
                $update_avatar_query = "UPDATE users SET avatar = ? WHERE id = ?";
                $stmt = $connect->prepare($update_avatar_query);
                $stmt->bind_param('si', $avatar_name, $user_id);
                $stmt->execute();
                $stmt->close();
            } else {
                $_SESSION['error'] = "Failed to upload avatar.";
                header('Location: profile.php');
                exit;
            }
        }


        // Update user data in the database
        $update_query = "UPDATE users SET name = ?, email = ?, github = ? WHERE id = ?";
        $stmt = $connect->prepare($update_query);
        $stmt->bind_param('sssi', $name, $email, $github, $user_id);

        if ($stmt->execute()) {
            // Update session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['github'] = $github;

            $_SESSION['success'] = "Profile updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }

        $stmt->close();
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div style='color: red;'>$error</div>";
        }
    }
}
?>

<div class="w3-container">
    <h1 class="w3-text-teal">My Account</h1>

    <div class="w3-row-padding">
        <div class="w3-third">
            <div class="w3-card w3-padding">
                <img src='<?php echo "../assets/uploads/" . $avatar; ?>' alt="User Avatar" class="w3-image w3-round" style="width: 100%">
            </div>
        </div>

        <div class="w3-twothird">
            <div class="w3-card w3-padding">
                <form action="profile.php" method="POST" id="accountForm" enctype="multipart/form-data" onsubmit="return validateAccountForm();" novalidate>
                    <input class="w3-input w3-margin-top" type="text" name="name" id="name" value="<?php echo $name; ?>" autocomplete="off" required disabled>
                    <label for="name" class="w3-text-gray">
                        <i class="fa-solid fa-user"></i>
                        Name
                        <span id="name-error" class="w3-text-red"></span>
                    </label>

                    <input class="w3-input w3-margin-top" type="email" name="email" id="email" value="<?php echo $email; ?>" autocomplete="off" required disabled>
                    <label for="email" class="w3-text-gray">
                        <i class="fa-solid fa-envelope"></i>
                        Email
                        <span id="email-error" class="w3-text-red"></span>
                    </label>

                    <input class="w3-input w3-margin-top" type="text" name="github" id="github" value="<?php echo $github; ?>" autocomplete="off" required disabled>
                    <label for="github" class="w3-text-gray">
                        <i class="fa-brands fa-github"></i>
                        GitHub
                        <span id="github-error" class="w3-text-red"></span>
                    </label>

                    <input class="w3-input w3-margin-top" type="file" style="display: none;" name="avatar" id="avatar" accept="image/*" autocomplete="off">
                    <label for="avatar" id="avatar_img" style="display: none;" class="w3-text-gray">
                        <i class="fa-solid fa-image"></i>
                        Avatar
                        <span id="avatar-error" class="w3-text-red"></span>
                    </label>

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <button type="submit" class="w3-button w3-teal w3-margin-top w3-block" id="saveButton" style="display: none;" name="submit">Save</button>
                </form>
                <div class="w3-row w3-margin-top" style="display: flex; align-items:center; justify-content:end; gap:5px;">
                    <button class="w3-button w3-blue w3-margin-bottom w3-block" id="updateButton">Update</button>
                    <button class="w3-button w3-red w3-margin-bottom w3-block" id="cancelButton" style="display: none;">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="w3-container w3-right-align w3-margin">
    <button onclick="location.href='dashboard.php';" class="w3-button w3-grey w3-text-white">
        <i class="fa-solid fa-caret-left"></i>
        Back to Dashboard
    </button>
</div>


<script>
    document.getElementById('updateButton').addEventListener('click', function() {
        var inputs = document.querySelectorAll('#accountForm input[type="text"], #accountForm input[type="email"], #accountForm input[type="file"]');
        inputs.forEach(function(input) {
            input.disabled = false;
        });
        document.getElementById('cancelButton').style.display = "inline-block";
        document.getElementById('updateButton').style.display = 'none';
        document.getElementById('avatar').style.display = 'block';
        document.getElementById('avatar_img').style.display = 'block';
        document.getElementById('saveButton').style.display = 'inline-block';
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        var inputs = document.querySelectorAll('#accountForm input[type="text"], #accountForm input[type="email"], #accountForm input[type="file"]');
        inputs.forEach(function(input) {
            input.disabled = true;
        });
        document.getElementById('updateButton').style.display = 'block';
        document.getElementById('cancelButton').style.display = "none";
        document.getElementById('avatar').display = 'none';
        document.getElementById('avatar_img').style.display = 'none';
        document.getElementById('saveButton').style.display = 'none';
    });

    // Client-side validation
    function validateAccountForm() {
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

        return errors === 0;
    }
</script>