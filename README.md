# SSO V1

The single sign-on application used for all BrickMMO assets.

Change MAMP to listen on two ports:

```
Listen 8888
Listen 7777
```

To get this working setup some local domains in your /etc/hosts file:

```
127.0.0.1       local.account.brickmmo.com
127.0.0.1       local.console.brickmmo.com
```

Locate the default directory features and revise them to look like this:

```
<Directory />
    Options Indexes FollowSymLinks
    AllowOverride All
</Directory>
```

And virtual servers in yur MAMP configuraiton:

```
# Virtual hosts
#Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf
NameVirtualHost *:7777

<VirtualHost *:7777>
DocumentRoot "/Users/thomasa/Desktop/BrickMMO/sso-v1"
ServerName local.account.brickmmo.com
</VirtualHost>

<VirtualHost *:7777>
DocumentRoot "/Users/thomasa/Desktop/BrickMMO/console-v3"
ServerName local.console.brickmmo.com
</VirtualHost>
```

---

## Project Stack

- **Backend**: PHP ![PHP](https://img.icons8.com/color/48/000000/php.png)
- **Frontend**: HTML ![HTML](https://img.icons8.com/color/48/000000/html-5.png), JavaScript ![JavaScript](https://img.icons8.com/color/48/000000/javascript.png)
- **Server-Side Cookie Management**: Node.js ![Node.js](https://img.icons8.com/color/48/000000/nodejs.png)
- **Database**: MySQL ![MySQL](https://img.icons8.com/color/48/000000/mysql.png)

---

## Tasks

- Database Setup ✅
- Create Sign In ✅
- Create Sign Up ✅
- Validation On Sign In ✅
- Validation On Sign Up ✅
- Dashboard ✅
- My Account ✅
- Forgot Password ✅
- Favicon Icon ✅
- Email Authentication Using SendGrid ✅
- City CRUD ✅
- Cross-Domain Cookie Node & PHP ✅
- Header for every cross-domain website ✅
- Add avatar image to the header ✅
- Redirect between cross-domain websites ✅

---

## Code Snippet in PHP For Cross-domain Website

```php
<?php
// ----- 1. Start the session to manage user login state
session_start();

// ----- 2. Define the redirect URL where the user will be redirected after login
$redirect_url = "https://milin-humber-brickmmo-parts.great-site.net";

// ----- 3. Define the login URL with the redirect URL parameter
$login_url = "https://milin-humber-brickmmo.great-site.net/?redirect_url=$redirect_url";

// ----- 4. Include Composer's autoload file to load the Firebase PHP-JWT library
// -----> On Terminal First Install JWT
// composer require firebase/php-jwt
require_once __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
?>

<!-- 6. Include necessary CSS and JavaScript files in the head tag -->
<head>
    <!-- Include existing CSS and JS code as required -->

    <!-- W3CSS for styling -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Custom CSS for the navigation bar -->
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

    <!-- FontAwesome Script -->
    <script src="https://kit.fontawesome.com/ff97c9dde6.js" crossorigin="anonymous"></script>
</head>

<!-- 7. Set up the main website body with the custom header -->
<body>
    <!-- Login Button (include in your existing header) -->
    <!-- <?php echo '<a href="' . $login_url . '" class="btn btn-primary">Login</a>'; ?> -->

    <?php
    // Check if 'sub' parameter is set in GET request or SESSION
    if (isset($_GET['sub']) || isset($_SESSION['sub'])) {
        // If 'sub' parameter is not set in SESSION, set it from GET request
        if (!isset($_SESSION['sub'])) {
            $_SESSION['sub'] = $_GET['sub'];
        }

        // Retrieve the JWT token from SESSION
        $jwt_token = $_SESSION['sub'];
        // Define a secure random string as the secret key for JWT
        $secret_key = "t22GcFbg2NdVd10zIcPRWmJj/MN9TMnyhggtKhzFPU0=";

        try {
            // Decode the JWT token to extract user data
            $decoded = JWT::decode($jwt_token, new Key($secret_key, 'HS256'));

            // Set a cookie with the JWT token, valid for the sub-site domain
            setcookie("jwt_sub", $jwt_token, $decoded->exp, "/", "your-sub-site-name-goes-here", true, true);

            // Store user data in the session
            $_SESSION['user_id'] = $decoded->data->user_id;
            $_SESSION['name'] = $decoded->data->name;
            $_SESSION['email'] = $decoded->data->email;
            $_SESSION['avatar'] = $decoded->data->avatar;
    ?>
            <!-- Navigation bar with user info -->
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
                        <img src="<?php echo "https://milin-humber-brickmmo.great-site.net/assets/uploads/" . $_SESSION['avatar']; ?>" style="height: 35px" class="w3-circle" />
                        <div class="w3-dropdown-hover">
                            <button class="w3-button">
                                <i class="fa-solid fa-grip-vertical fa-padding-right"></i>
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
            // If decoding fails, log out the user
            header("Location: ?logout");
            exit();
        }
    }
    ?>

    <?php
    // Define the logout function
    function logout()
    {
        // Clear all session variables
        $_SESSION = array();

        // Delete the session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        // Destroy the session
        session_destroy();

        // Clear the JWT cookie for the sub-site domain
        setcookie("jwt_sub", "", time() - 3600, "/", "milin-humber-brickmmo-parts.great-site.net", true, true);
    }

    // Check if logout is requested
    if (isset($_GET['logout'])) {
        // Call the logout function
        logout();

        // Redirect to the homepage
        header("Location: $redirect_url");
        exit();
    }
    ?>
</body>
</html>
```

## Live Demo

- [SSO](https://milin-humber-brickmmo.great-site.net)
- [BrickMMO Parts](https://milin-humber-brickmmo-parts.great-site.net)

---

## Repository Resources

- [BrickMMO](https://brickmmo.com)
- [CodeAdam](https://codeadam.ca)
- [BrickMMO SSO](https://sso.brickmmo.com/)

[![BrickMMO Logo](https://brickmmo.com/images/brickmmo-logo-horizontal.jpg)](https://brickmmo.com)

---
