<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= PAGE_TITLE ?> | SSO For BrickMMO</title>

    <link rel="stylesheet" type="text/css" href="/sso-v1/assets/style/styles.css">
</head>

<?php
include('../includes/connect.php');
include('../includes/session.php');

// Function to destroy session and redirect to index.php
function logout()
{
    $_SESSION = array(); // Clear all session variables
    session_destroy();   // Destroy the session
    header("Location: ../index.php"); // Redirect to index.php
    exit();
}

// Check if logout action is triggered
if (isset($_GET['logout'])) {
    logout();
}

// Fetch cities from the database
$query = "SELECT * FROM cities WHERE status = 'Active'";
$result = mysqli_query($connect, $query);

$cities = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }
}

// Determine selected city ID
$selectedCityId = isset($_POST['city']) ? $_POST['city'] : (isset($_SESSION['selected_city']) ? $_SESSION['selected_city'] : '');
if (empty($selectedCityId) && !empty($cities)) {
    $selectedCityId = $cities[0]['id']; // Default to the first city if none is selected
    $_SESSION['selected_city'] = $selectedCityId;
}
?>

<body>
    <div id="container">

        <header class="header">
            <div class="header-left">
                <span class="site-name">SSO FOR BrickMMO</span>
                <form action="" method="POST" class="city-form">
                    <select name="city" id="cityDropdown" onchange="this.form.submit()">
                        <?php foreach ($cities as $city) : ?>
                            <?php $selected = ($city['id'] == $selectedCityId) ? 'selected' : ''; ?>
                            <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>><?php echo $city['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="header-right">
                <span class="user-info">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?>!</span>
                <a href="./profile.php">My Account</a>
                <a href="../index.php">Logout</a>
            </div>
        </header>

        <hr>