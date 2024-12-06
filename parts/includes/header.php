<?php
session_start();
$redirect_url = 'https://milin-humber-brickmmo-parts.great-site.net';
$login_url = "https://milin-humber-brickmmo.great-site.net/?redirect_url=$redirect_url";

require_once __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Function to logout (clear session and cookies)
function logout()
{
    // Clear all session variables
    $_SESSION = array();

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Clear the JWT cookie for the sub-site
    setcookie("jwt_sub", "", time() - 3600, "/", "milin-humber-brickmmo-parts.great-site.net", true, true);
}

// Check if logout is requested
if (isset($_GET['logout'])) {
    logout();

    // homepage
    header("Location: $redirect_url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= PAGE_TITLE ?> | BrickMMO Parts</title>

    <!-- Favicon Icon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <link href="./assets/css/styles.css" type="text/css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- == Additional Code For Header == -->
    <!-- W3 School CSS -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />

    <!-- Font Awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Inline CSS For Custom Header -->
    <style>
        nav.w3-bar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: visible;
        }

        nav.w3-bar .w3-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        nav.w3-bar .user-info {
            margin-right: 10px;
        }

        .w3-dropdown-hover:hover .w3-dropdown-content {
            display: block;
            margin-left: -119px;
        }
    </style>

    <!-- Script For Fontawsome Icons -->
    <script src="https://kit.fontawesome.com/ff97c9dde6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    // Additional Code
    if (isset($_GET['sub']) || isset($_SESSION['sub'])) {
        if (!isset($_SESSION['sub'])) {
            $_SESSION['sub'] = $_GET['sub'];
        }
        $jwt_token = $_SESSION['sub'];
        $secret_key = "t22GcFbg2NdVd10zIcPRWmJj/MN9TMnyhggtKhzFPU0="; // secure random string
        try {
            // Decode JWT to get user data
            $decoded = JWT::decode($jwt_token, new Key($secret_key, 'HS256'));
            setcookie("jwt_sub", $jwt_token, $decoded->exp, "/", "milin-humber-brickmmo-parts.great-site.net", true, true);

            $_SESSION['user_id'] = $decoded->data->user_id;
            $_SESSION['name'] = $decoded->data->name;
            $_SESSION['email'] = $decoded->data->email;
            $_SESSION['avatar'] = $decoded->data->avatar;
    ?>
            <nav class="w3-bar w3-border-bottom w3-padding w3-white w3-top" style="position: sticky; z-index: 102;">
                <div class="w3-row">
                    <div class="w3-col s8">
                        <div style="display: flex; align-items: center;">
                            <a href="https://milin-humber-brickmmo.great-site.net/pages/dashboard.php" class="w3-margin-left">
                                <img src="https://milin-humber-brickmmo.great-site.net/assets/images/brickmmo_logo.png" style="height: 35px" />
                            </a>
                        </div>
                    </div>

                    <div class="w3-col s4" style="display:flex;text-align: right; align-items: center; justify-content:end;">
                        <span class="user-info">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; ?>!</span>
                        <img src=<?php echo "https://milin-humber-brickmmo.great-site.net/assets/uploads/" . $_SESSION['avatar']; ?> style="height: 35px" class="w3-circle" />
                        <div class="w3-dropdown-hover">
                            <button class="w3-button">
                                <i class="fa-solid fa-grip-vertical"></i>
                            </button>
                            <div class="w3-dropdown-content w3-bar-block w3-border">
                                <a href="https://milin-humber-brickmmo.great-site.net/pages/profile.php" class="w3-bar-item w3-button">My Account</a>
                                <a href="?logout" class="w3-bar-item w3-button">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
    <?php
        } catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
            header("Location: ?logout");
            exit();
        }
    }
    ?>
    <nav class="navbar parts-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="./assets/img/logo.png" alt="BrickMMO Parts Logo" class="img-fluid parts_img">
            </a>

            <div class="d-flex gap-4">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn parts-btn" type="submit">
                        Search
                    </button>
                </form>

                <?php
                if (isset($_GET['sub']) || isset($_SESSION['sub'])) {
                } else {
                    // Show login button if user is not logged in
                    echo '<a href="' . $login_url . '" class="btn btn-primary">Login</a>';
                }
                ?>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>