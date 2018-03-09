<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<title>Tool Inventory Report</title>

<!-- The list of tools where their total profit and cost are shown for all time -->

<link rel="stylesheet" href="css/style.css" />
</head>
<body>


<form action="" method="post" name="login">

		<p>
			Type: 
			<input type="radio" name="type" value="handtool" checked onclick="FillSubtype('handtool')">Hand Tool<br>
			<input type="radio" name="type" value="gardentool" onclick="FillSubtype('gardentool')">Garden Tool<br>
			<input type="radio" name="type" value="laddertool" onclick="FillSubtype('laddertool')">Ladder<br>
			<input type="radio" name="type" value="powertool" onclick="FillSubtype('powertool')">Power Tool<br>
		</p>

		<p>
			
			<label for="customsearch_label">Custom Search:</label>
			<input type="text" name="cs_keyword" placeholder="Enter keyword" />
			
		</p>
		<input name="submit" type="submit" value="Search" />
</form>





<?php


require('db.php');
session_start();




	$cs_keyword = "";
	$powersource = "";
	
if(isset($_REQUEST['cs_keyword'])) {
	if(isset($_REQUEST['cs_keyword'])) {
		$cs_keyword = stripslashes($_REQUEST['cs_keyword']);
		$cs_keyword = mysqli_real_escape_string($con,$cs_keyword);
	}
	
	$type = stripslashes($_REQUEST['type']);
	$type = mysqli_real_escape_string($con,$type);

$query = "SELECT category, toolID, status, statusdate, CONCAT(power_source,',',sub_option,',',sub_type) AS short_description, (rental_count * rental_price) AS rental_profit, purchase_price, ((rental_count * rental_price)-purchase_price) AS total_profit FROM tool where category = '$type' AND sub_option LIKE '%$cs_keyword%'";
							
$result = mysqli_query($con,$query) or die(mysql_error());
$rows = mysqli_num_rows($result);

?>

<table class="tools">
        <thead>
            <tr class="header">
			
                <td><b>Tool ID</b></td>
                <td><b>Current Status</b></td>
				<td><b>Date</b></td>
				<td><b>Description</b></td>
				<td><b>Rental Profit</b></td>
				<td><b>Total Cost</b></td>
				<td><b>Total Profit</b></td>

            </tr>
        </thead>
        <tbody>
        <?php
            while($row = mysqli_fetch_array($result)) {
							$category = $row['category'];
							$query_fd = '';
							if($category == "handtool") {
								$query_fd = 'SELECT CONCAT(length,"in. Wx",width," L",weight," lb.", category," ",sub_option," ",sub_type," ", "#", IF(NOT ISNULL(screw_size), screw_size, ""), IF(NOT ISNULL(drive_size), drive_size, ""), " in.", IF(adjustable, "adjustable", ""), " ", IF(NOT ISNULL(gauge_rating), gauge_rating, ""), "G ", IF(NOT ISNULL(capacity), capacity, ""), " ", manufacturer) AS full_desc
								FROM Tool
								INNER JOIN handtool AS ht
								WHERE Tool.toolID ='.$row["toolID"].' AND ht.toolID ='.$row["toolID"];
							} else if ($category == "powertool") {
								$query_fd = 'SELECT CONCAT(length,"in. Wx",width," L",weight," lb.", category," ",sub_option," ",sub_type,IF(volt_rating IS NOT NULL, CONCAT(volt_rating,"V "), ""),IF(amp_rating IS NOT NULL, CONCAT(amp_rating, "A "), ""),IF(min_torque_rating IS NOT NULL, CONCAT(min_torque_rating, "ft-lb "), ""),IF(blade_size IS NOT NULL, CONCAT(blade_size, "in. "), ""),IF(tank_size IS NOT NULL, CONCAT(tank_size, "Gal "), ""),IF(motor_rating IS NOT NULL, CONCAT(motor_rating, "hp "), ""),IF(power_rating IS NOT NULL, CONCAT(power_rating, "W "), ""),manufacturer) AS full_desc
								FROM Tool
								INNER JOIN powertool AS pt
								WHERE Tool.toolID ='.$row["toolID"].' AND pt.toolID ='.$row["toolID"];
							} else if ($category == "gardentool") {
								$query_fd = 'SELECT CONCAT(length,"in. Wx",width," L",weight," lb.", category," ",sub_option," ",sub_type," ",IF(NOT ISNULL(handle_material), handle_material, "")," ",IF(NOT ISNULL(blade_length), blade_length, "")," in. ",IF(NOT ISNULL(head_weight), head_weight, "")," lb. ",IF(NOT ISNULL(blade_width), blade_width, "")," in. ",IF(blade_length IS NOT NULL, CONCAT(blade_length, "in. "), ""),IF(NOT ISNULL(tine_count), tine_count, "")," tine ",IF(NOT ISNULL(bin_material), bin_material, "")," ",IF(NOT ISNULL(wheel_count), wheel_count, "")," ",manufacturer) AS full_desc
								FROM Tool
								INNER JOIN gardentool AS gt
								WHERE Tool.toolID ='.$row["toolID"].' AND gt.toolID ='.$row["toolID"];
							} else {
								$query_fd = 'SELECT CONCAT(length,"in. Wx",width," L",weight," lb.", category," ",sub_option," ",sub_type," ",IF(step_count IS NOT NULL, CONCAT(step_count," step "), ""),IF(weight_capacity IS NOT NULL, CONCAT(weight_capacity," lb.capacity "), ""),manufacturer) AS full_desc
								FROM Tool
								INNER JOIN laddertool AS lt
								WHERE Tool.toolID ='.$row["toolID"].' AND lt.toolID ='.$row["toolID"];
							}
							
							$result_fd = mysqli_query($con,$query_fd) or die(mysql_error());
							$row_fd = mysqli_fetch_array($result_fd);
            ?>
					                            <td><?php echo $row['toolID']?></td>
											    <td><?php if ($row['status'] == 'available'){
                                                  echo '<td bgcolor="#00FF99">' . $row['status'] ;
                                                      } 
													  if ($row['status'] == 'rented'){
                                                  echo '<td bgcolor="#FFFF00">' . $row['status'] ;
                                                      } 
													  if ($row['status'] == 'in-repair'){
                                                  echo '<td bgcolor="#cc0000">' . $row['status'] ;
                                                      } 
													  if ($row['status'] == 'for-sale'){
                                                  echo '<td bgcolor="#999999">' . $row['status'] ;
                                                      }
													if ($row['status'] == 'Sold'){
                                                  echo '<td bgcolor="#000000">' . $row['status'] ;
                                                      }
													  ?></td>
													  
												<td><?php echo $row['statusdate']?></td> 
												<td id="description" onclick="GetFullDescription(
													'<?php
														echo $row_fd["full_desc"];
													?>'
												)">
													<?php echo $row["short_description"]; ?>
												</td>
												<td><?php echo $row['rental_profit']?></td>
												<td><?php echo $row['purchase_price']?></td>	
												<td><?php echo $row['total_profit']?></td>

													
												
                </tr>

            <?php
            }
            ?>
        </tbody>
</table>
<?php
            }
            ?>

<form action='Reports.php'>
    <input type="submit" value="Back to Report Menu" /> <a href='Inventoryreport.php'>Reload Results</a></div>
</form>

<script>
	function GetFullDescription(fullDesc) {
		alert(fullDesc);
	}
	
</script>