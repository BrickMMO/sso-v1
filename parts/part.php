<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');
define('PAGE_TITLE', 'Parts');
include('includes/header.php');
?>

<?php

/*
Fetch the selected part
*/
$query = 'SELECT parts.*
    FROM parts
    WHERE part_num = "' . $_GET['id'] . '"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$part = mysqli_fetch_assoc($result);

?>


<h1>Part: <?= $part['name'] ?></h1>

<?php

/*
    Fetch all the colors the selected part comes in
    */
$query = 'SELECT colors.*
        FROM colors
        LEFT JOIN elements 
        ON color_id = id
        WHERE part_num = "' . $part['part_num'] . '"
        GROUP BY colors.id
        ORDER BY name';
$result = mysqli_query($connect, $query);

?>

<h2>Colours</h2>

<?php while ($color = mysqli_fetch_assoc($result)) : ?>

    <hr>

    <h3>Color: <?= $color['name'] ?></h3>

    Full Color Data:
    <pre><?php print_r($color); ?></pre>

<?php endwhile; ?>


<hr>
<hr>

<?php

/*
    Fetch all the sets the selected part comes with
    */
$query = 'SELECT sets.*
        FROM sets
        LEFT JOIN inventories 
        ON inventories.set_num = sets.set_num
        LEFT JOIN inventory_parts
        ON inventory_parts.inventory_id = inventories.id
        WHERE part_num = "' . $part['part_num'] . '"
        GROUP BY sets.set_num
        ORDER BY name';
$result = mysqli_query($connect, $query);

?>

<h2>Sets</h2>

<?php while ($set = mysqli_fetch_assoc($result)) : ?>

    <hr>

    <h3>Set: <?= $set['name'] ?></h3>

    <a href="set.php?id=<?= $set['set_num'] ?>">Part Details</a>

    <br><br>

    Full Set Data:
    <pre><?php print_r($set); ?></pre>

<?php endwhile; ?>
<?php include('includes/footer.php'); ?>