<?php
define('PAGE_TITLE', 'Add City');

include('../includes/mainheader.php');

include('../includes/sidebar.php');

// Server-side validation
$errors = [];
$name = $status = $logo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $status = trim($_POST['status']);
    $logo = '';

    // Validate city name
    if (empty($name)) {
        $errors[] = "City name is required.";
    }

    // Validate status
    if (empty($status)) {
        $errors[] = "Status is required.";
    } elseif (!in_array($status, ['Active', 'Inactive'])) {
        $errors[] = "Invalid status value.";
    }

    // Validate and handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'svg'];
        $file_extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and SVG are allowed.";
        } else {
            $sanitize_city_name = strtolower(str_replace(' ', '', $name));
            $logo_name = $sanitize_city_name . '.' . $file_extension;
            $upload_dir = '../assets/city_logo/';
            $logo_path = $upload_dir . $logo_name;

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path)) {
                $logo = $logo_name;
            } else {
                $errors[] = "Failed to upload logo.";
            }
        }
    } else {
        $errors[] = "City logo is required.";
    }

    // If no errors, insert data into the database
    if (empty($errors)) {
        $query = "INSERT INTO cities (name, status, logo) VALUES (?, ?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param('sss', $name, $status, $logo);

        if ($stmt->execute()) {
            $_SESSION['success'] = "City added successfully.";
        } else {
            $errors[] = "Failed to add city: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>
<div class="w3-container">
    <h1 class="w3-text-teal">Add New City</h1>

    <?php
    if (!empty($errors)) {
        echo '<div class="w3-panel w3-red">';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '</div>';
    }
    ?>

    <form action="add_city.php" method="POST" enctype="multipart/form-data" onsubmit="return validateCityForm();" novalidate>
        <div class="w3-section">
            <label for="name" class="w3-text-gray">City Name</label>
            <input class="w3-input" type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
            <span id="name-error" class="w3-text-red"></span>
        </div>

        <div class="w3-section">
            <label for="status" class="w3-text-gray">Status</label>
            <select class="w3-select" name="status" id="status">
                <option value="" disabled selected>Choose status</option>
                <option value="Active" <?php if ($status === 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($status === 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
            <span id="status-error" class="w3-text-red"></span>
        </div>

        <div class="w3-section">
            <label for="logo" class="w3-text-gray">City Logo</label>
            <input class="w3-input" type="file" name="logo" id="logo" accept=".jpg, .jpeg, .png, .svg">
            <span id="logo-error" class="w3-text-red"></span>
        </div>

        <button type="submit" class="w3-button w3-blue w3-margin-top w3-right" name="submit">Save</button>
    </form>
</div>

<!-- Client Side Validation -->

<script>
    function validateCityForm() {
        let errors = 0;

        // City Name Validation
        let name = document.getElementById('name');
        let nameError = document.getElementById('name-error');
        nameError.textContent = '';
        if (name.value.trim() === '') {
            nameError.textContent = 'City name is required.';
            errors++;
        }

        // Status Validation
        let status = document.getElementById('status');
        let statusError = document.getElementById('status-error');
        statusError.textContent = '';
        if (status.value === '') {
            statusError.textContent = 'Status is required.';
            errors++;
        }

        // Logo Validation
        let logo = document.getElementById('logo');
        let logoError = document.getElementById('logo-error');
        logoError.textContent = '';
        if (logo.files.length === 0) {
            logoError.textContent = 'City logo is required.';
            errors++;
        } else {
            let allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
            let fileExtension = logo.files[0].name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                logoError.textContent = 'Invalid file type. Only JPG, JPEG, PNG, and SVG are allowed.';
                errors++;
            }
        }

        return errors === 0;
    }
</script>