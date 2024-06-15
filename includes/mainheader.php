<?php
include_once('../includes/connect.php');
include_once('../includes/session.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= PAGE_TITLE ?> | | BrickMMO SSO </title>

    <!-- W3 School CSS -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />

    <!-- Font Awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/sso-v1/assets/style/styles.css">

    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="/sso-v1/assets/images/favicon.ico">

    <!-- Script For Fontawsome Icons -->
    <script src="https://kit.fontawesome.com/a74f41de6e.js" crossorigin="anonymous"></script>
</head>

<?php
// Function to destroy session and redirect to login
function logout()
{
    $_SESSION = array(); // Clear all session variables
    session_destroy();   // Destroy the session
    header("Location: ../"); // Redirect to login
    exit();
}

// Check if logout action is triggered
if (isset($_GET['logout'])) {
    logout();
}

// Fetch cities from the database
$query = "SELECT * FROM cities";
$stmt = $connect->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
}

// Profile Image
$profile_image = $_SESSION['avatar'];

// Determine selected city ID
$selectedCityId = isset($_POST['city']) ? $_POST['city'] : (isset($_SESSION['selected_city']) ? $_SESSION['selected_city'] : '');
if (empty($selectedCityId) && !empty($cities)) {
    $selectedCityId = $cities[0]['id']; // Default to the first city if none is selected
    $_SESSION['selected_city'] = $selectedCityId;
}
?>

<body>
    <nav class="w3-bar w3-border-bottom w3-padding w3-white w3-top" style="position: sticky; z-index: 102">
        <div class="w3-row">
            <div class="w3-col s8">
                <div style="display: flex; align-items: center;">
                    <button class="w3-button" onclick="w3_sidebar_toggle()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <a href="../pages/dashboard.php" class="w3-margin-left"><img src="/sso-v1/assets/images/brickmmo_logo.png" style="height: 35px" /></a>
                    <form action="" method="POST" class="city-form w3-margin-left">
                        <select class="w3-select w3-border" name="city" id="cityDropdown" onchange="this.form.submit()">
                            <?php foreach ($cities as $city) : ?>
                                <?php if ($city['status'] == "Active") : ?>
                                    <?php
                                    $selected = ($city['id'] == $selectedCityId) ? 'selected' : '';
                                    // Convert city name to lowercase without spaces
                                    $cityName = strtolower(str_replace(' ', '', $city['name']));
                                    ?>
                                    <option value="<?php echo $city['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo $city['name']; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="w3-col s4" style="display:flex;text-align: right; align-items: center; justify-content:end;">
                <span class="user-info">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?>!</span>
                <img src=<?php echo "/sso-v1/assets/uploads/" . $profile_image; ?> style="height: 35px" class="w3-circle" />
                <div class="w3-dropdown-hover">
                    <button class="w3-button">
                        <i class="fa-solid fa-grip-vertical"></i>
                    </button>
                    <div class="w3-dropdown-content w3-bar-block w3-border">
                        <a href="./profile.php" class="w3-bar-item w3-button">My Account</a>
                        <a href="./cities.php" class="w3-bar-item w3-button">Manage City</a>
                        <a href="../" class="w3-bar-item w3-button">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="content-area">
        <!-- sidebar toggle -->
        <script>
            function w3_sidebar_toggle() {
                let sidebar = document.getElementById("sidebar");
                let mainContent = document.querySelector("main");
                let overlay = document.getElementById("overlay");
                let width = sidebar.getBoundingClientRect().width;

                sidebar.style.transition = "0.5s";

                if (sidebar.style.left == "0px") {
                    sidebar.style.left = "-" + width + "px";
                    mainContent.style.marginLeft = "0";
                    overlay.style.display = "none";
                    setTimeout(function() {
                        w3_sidebar_close_all();
                    }, 500);
                } else {
                    sidebar.style.left = "0px";
                    mainContent.style.marginLeft = width + "px";
                    overlay.style.display = "block";
                }
            }
        </script>