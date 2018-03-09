<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Tools4Rent Profile</title>
<link rel="stylesheet" href="css/style.css"/>
</head>
	
<?php
	require('db.php');
	session_start();
	
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}
	
	if(isset($_GET['un'])) {
		$username = $_GET['un'];
	}
	else {
		$username = $_SESSION['username'];
	}
	
	$query = "SELECT User.username, User.email, User.first_name, User.middle_name, User.last_name, cpn.home_phone_number, CONCAT(rc.street, ' ', rc.city, ' ', rc.state, ' ', rc.zip) AS full_address " .
		 "FROM User 
		 	INNER JOIN RentalCustomer AS rc
				ON User.username=rc.username 
			INNER JOIN CustomerPhoneNumber AS cpn 
				ON cpn.username=User.username " .
		 "WHERE User.username='$username'";
	
	$result = mysqli_query($con,$query) or die(mysql_error());	
	$row = mysqli_fetch_array($result)

?>
<body>
    <div class="center_content">
        <div class="center_left">
            <div class="features">   
            
                <div class="profile_section">
                    <div class="subtitle"><h1>View Profile: <?php print $row['first_name'] . ' ' . $row['last_name']; ?></h1></div>   
                    <table>
                        <tr>
                            <td class="item_label">E-mail:</td>
                            <td>
                                <?php print $row['email'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Full Name:</td>
                            <td>
                                <?php print $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Phone:</td>
                            <td>
                                <?php
								if ($row['cpn.primary_phone']='home') {
									print $row['home_phone_number'];
								} elseif ($row['cpn.primary_phone']='work') {
									print $row['work_phone_number'];
								} else {
									print $row['cell_phone_number'];
								}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Address</td>
                            <td>
                                <?php print $row['full_address'];?>
                            </td>
                        </tr>
                    </table>						
                </div>
				<br></br>
                <div class="profile_section">
                  <div class="subtitle"><h2>Reservations</h2></div>
                    <table width="976" height="61" border="1">
                        <tr>
                            <td class="heading">Reservation ID</td>
                            <td class="heading">Tools</td>
                            <td class="heading">Start Date</td>
                            <td class="heading">End Date</td>
                            <td class="heading">Pick-up Clerk</td>
                            <td class="heading">Drop-off Clerk</td>
                            <td class="heading">Number of Days</td>
                            <td class="heading">Total Deposit Price</td>
                            <td class="heading">Total Rental Price</td>
                        </tr>							

                        <?php
									    $query = "SELECT r.rNO, r.start_date, r.end_date, r.pickupClerkENO, r.dropoffClerkENO, r.deposit_price, r.rental_price " .
                                            "FROM Reservation AS r
											INNER JOIN RentalCustomer AS rc ON rc.rcID=r.rcID " .
                                            "WHERE rc.username='$username' AND rc.rcID=r.rcID";

									    $result = mysqli_query($con,$query) or die(mysql_error());
                                          
									while ($row = mysqli_fetch_array($result)) {
										$date1=date_create($row["start_date"]);
										$date2=date_create($row["end_date"]);
										$diff=date_diff($date1,$date2);
										$dateDiffFormatted = $diff->format("%R%a days");
										
										print "<tr>";
										print "<td>" . $row['rNO'] . "</td>";
										$tools="";
													
													// Get tools in the reservation
													$rNO = $row['rNO'];
													$query_holds="SELECT * FROM `holds` WHERE rNO='$rNO'";
													$result_holds = mysqli_query($con,$query_holds) or die(mysql_error());
													$tools = "";
													while($row_hold = $result_holds->fetch_assoc()) {
														$toolID = $row_hold["toolID"];
														$query_tool="SELECT * FROM `tool` WHERE toolID='$toolID'";
														$result_tool = mysqli_query($con,$query_tool) or die(mysql_error());
														$row_tool = mysqli_fetch_array($result_tool);
														$tools = $tools."</br>".$row_tool["short_description"];
													}
													
										print "<td>" . $tools . "</td>";
                                        print "<td>" . $row['start_date'] . "</td>";
                                        print "<td>" . $row['end_date'] . "</td>";
                                        print "<td>" . $row['pickupClerkENO'] . "</td>";
                                        print "<td>" . $row['dropoffClerkENO'] . "</td>";
                                        print "<td>" . $dateDiffFormatted . "</td>";
                                        print "<td>" . $row['deposit_price'] . "</td>";
                                        print "<td>" . $row['rental_price'] . "</td>";
										print "</tr>";
									}
								?>
                    </table>						
                </div>	

            </div> 			
        </div>  
		</div>
	<br/>Click here to go back to <a href='menu.php'>menu</a>
	</body>
</html>
</body>
</html>
