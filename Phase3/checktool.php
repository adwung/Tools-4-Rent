<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Check Tool Availability</title>
<h1>Check Tool Availability</h1>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
session_start();
?>

<form action="" method="post" name="login">
		<p>
			<label for="startdate_label">Start Date:</label>
			<input type="text" id="start_date" name="startdate" placeholder="yyyy-mm-dd" required />
		
			<label for="enddate_label">End Date:</label>
			<input type="text" id="end_date" name="enddate" placeholder="yyyy-mm-dd" required />
			
			<label for="customsearch_label">Custom Search:</label>
			<input type="text" name="cs_keyword" placeholder="Enter keyword" />
			
		</p>
		<p>
			Type: 
			<input type="radio" name="type" value="handtool" checked onclick="FillSubtype('handtool')">Hand Tool<br>
			<input type="radio" name="type" value="gardentool" onclick="FillSubtype('gardentool')">Garden Tool<br>
			<input type="radio" name="type" value="laddertool" onclick="FillSubtype('laddertool')">Ladder Tool<br>
			<input type="radio" name="type" value="powertool" onclick="FillSubtype('powertool')">Power Tool<br>
		</p>
		<p>
			Sub-type:
			<select name="subtype" id="subtype">
				<option value="all" selected="selected">All</option>
				<option value="screwdriver">Screwdriver</option>
				<option value="socket">Socket</option>
				<option value="ratchet">Ratchet</option>
				<option value="wrench">Wrench</option>
				<option value="pliers">Pliers</option>
				<option value="gun">Gun</option>
				<option value="hammer">Hammer</option>
			</select>
		</p>
		
		<p id="powersourceDiv" style="display:none">
			Power Source:
			<select name="powersource" id="powersource">
				<option value="all" selected="selected">All</option>
				<option value="electic">Electric</option>
				<option value="cordless">Cordless</option>
				<option value="gas">Gas</option>
				<option value="manual">Manual</option>
			</select>
		</p>
		<input name="submit" type="submit" value="Search" />
</form>

<?php
$username = $_SESSION['username'];
$query = "SELECT * FROM `rentalcustomer` WHERE username='$username'";
$result = mysqli_query($con,$query) or die(mysql_error());
$row_customer = mysqli_fetch_row($result);
$rcID = $row_customer[0];
	
if (isset($_REQUEST['startdate']) AND isset($_REQUEST['enddate'])){
	$startdate = stripslashes($_REQUEST['startdate']);
	$startdate = mysqli_real_escape_string($con,$startdate);
	
	$enddate = stripslashes($_REQUEST['enddate']);
	$enddate = mysqli_real_escape_string($con,$enddate);
	
	$cs_keyword = "";
	$powersource = "";
	
	if(isset($_REQUEST['cs_keyword'])) {
		$cs_keyword = stripslashes($_REQUEST['cs_keyword']);
		$cs_keyword = mysqli_real_escape_string($con,$cs_keyword);
	}
	
	$type = stripslashes($_REQUEST['type']);
	$type = mysqli_real_escape_string($con,$type);
	
	$subtype = stripslashes($_REQUEST['subtype']);
	$subtype = mysqli_real_escape_string($con,$subtype);
	
	if(isset($_REQUEST['powersource'])) {
		$powersource = stripslashes($_REQUEST['powersource']);
		$powersource = mysqli_real_escape_string($con,$powersource);
	}
	
	// toolID, short_description, rental_price, deposit_price
	
	$query= "SELECT tAll.toolID, tAll.short_description, tAll.rental_price, tAll.deposit_price , tAll.category
			FROM tool AS tAll
			LEFT OUTER JOIN
	        (SELECT t.toolID FROM holds AS h
			INNER JOIN reservation AS r
			ON h.rNO = r.rNO
			INNER JOIN tool AS t
			ON h.toolID = t.toolID
			WHERE (((date('$startdate') >= r.start_date AND date('$startdate')<=r.end_date)
			OR (date('$enddate') >= r.start_date AND date('$enddate')<=r.end_date)
			OR (date('$startdate') <= r.start_date AND date('$enddate')>=r.end_date)) AND (r.dropoffClerkENO IS NULL AND r.pickupClerkENO IS NOT NULL))) AS tSome
			ON tAll.toolID = tSome.toolID
			WHERE tSome.toolID IS NULL
			AND tAll.category = '$type'
			AND ((tALL.sub_type = '$subtype') OR ('$subtype' = 'All'))
			AND tAll.sub_option LIKE '%$cs_keyword%'";
	
	$result = mysqli_query($con,$query) or die(mysql_error());
	$rows = mysqli_num_rows($result);
?>

<table id="unreserved_tools" data-startDate="<?php echo $startdate; ?>" data-endDate="<?php echo $enddate; ?>">
        <thead>
            <tr class="header">
                <td>Tool ID</td>
                <td>Description</td>
				<td>Rental Price</td>
				<td>Deposit Price</td>
				<td>Add</td>
				
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
                <tr class="tool">
                    <td id="toolID"><?php echo $row["toolID"]; ?></td>
					<td id="description" onclick="GetFullDescription(
						'<?php
							echo $row_fd["full_desc"];
						?>'
					)">
						<?php echo $row["short_description"]; ?>
					</td>
					<td id="rental_price"><?php echo $row["rental_price"]; ?></td>
					<td id="deposit_price"><?php echo $row["deposit_price"]; ?></td>
				</tr>
		<?php 
		    } ?>
		</tbody>
</table>

<?php
}
?>

<br/>Click here to go back to <a href='menu.php'>menu</a></div>
<script>
	function GetFullDescription(fullDesc) {
		alert(fullDesc);
	}
	
	function FillSubtype(type) {
		var dropdown = document.getElementById("subtype");
		
		switch(type) {
			case "handtool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("All","all");
				dropdown.options[dropdown.options.length] = new Option("Screwdriver","screwdriver");
				dropdown.options[dropdown.options.length] = new Option("Socket","socket");
				dropdown.options[dropdown.options.length] = new Option("Ratchet","ratchet");
				dropdown.options[dropdown.options.length] = new Option("Wrench","wrench");
				dropdown.options[dropdown.options.length] = new Option("Pliers","pliers");
				dropdown.options[dropdown.options.length] = new Option("Gun","gun");
				dropdown.options[dropdown.options.length] = new Option("Hammer","hammer");
				
				// Disable powertool related UI elements
				var powersource = document.getElementById("powersourceDiv");
				powersource.style = "display:none";
				break;
			case "gardentool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("All","all");
				dropdown.options[dropdown.options.length] = new Option("Digger","digger");
				dropdown.options[dropdown.options.length] = new Option("Pruner","pruner");
				dropdown.options[dropdown.options.length] = new Option("Rakes","rakes");
				dropdown.options[dropdown.options.length] = new Option("Wheelbarrows","wheelbarrows");
				dropdown.options[dropdown.options.length] = new Option("Striking","striking");
				
				// Disable powertool related UI elements
				var powersource = document.getElementById("powersourceDiv");
				powersource.style = "display:none";
				break;
			case "laddertool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("All","all");
				dropdown.options[dropdown.options.length] = new Option("Straight","straight");
				dropdown.options[dropdown.options.length] = new Option("Step","step");
				
				// Disable powertool related UI elements
				var powersource = document.getElementById("powersourceDiv");
				powersource.style = "display:none";
				break;
			case "powertool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("All","all");
				dropdown.options[dropdown.options.length] = new Option("Drill","drill");
				dropdown.options[dropdown.options.length] = new Option("Saw","saw");
				dropdown.options[dropdown.options.length] = new Option("Sander","sander");
				dropdown.options[dropdown.options.length] = new Option("Air-Compressor","aircompressor");
				dropdown.options[dropdown.options.length] = new Option("Mixer","mixer");
				dropdown.options[dropdown.options.length] = new Option("Generator","generator");
				
				// Enable powertool related UI elements
				var powersource = document.getElementById("powersourceDiv");
				powersource.style = "display:block";
				break;
		}
	}
</script>