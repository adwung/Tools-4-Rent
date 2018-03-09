<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<title>Clerk Report</title>

<!--  The list of clerks where their total pickups and dropoffs are shown for the past month -->

<link rel="stylesheet" href="css/style.css" />
</head>
<body>


<?php
require('db.php');
session_start();

// get all reservations to display for pickup

$query = "SELECT empNO, first_name, middle_name, last_name, email, hire_date FROM clerk inner JOIN user on clerk.username = user.username";
							
$result = mysqli_query($con,$query) or die(mysql_error());
$rows = mysqli_num_rows($result);

?>

<table class="reservations">
        <thead>
            <tr class="header">
			
                <td><b>Clerk ID</b></td>
                <td><b>First Name</b></td>
				<td><b>Middle name</b></td>
				<td><b>Last Name</b></td>
				<td><b>Email</b></td>
				<td><b>Hire Date</b></td>
				<td><b>Number of Pickups</b></td>
				<td><b>Number of Dropoffs</b></td>
				<td><b>Combined Total</b></td>

            </tr>
        </thead>
        <tbody>
       <?php
            while($row = mysqli_fetch_array($result)) {
            ?>
					                            <td><?php echo $row['empNO']?></td> 								
												<td><?php echo $row['first_name']?></td>
												<td><?php echo $row['middle_name']?></td> 
												<td><?php echo $row['last_name']?></td>
												<td><?php echo $row['email']?></td>
												<td><?php echo $row['hire_date']?></td>

												<td><?php 
												    $empNO = $row['empNO'];
													$query_reservation="SELECT count(*) FROM reservation WHERE pickupClerkENO ='$empNO' and MONTH(start_date) = MONTH(CURRENT_DATE()- interval 1 Month)";
													$result_reservation = mysqli_query($con,$query_reservation) or die(mysql_error());
													$a = mysqli_fetch_row($result_reservation);
													echo $a[0];?></td>
			
												<td><?php 
												    $empNO = $row['empNO'];
													$query_reservation1="SELECT count(*) FROM reservation WHERE dropoffClerkENO ='$empNO' and MONTH(start_date) = MONTH(CURRENT_DATE()- interval 1 Month)";
													$result_reservation1 = mysqli_query($con,$query_reservation1) or die(mysql_error());
													$a1 = mysqli_fetch_row($result_reservation1);
													echo $a1[0];?></td>
												<td><?php echo ($a1[0]+$a[0])?></td> 	
													
												
                </tr>

            <?php
            }
            ?>
        </tbody>
</table>


<form action='Reports.php'>
    <input type="submit" value="Back to Report Menu" /> <a href='Clerkreport.php'>Reload Results</a></div>
</form>

