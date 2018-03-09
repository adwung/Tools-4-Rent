<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Drop-off Reservation</title>
<h1>Drop-off Reservation</h1>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
session_start();
// Handle dropoff by updating the reservation table entry with dropoffClerkENO
if (isset($_POST['rIDToDropoff'])){
	$rIDToDropoff = stripslashes($_REQUEST['rIDToDropoff']);
	$rIDToDropoff = mysqli_real_escape_string($con,$rIDToDropoff);
	$username = $_SESSION['username'];
	
	$query = "SELECT * FROM `clerk` WHERE username='$username'";
	$result = mysqli_query($con,$query) or die(mysql_error());
	$row = mysqli_fetch_row($result);
	$empNO = $row[0];
	
	$query_rIDToDropoff = "UPDATE reservation SET dropoffClerkENO='$empNO' WHERE rNO='$rIDToDropoff'";
	
	if (!mysqli_query($con, $query_rIDToDropoff)) {
		$dropoffResult= "<div class='form'>
			<h3>The entered reservation ID for dropoff doesnt have any record in table. Please try with another ID.</h3>";
	} else {
		$dropoffResult= "Reservation record updated successfully with dropoffClerkENO.";
		
		$query_toolsupdate="SELECT * FROM `holds` WHERE rNO='$rIDToDropoff'";
		$result_toolsupdate = mysqli_query($con,$query_toolsupdate) or die(mysql_error());
													
		while($row_toolsupdate = $result_toolsupdate->fetch_assoc()) {
			$status_date = date("Y/m/d");
			$query_statusUpdate = "UPDATE `tool` SET status='available', statusdate='".$status_date.
				"' WHERE toolID=".$row_toolsupdate["toolID"];
			
			$result_statusUpdate = mysqli_query($con,$query_statusUpdate) or die(mysql_error());
		}
		
		$query = "SELECT * FROM `reservation` WHERE rNO = '$rIDToDropoff'";
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
		
	    $dropoffResult .= "<br><br>";
		$dropoffResult .= "Reservation ID: #".$row['rNO']."<br>";
		$dropoffResult .= "Customer Name: ".$row_user['first_name']." ".$row_user['middle_name']." ".$row_user['last_name']."<br>";
		$dropoffResult .= "Total Deposit Price: ".$row['deposit_price']."<br>";
		$dropoffResult .= "Total Rental Price: ".$row['rental_price']."<br>";
	}
}

// get all reservations to display for dropoff
$query = "SELECT * FROM `Reservation` WHERE dropoffClerkENO IS NULL AND pickupClerkENO IS NOT NULL";
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
														$toolID = $row_hold["toolID"];;
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
		<input type="text" name="rIDToDropoff" placeholder="Enter Reservation ID" />
		<input name="submit" type="submit" value="Drop-Off" />
</form>

<?php if(isset($dropoffResult)) { ?>
<div id="dropoffConfirmation"><?php echo $dropoffResult; ?></div>
<?php } ?>

<br/>Click here to go back to <a href='menu.php'>menu</a></div>
<script>
function GetReservationDetails(rcID, customerName, depositPrice, tools, rentalPrice) {
	alert("Customer Name: "+customerName+"\nTotal Deposit Price: "+depositPrice+"\nTotal Rental Price: "+rentalPrice+"\nTools: "+tools);
}
</script>