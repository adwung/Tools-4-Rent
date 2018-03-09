<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Menu</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
session_start();
?>

<div id="nav">
<ul class="menu">

<?php 
if ($_SESSION["user_type"] == 'customer') { ?>
<em><a href="viewProfile.php">View Profile</a></em>
<em><a href="make_reservation.php">Make Reservation</a></em>
<em><a href="checktool.php">Check Tool Availability</a></em>
<?php } else { ?>
<em><a href="pickup.php">Pick-up Reservation</a></em>
<em><a href="dropoff.php">Drop-off Reservation</a></em>
<em><a href="addtool.php">Add New Tool</a></em>
<em><a href="Reports.php">Reports</a></em>
<?php } ?>

</ul>

<br/>Click here to <a href='login.php'>logout</a>
</body>
</html>