<?php
define('PAGE_TITLE', 'Cities');

include('../includes/mainheader.php');

include('../includes/sidebar.php');

// As we import mainheader, there is already $cities.

// Function to delete city from database
function deleteCity($connect, $cityId)
{
    $query = "DELETE FROM cities WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, 'i', $cityId);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// Handle city deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $cityId = $_POST['id'];

    // Perform delete operation
    if (deleteCity($connect, $cityId)) {
        $_SESSION['success'] = "City deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete city.";
        // Redirect to refresh page after deletion (to prevent form resubmission on refresh)
        header('Location: cities.php');
        exit;
    }
}
?>

<div class="w3-container">
    <h1 class="w3-text-teal">Cities</h1>

    <!-- Add City Button -->
    <button class="w3-button w3-teal w3-margin-bottom w3-right" onclick="location.href='./add_city.php'">
        <i class="fas fa-plus"></i>
        Add City
    </button>

    <!-- Cities Table -->
    <table class="w3-table w3-bordered w3-striped w3-hoverable">
        <thead>
            <tr class="w3-light-grey w3-center">
                <th>ID</th>
                <th>City Name</th>
                <th>City Logo</th>
                <th>Status</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city) : ?>
                <tr>
                    <td><?php echo $city['id']; ?></td>
                    <td><?php echo $city['name']; ?></td>
                    <td><img src='../assets/city_logo/<?php echo $city['logo']; ?>' alt='<?php echo $city['name']; ?> Logo' class='w3-image w3-start' style='width: 20px; height: auto;'></td>
                    <td><?php echo $city['status']; ?></td>
                    <td>
                        <form action='./update_city.php' method='GET' style='display:inline;'>
                            <input type='hidden' name='id' value='<?php echo $city['id']; ?>'>
                            <button type='submit' class='w3-button w3-blue w3-small' name="deleteCity"><i class='fas fa-edit'></i> Update</button>
                        </form>
                        <button class='w3-button w3-red w3-small' onclick='openDeleteModal(<?php echo $city["id"]; ?>)'><i class='fas fa-trash'></i> Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="w3-modal">
    <div class="w3-modal-content w3-animate-top w3-card-8">
        <header class="w3-container w3-blue">
            <span onclick="document.getElementById('deleteModal').style.display='none'" class="w3-button w3-red w3-display-topright">&times;</span>
            <h2>Delete City</h2>
        </header>
        <div class="w3-container w3-padding-16">
            <p>Are you sure you want to delete this city?</p>
        </div>
        <footer class="w3-container w3-blue">
            <form action='' method='POST' id='deleteForm' class="w3-right w3-padding">
                <input type='hidden' name='id' id='deleteCityId'>
                <button type='submit' class='w3-button w3-red'>Delete</button>
                <button type='button' class='w3-button w3-light-grey' onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
            </form>
        </footer>
    </div>
</div>

<!-- Script for Modal -->
<script>
    function openDeleteModal(cityId) {
        document.getElementById('deleteCityId').value = cityId;
        document.getElementById('deleteModal').style.display = 'block';
    }
</script>