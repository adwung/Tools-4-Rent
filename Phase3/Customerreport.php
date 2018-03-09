<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Customer Report</title>


<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php

require('db.php');
session_start();

// get all reservations to display for pickup


$query = "SELECT rc.rcID, first_name, middle_name, last_name, email, primary_phone, home_phone_number, work_phone_number, cell_phone_number, User.username
			FROM RentalCustomer AS rc
			INNER JOIN User ON User.username = rc.username
			INNER JOIN CustomerPhoneNumber AS cp ON cp.username = rc.username";
							
$result = mysqli_query($con,$query) or die(mysql_error());
$rows = mysqli_num_rows($result);

?>

<table class="reservations">
        <thead>
            <tr class="header">
                <td><b>Customer ID</b></td>
				<td><b>View Profile?</b></td>
                <td><b>First Name</b></td>
				<td><b>Middle name</b></td>
				<td><b>Last Name</b></td>
				<td><b>Email</b></td>
				<td><b>Phone</b></td>
				<td><b>Total # Reservations</b></td>
				<td><b>Total # Tools Rented</b></td>
				
            </tr>
        </thead>
        <tbody>
       <?php
            while($row = mysqli_fetch_array($result)) {
												$username = $row["username"];
            ?>
         
					                            <td><?php echo $row['rcID']?></td>
                                                
												<td class="View Profile"><a onclick="location.href='viewProfile.php?un=<?php echo $username; ?>'">View Profile</a></div>
												
												<td><?php echo $row['first_name']?></td>
												<td><?php echo $row['middle_name']?></td> 
												<td><?php echo $row['last_name']?></td>
												<td><?php echo $row['email']?></td>
										
												<td><?php if ($row['primary_phone'] == 'home'){
                                                  echo $row['home_phone_number'] ;
												} if ($row['primary_phone'] == 'cell'){
                                                  echo $row['cell_phone_number'] ;
                                                      } if ($row['primary_phone'] == 'work'){
                                                  echo $row['work_phone_number'] ;
                                                      }?></td>

												<td><?php 
												    $rcID = $row['rcID'];
													$query_reservation="SELECT count(*) FROM reservation WHERE rcID='$rcID' and MONTH(start_date) = MONTH(CURRENT_DATE()- interval 1 Month)";
													$result_reservation = mysqli_query($con,$query_reservation) or die(mysql_error());
													$a = mysqli_fetch_row($result_reservation);
													echo $a[0];?></td>
			
												<td><?php 
												    $rcID = $row['rcID'];
													$query_holds="SELECT count(*) FROM reservation inner join holds on reservation.rNO = holds.rNO WHERE rcID='$rcID' and MONTH(start_date) = MONTH(CURRENT_DATE()- interval 1 Month)";
													$result_holds = mysqli_query($con,$query_holds) or die(mysql_error());
													$b = mysqli_fetch_row($result_holds);
													echo $b[0];?></td>			
													
												
                </tr>

            <?php
            }
            ?>
        </tbody>
</table>


<form action='Reports.php'>
    <input type="submit" value="Back to Report Menu" /> <a href='Customerreport.php'>Reload Results</a></div>
</form>

