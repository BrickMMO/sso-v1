<?php
define('PAGE_TITLE', 'My Account');

include('../includes/mainheader.php');

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, github,avatar FROM users WHERE id = ?";
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

    // Handle avatar upload
    if (!empty($_FILES['avatar']['name'])) {
        $target_dir = "../assets/uploads/";
        $file_extension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        $avatar_name = uniqid('avatar_', true) . '.' . $file_extension;
        $target_file = $target_dir . $avatar_name;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $avatar = $avatar_name;
        } else {
            $_SESSION['error'] = "Failed to upload avatar.";
            header('Location: profile.php');
            exit;
        }
    }

    // Update user data in the database
    $update_query = "UPDATE users SET name = ?, email = ?, github = ?, avatar = ? WHERE id = ?";
    $stmt = $connect->prepare($update_query);
    $stmt->bind_param('ssssi', $name, $email, $github, $avatar, $user_id);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['github'] = $github;
        $_SESSION['avatar'] = $avatar;

        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

    $stmt->close();

    // Reload the page to reflect changes
    header('Location: profile.php');
    exit;
}
?>

<h1> MY Account </h1>

<div class="account-container">
    <div class="avatar">
        <img src='<?php echo "../assets/uploads/" . $avatar; ?>' alt="User Avatar">
    </div>
    <div class="account-details">
        <form action="profile.php" method="POST" id="accountForm" enctype="multipart/form-data">
            <label>
                Name:
                <input type="text" name="name" value="<?php echo $name; ?>" disabled>
            </label>
            <label>
                Email:
                <input type="email" name="email" value="<?php echo $email; ?>" disabled>
            </label>
            <label>
                GitHub:
                <input type="text" name="github" value="<?php echo $github; ?>" disabled>
            </label>
            <label id="avatar_img" style="display: none;">
                Avatar:
                <input type="file" name="avatar" disabled>
            </label>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="submit" value="Save" id="saveButton" style="display: none;">
        </form>

        <button id="updateButton">Update</button>
        <button id="cancelButton" style="display: none;">Cancel</button>
    </div>
</div>
<div class="right">
    <a href="dashboard.php" class="back-link">Back to Dashboard</a>
</div>
<script>
    document.getElementById('updateButton').addEventListener('click', function() {
        var inputs = document.querySelectorAll('#accountForm input[type="text"], #accountForm input[type="email"], #accountForm input[type="file"]');
        inputs.forEach(function(input) {
            input.disabled = false;
        });
        document.getElementById('cancelButton').style.display = "block"
        document.getElementById('avatar_img').style.display = 'block';
        document.getElementById('saveButton').style.display = 'block';
    });

    document.getElementById('cancelButton').addEventListener('click', function() {
        var inputs = document.querySelectorAll('#accountForm input[type="text"], #accountForm input[type="email"], #accountForm input[type="file"]');
        inputs.forEach(function(input) {
            input.disabled = true;
        });
        document.getElementById('cancelButton').style.display = "none"
        document.getElementById('avatar_img').style.display = 'none';
        document.getElementById('saveButton').style.display = 'none';
    })
</script>