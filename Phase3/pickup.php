<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Pick-up Reservation</title>
<h1>Pick-up Reservation</h1>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
session_start();
// Handle pickup by updating the reservation table entry with pickupClerkENO
if (isset($_POST['rIDToPickup'])){
	$rIDToPickup = stripslashes($_REQUEST['rIDToPickup']);
	$rIDToPickup = mysqli_real_escape_string($con,$rIDToPickup);
	$username = $_SESSION['username'];
	
	$query = "SELECT * FROM `clerk` WHERE username='$username'";
	$result = mysqli_query($con,$query) or die(mysql_error());
	$row = mysqli_fetch_row($result);
	$empNO = $row[0];
	
	$query_rIDToPickup = "UPDATE reservation SET pickupClerkENO='$empNO' WHERE rNO='$rIDToPickup'";
	
	if (!mysqli_query($con, $query_rIDToPickup)) {
		$pickupResult = "<div class='form'>
			<h3>The entered reservation ID for pickup doesnt have any record in table. Please try with another ID.</h3>";
	} else {
		$pickupResult = "<br>Reservation record updated successfully with pickupClerkENO.";
		
		$query_toolsupdate="SELECT * FROM `holds` WHERE rNO='$rIDToPickup'";
		$result_toolsupdate = mysqli_query($con,$query_toolsupdate) or die(mysql_error());
													
		while($row_toolsupdate = $result_toolsupdate->fetch_assoc()) {
			$status_date = date("Y/m/d");
			$query_statusUpdate = "UPDATE `tool` SET status='rented', statusdate='".$status_date.
				"' WHERE toolID=".$row_toolsupdate["toolID"];
			
			$result_statusUpdate = mysqli_query($con,$query_statusUpdate) or die(mysql_error());
		}
		
		$query = "SELECT * FROM `reservation` WHERE rNO = '$rIDToPickup'";
		$result = mysqli_query($con,$query) or die(mysql_error());
	    $row = mysqli_fetch_array($result);
		
		$rcID = $row["rcID"];
		$query = "SELECT * FROM `rentalcustomer` WHERE rcID = '$rcID'";
		$result = mysqli_query($con,$query) or die(mysql_error());
	    $row_rc = mysqli_fetch_array($result);
		
		$username = $row_rc["username"];
		
		$query = "SELECT * FROM `user` WHERE username = '$username'";
		$result = mysqli_query($con,$query) or die(mysql_error());
	    $row_user = mysqli_fetch_array($result);
		
	    $pickupResult .= "<br><br>";
		$pickupResult .= "Reservation ID: #".$row['rNO']."<br>";
		$pickupResult .= "Customer Name: ".$row_user['first_name']." ".$row_user['middle_name']." ".$row_user['last_name']."<br>";
		$pickupResult .= "Total Deposit Price: ".$row['deposit_price']."<br>";
		$pickupResult .= "Total Rental Price: ".$row['rental_price']."<br>";
	}
}

// get all reservations to display for pickup
$query = "SELECT * FROM `Reservation` WHERE pickupClerkENO IS NULL";
$result = mysqli_query($con,$query) or die(mysql_error());
$rows = mysqli_num_rows($result);
?>
<table class="reservations">
        <thead>
            <tr class="header">
                <td>Reservation ID</td>
                <td>Customer</td>
				<td>CustomerID</td>
				<td>Start Date</td>
				<td>End Date</td>
				
            </tr>
        </thead>
        <tbody>
        <?php
            while($row = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <td class="rcID"><a onclick="GetReservationDetails(
												// Pass the following parameters to show in popup
					                            <?php echo $row['rNO']; ?>, 
												// Get the customer full name
												'<?php
													$rcID=$row['rcID'];
													$query_rc="SELECT * FROM `rentalcustomer` WHERE rcID='$rcID'";
													$result_rc = mysqli_query($con,$query_rc) or die(mysql_error());
													$row_rc = mysqli_fetch_row($result_rc);
													$username = $row_rc[1];
													$query_user="SELECT * FROM `user` WHERE username='$username'";
													$result_user = mysqli_query($con,$query_user) or die(mysql_error());
													$row_user = mysqli_fetch_row($result_user);
													echo $row_user[3].' '.$row_user[4].' '.$row_user[5];
												?>',
												<?php echo $row['deposit_price']; ?>,
												// Get the tool list in reservation
												'<?php 
												    $rNO = $row['rNO'];
													$query_holds="SELECT * FROM `holds` WHERE rNO='$rNO'";
													$result_holds = mysqli_query($con,$query_holds) or die(mysql_error());
													$tools = "";
													while($row_hold = $result_holds->fetch_assoc()) {
														$toolID = $row_hold["toolID"];
														$query_tool="SELECT * FROM `tool` WHERE toolID='$toolID'";
														$result_tool = mysqli_query($con,$query_tool) or die(mysql_error());
														$row_tool = mysqli_fetch_array($result_tool);
														$tools = $tools." ".$row_tool["short_description"];
													}
													echo $tools;
												?>',
												<?php echo $row['rental_price']; ?>);">
											<?php echo $row['rNO']?>
									</a>
					</td>
                    <td><?php 
						      // Get customer first name
					          $rcID=$row['rcID'];
                              $query_rc="SELECT * FROM `rentalcustomer` WHERE rcID='$rcID'";
                              $result_rc = mysqli_query($con,$query_rc) or die(mysql_error());
							  $row_rc = mysqli_fetch_row($result_rc);
							  $username = $row_rc[1];
							  $query_user="SELECT * FROM `user` WHERE username='$username'";
							  $result_user = mysqli_query($con,$query_user) or die(mysql_error());
							  $row_user = mysqli_fetch_row($result_user);
							  echo $row_user[3];
						?>
					</td>
					<td><?php echo $row['rcID']?></td>
					<td><?php echo $row['start_date']?></td>
					<td><?php echo $row['end_date']?></td>
                </tr>

            <?php
            }
            ?>
        </tbody>
</table>

<form action="" method="post" name="login">
		<input type="text" name="rIDToPickup" placeholder="Enter Reservation ID" />
		<input name="submit" type="submit" value="Pick Up" />
</form>

<?php if(isset($pickupResult)) { ?>
<div id="pickupConfirmation"><?php echo $pickupResult; ?></div>
<?php } ?>

<br/>Click here to go back to <a href='menu.php'>menu</a></div>
<script>
function GetReservationDetails(rcID, customerName, depositPrice, tools, rentalPrice) {
	alert("Customer Name: "+customerName+"\nTotal Deposit Price: "+depositPrice+"\nTotal Rental Price: "+rentalPrice+"\nTools: "+tools);
}
</script>