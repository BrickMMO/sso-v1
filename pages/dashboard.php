<?php

define('PAGE_TITLE', 'Dashboard');

include('../includes/mainheader.php');
?>

<h1> Dashboard </h1>

<?php
// Determine the selected city ID from the session or POST data
if (!empty($_POST['city'])) {
    $selected_city_id = $_POST['city'];
    $_SESSION['selected_city'] = $selected_city_id; // Store selected city in session for future use
} elseif (isset($_SESSION['selected_city'])) {
    $selected_city_id = $_SESSION['selected_city'];
} else {
    // Default to the first city in the database if none is selected
    $query = "SELECT id FROM cities WHERE status = 'Active' LIMIT 1";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    $selected_city_id = $row['id'];
    $_SESSION['selected_city'] = $selected_city_id;
}

// Display city name based on the selected city ID
$query = "SELECT name FROM cities WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $selected_city_id);
$stmt->execute();
$stmt->bind_result($city_name);
$stmt->fetch();
$stmt->close();

if ($city_name) {
    echo "<h2>$city_name</h2>";
} else {
    echo "<h2>Error: City not found.</h2>";
}
?>