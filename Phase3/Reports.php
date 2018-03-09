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


<em><a href="Clerkreport.php">Clerk Report</a></em><br>
<em><a href="Customerreport.php">Customer Report</a></em></br>
<em><a href="Inventoryreport.php">Inventory Report</a></em></br>
</ul>

<br/>Click here to go back to <a href='menu.php'>menu</a></div>
</body>
</html>