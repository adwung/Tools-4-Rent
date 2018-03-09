<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<title>Add Tool</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
session_start();

// If form submitted, insert values into the database.
if (isset($_REQUEST['material'])){
        // removes backslashes
	$username = $_SESSION['username'];
	$query = "SELECT * FROM `clerk` WHERE username='$username'";
	$result = mysqli_query($con,$query) or die(mysql_error());
	$row_clerk = mysqli_fetch_row($result);
	$empNO = $row_clerk[0];
	
	$tool_type = stripslashes($_REQUEST['tool_type']);
        //escapes special characters in a string
	$sub_type = stripslashes($_REQUEST['sub_type']);
	$sub_option = stripslashes($_REQUEST['sub_option']);
	$width = stripslashes($_REQUEST['width']);
	$width = mysqli_real_escape_string($con,$width);
  
	$width = stripslashes($_REQUEST['width']);
	$width = mysqli_real_escape_string($con,$width); 
	
	$width_unit = $_REQUEST['width_unit'];
	
	$length = stripslashes($_REQUEST['length']);
	$length = mysqli_real_escape_string($con,$length); 
	
	$length_unit = stripslashes($_REQUEST['length_unit']);
  
	$manufacturer = stripslashes($_REQUEST['manufacturer']);
	$manufacturer = mysqli_real_escape_string($con,$manufacturer); 	
	
	$weight = stripslashes($_REQUEST['weight']);
	$weight = mysqli_real_escape_string($con,$weight);
  
	$power_source = stripslashes($_REQUEST['power_source']);
	
	$material = stripslashes($_REQUEST['material']);
	$material = mysqli_real_escape_string($con,$material); 
  
	$rental_count = 1;
	
	$purchase_price = stripslashes($_REQUEST['purchase_price']);
	$purchase_price = mysqli_real_escape_string($con,$purchase_price);
	
	$deposit_price = round($purchase_price*0.4,2);	
	
	$rental_price = round($purchase_price*0.15,2);	
	
	$short_description = $power_source.",".$sub_option.",".$sub_type;
	
	$full_description = "full description";
	
	$status = 'available';
	
	$status_date = date("Y/m/d");

        $query = "INSERT into `Tool` (empNO, category, sub_type, sub_option, width, width_unit, length, length_unit, manufacturer, weight, power_source, material, deposit_price, rental_price, rental_count, purchase_price, short_description, full_description, status, statusdate)	   
VALUES ('$empNO','$tool_type', '$sub_type', '$sub_option', '$width', '$width_unit', '$length', '$length_unit', '$manufacturer', '$weight', '$power_source', '$material', '$deposit_price', '$rental_price', '$rental_count', '$purchase_price', '$short_description', '$full_description', '$status', '$status_date')";
        $result = mysqli_query($con,$query);
	
        if($result){
            echo "<div class='form'>
<h3>Tool added successfully!</h3>
<br/></div>";
        }  else {
			echo "Add tool failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
		}
		$toolIDInserted = mysqli_insert_id($con);
		
		if($tool_type == "handtool") {
			$screw_size = NULL;
			if(isset($_REQUEST['screw_size']) && !empty($_REQUEST['screw_size'])){
				$screw_size = stripslashes($_REQUEST['screw_size']);
				$screw_size = "'".mysqli_real_escape_string($con,$screw_size)."'";
			}

			$sae_size = NULL;
			if(isset($_REQUEST['sae_size']) && !empty($_REQUEST['sae_size'])){
				$sae_size = stripslashes($_REQUEST['sae_size']);
				$sae_size = "'".mysqli_real_escape_string($con,$sae_size)."'"; 
			}
			
			$deep_socket = NULL;
			if(isset($_REQUEST['deep_socket']) && !empty($_REQUEST['deep_socket'])){
				$deep_socket = stripslashes($_REQUEST['deep_socket']);
				$deep_socket = "'".mysqli_real_escape_string($con,$deep_socket)."'";
			}
			
			$drive_size = NULL;
			if(isset($_REQUEST['drive_size']) && !empty($_REQUEST['drive_size'])) {
				$drive_size = stripslashes($_REQUEST['drive_size']);
				$drive_size = "'".mysqli_real_escape_string($con,$drive_size)."'";
			}
			
			$adjustable = NULL;
			if(isset($_REQUEST['adjustable']) && !empty($_REQUEST['adjustable'])) {
				$adjustable = stripslashes($_REQUEST['adjustable']);
				$adjustable = mysqli_real_escape_string($con,$adjustable);
				$adjustable_tiny = 0;
				if($adjustable == "true") {
					$adjustable_tiny = 1;
				}
				$adjustable = "'".$adjustable_tiny."'";
			}
			
			$gauge_rating = NULL;
			if(isset($_REQUEST['gauge_rating']) && !empty($_REQUEST['gauge_rating'])) {
				$gauge_rating = stripslashes($_REQUEST['gauge_rating']);
				$gauge_rating = "'".mysqli_real_escape_string($con,$gauge_rating)."'";
			}
			
			$capacity = NULL;
			if(isset($_REQUEST['capacity']) && !empty($_REQUEST['capacity'])) {
				echo "capacity".$_REQUEST['capacity'];
				$capacity = stripslashes($_REQUEST['capacity']);
				$capacity = "'".mysqli_real_escape_string($con,$capacity)."'";
			}
			
			$anti_vibration = NULL;
			if(isset($_REQUEST['anti_vibration']) && !empty($_REQUEST['anti_vibration'])) {
				$anti_vibration = stripslashes($_REQUEST['anti_vibration']);
				$anti_vibration = "'".mysqli_real_escape_string($con,$anti_vibration)."'";
				$anti_vibration_tiny = 0;
				if($anti_vibration == "true") {
					$anti_vibration_tiny = 1;
				}
				$anti_vibration = "'".$anti_vibration_tiny."'";
			}
			
			$query = "INSERT into `handtool` (toolID, screw_size, sae_size, deep_socket, drive_size, adjustable, gauge_rating, capacity, anti_vibration)VALUES(".
			$toolIDInserted.",".
			(is_null($screw_size) ? "NULL" : $screw_size).",".
			(is_null($sae_size) ? "NULL" : $sae_size).",".
			(is_null($deep_socket) ? "NULL" : $deep_socket).",".
			(is_null($drive_size) ? "NULL" : $drive_size).",".
			(is_null($adjustable) ? "NULL" : $adjustable).",".
			(is_null($gauge_rating) ? "NULL" : $gauge_rating).",".
			(is_null($capacity) ? "NULL" : $capacity).",".
			(is_null($anti_vibration) ? "NULL" : $anti_vibration).")";
        $result = mysqli_query($con,$query);
		
        if($result){
            echo "<div class='form'>
<h3>Hand Tool added successfully!</h3>
<br/>Click here to go back to <a href='menu.php'>menu</a></div>";
        }  else {
			echo "Add Hand tool failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
		}
		}
		else if($tool_type == "gardentool") {
			$blade_length = NULL;
			if(isset($_REQUEST['blade_length']) && !empty($_REQUEST['blade_length'])){
				$blade_length = stripslashes($_REQUEST['blade_length']);
				$blade_length = "'".mysqli_real_escape_string($con,$blade_length)."'";
			}

			$blade_width = NULL;
			if(isset($_REQUEST['blade_width']) && !empty($_REQUEST['blade_width'])){
				$blade_width = stripslashes($_REQUEST['blade_width']);
				$blade_width = "'".mysqli_real_escape_string($con,$blade_width)."'"; 
			}
			
			$blade_material = NULL;
			if(isset($_REQUEST['blade_material']) && !empty($_REQUEST['blade_material'])){
				$blade_material = stripslashes($_REQUEST['blade_material']);
				$blade_material = "'".mysqli_real_escape_string($con,$blade_material)."'";
			}
			
			$tine_count = NULL;
			if(isset($_REQUEST['tine_count']) && !empty($_REQUEST['tine_count'])) {
				$tine_count = stripslashes($_REQUEST['tine_count']);
				$tine_count = "'".mysqli_real_escape_string($con,$tine_count)."'";
			}
			
			$bin_material = NULL;
			if(isset($_REQUEST['bin_material']) && !empty($_REQUEST['bin_material'])) {
				$bin_material = stripslashes($_REQUEST['bin_material']);
				$bin_material = "'".mysqli_real_escape_string($con,$bin_material)."'";
			}
			
			$bin_volume = NULL;
			if(isset($_REQUEST['bin_volume']) && !empty($_REQUEST['bin_volume'])) {
				$bin_volume = stripslashes($_REQUEST['bin_volume']);
				$bin_volume = "'".mysqli_real_escape_string($con,$bin_volume)."'";
			}
			
			$wheel_count = NULL;
			if(isset($_REQUEST['wheel_count']) && !empty($_REQUEST['wheel_count'])) {
				$wheel_count = stripslashes($_REQUEST['wheel_count']);
				$wheel_count = "'".mysqli_real_escape_string($con,$wheel_count)."'";
			}
			
			$head_weight = NULL;
			if(isset($_REQUEST['head_weight']) && !empty($_REQUEST['head_weight'])) {
				$head_weight = stripslashes($_REQUEST['head_weight']);
				$head_weight = "'".mysqli_real_escape_string($con,$head_weight)."'";
			}
			
			$handle_material = NULL;
			if(isset($_REQUEST['handle_material']) && !empty($_REQUEST['handle_material'])) {
				$handle_material = stripslashes($_REQUEST['handle_material']);
				$handle_material = "'".mysqli_real_escape_string($con,$handle_material)."'";
			}
			
			$query = "INSERT into `gardentool` (toolID, blade_length, blade_width, blade_material, tine_count, bin_material, bin_volume, wheel_count, head_weight,handle_material)VALUES(".
			$toolIDInserted.",".
			(is_null($blade_length) ? "NULL" : $blade_length).",".
			(is_null($blade_width) ? "NULL" : $blade_width).",".
			(is_null($blade_material) ? "NULL" : $blade_material).",".
			(is_null($tine_count) ? "NULL" : $tine_count).",".
			(is_null($bin_material) ? "NULL" : $bin_material).",".
			(is_null($bin_volume) ? "NULL" : $bin_volume).",".
			(is_null($wheel_count) ? "NULL" : $wheel_count).",".
			(is_null($head_weight) ? "NULL" : $head_weight).",".
			(is_null($handle_material) ? "NULL" : $handle_material).")";
        $result = mysqli_query($con,$query);
		
        if($result){
            echo "<div class='form'>
<h3>Garden Tool added successfully!</h3>
<br/>Click here to go back to <a href='menu.php'>menu</a></div>";
        }  else {
			echo "Add Garden tool failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
		}
		}
		else if($tool_type == "laddertool") {
			$step_count = NULL;
			if(isset($_REQUEST['step_count']) && !empty($_REQUEST['step_count'])){
				$step_count = stripslashes($_REQUEST['step_count']);
				$step_count = "'".mysqli_real_escape_string($con,$step_count)."'";
			}

			$weight_capacity = NULL;
			if(isset($_REQUEST['weight_capacity']) && !empty($_REQUEST['weight_capacity'])){
				$weight_capacity = stripslashes($_REQUEST['weight_capacity']);
				$weight_capacity = "'".mysqli_real_escape_string($con,$weight_capacity)."'"; 
			}
			
			$rubber_feet = NULL;
			if(isset($_REQUEST['rubber_feet']) && !empty($_REQUEST['rubber_feet'])){
				$rubber_feet = stripslashes($_REQUEST['rubber_feet']);
				$rubber_feet = mysqli_real_escape_string($con,$rubber_feet);
				$rubber_feet_tiny = 0;
				if($rubber_feet == "true") {
					$rubber_feet_tiny = 1;
				}
				$rubber_feet = "'".$rubber_feet_tiny."'";
			}
			
			$pail_shelf = NULL;
			if(isset($_REQUEST['pail_shelf']) && !empty($_REQUEST['pail_shelf'])) {
				$pail_shelf = stripslashes($_REQUEST['pail_shelf']);
				$pail_shelf = mysqli_real_escape_string($con,$pail_shelf);
				$pail_shelf_tiny = 0;
				if($pail_shelf == "true") {
					$pail_shelf_tiny = 1;
				}
				$pail_shelf = "'".$pail_shelf_tiny."'";
			}
			
			$query = "INSERT into `laddertool` (toolID, step_count, weight_capacity, rubber_feet, pail_shelf) VALUES(".
			$toolIDInserted.",".
			(is_null($step_count) ? "NULL" : $step_count).",".
			(is_null($weight_capacity) ? "NULL" : $weight_capacity).",".
			(is_null($rubber_feet) ? "NULL" : $rubber_feet).",".
			(is_null($pail_shelf) ? "NULL" : $pail_shelf).")";
        $result = mysqli_query($con,$query);
		
        if($result){
            echo "<div class='form'>
<h3>Ladder Tool added successfully!</h3>
<br/>Click here to go back to <a href='menu.php'>menu</a></div>";
        }  else {
			echo "Add Ladder tool failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
		}
		}
		else if($tool_type == "powertool") {
			$volt_rating = NULL;
			if(isset($_REQUEST['volt_rating']) && !empty($_REQUEST['volt_rating'])){
				$volt_rating = stripslashes($_REQUEST['volt_rating']);
				$volt_rating = "'".mysqli_real_escape_string($con,$volt_rating)."'";
			}

			$amp_rating = NULL;
			if(isset($_REQUEST['amp_rating']) && !empty($_REQUEST['amp_rating'])){
				$amp_rating = stripslashes($_REQUEST['amp_rating']);
				$amp_rating = "'".mysqli_real_escape_string($con,$amp_rating)."'"; 
			}
			
			$adjustable_clutch = NULL;
			if(isset($_REQUEST['adjustable_clutch']) && !empty($_REQUEST['adjustable_clutch'])){
				$adjustable_clutch = stripslashes($_REQUEST['adjustable_clutch']);
				$adjustable_clutch = mysqli_real_escape_string($con,$adjustable_clutch);
				$adjustable_clutch_tiny = 0;
				if($adjustable_clutch == "true") {
					$adjustable_clutch_tiny = 1;
				}
				$adjustable_clutch = "'".$adjustable_clutch_tiny."'";
			}
			
			$min_torque_rating = NULL;
			if(isset($_REQUEST['min_torque_rating']) && !empty($_REQUEST['min_torque_rating'])){
				$min_torque_rating = stripslashes($_REQUEST['min_torque_rating']);
				$min_torque_rating = "'".mysqli_real_escape_string($con,$min_torque_rating)."'";
			}
			
			$max_torque_rating = NULL;
			if(isset($_REQUEST['max_torque_rating']) && !empty($_REQUEST['max_torque_rating'])){
				$max_torque_rating = stripslashes($_REQUEST['max_torque_rating']);
				$max_torque_rating = "'".mysqli_real_escape_string($con,$max_torque_rating)."'";
			}
			
			$blade_size = NULL;
			if(isset($_REQUEST['blade_size']) && !empty($_REQUEST['blade_size'])){
				$blade_size = stripslashes($_REQUEST['blade_size']);
				$blade_size = "'".mysqli_real_escape_string($con,$blade_size)."'";
			}
			
			$dust_bag = NULL;
			if(isset($_REQUEST['dust_bag']) && !empty($_REQUEST['dust_bag'])){
				$dust_bag = stripslashes($_REQUEST['dust_bag']);
				$dust_bag = "'".mysqli_real_escape_string($con,$dust_bag)."'";
			}
			
			$tank_size = NULL;
			if(isset($_REQUEST['tank_size']) && !empty($_REQUEST['tank_size'])){
				$tank_size = stripslashes($_REQUEST['tank_size']);
				$tank_size = "'".mysqli_real_escape_string($con,$tank_size)."'";
			}
			
			$pressure_rating = NULL;
			if(isset($_REQUEST['pressure_rating']) && !empty($_REQUEST['pressure_rating'])){
				$pressure_rating = stripslashes($_REQUEST['pressure_rating']);
				$pressure_rating = "'".mysqli_real_escape_string($con,$pressure_rating)."'";
			}
			
			$pressure_rating = NULL;
			if(isset($_REQUEST['pressure_rating']) && !empty($_REQUEST['pressure_rating'])){
				$pressure_rating = stripslashes($_REQUEST['pressure_rating']);
				$pressure_rating = "'".mysqli_real_escape_string($con,$pressure_rating)."'";
			}
			
			$motor_rating = NULL;
			if(isset($_REQUEST['motor_rating']) && !empty($_REQUEST['motor_rating'])){
				$motor_rating = stripslashes($_REQUEST['motor_rating']);
				$motor_rating = "'".mysqli_real_escape_string($con,$motor_rating)."'";
			}
			
			$drum_size = NULL;
			if(isset($_REQUEST['drum_size']) && !empty($_REQUEST['drum_size'])){
				$drum_size = stripslashes($_REQUEST['drum_size']);
				$drum_size = "'".mysqli_real_escape_string($con,$drum_size)."'";
			}
			
			$power_rating = NULL;
			if(isset($_REQUEST['power_rating']) && !empty($_REQUEST['power_rating'])){
				$power_rating = stripslashes($_REQUEST['power_rating']);
				$power_rating = "'".mysqli_real_escape_string($con,$power_rating)."'";
			}
			
			$query = "INSERT into `powertool` (toolID, volt_rating, amp_rating, adjustable_clutch, min_torque_rating, max_torque_rating, blade_size, dust_bag, tank_size, pressure_rating, motor_rating,drum_size,power_rating) VALUES(".
			$toolIDInserted.",".
			(is_null($volt_rating) ? "NULL" : $volt_rating).",".
			(is_null($amp_rating) ? "NULL" : $amp_rating).",".
			(is_null($adjustable_clutch) ? "NULL" : $adjustable_clutch).",".
			(is_null($min_torque_rating) ? "NULL" : $min_torque_rating).",".
			(is_null($max_torque_rating) ? "NULL" : $max_torque_rating).",".
			(is_null($blade_size) ? "NULL" : $blade_size).",".
			(is_null($dust_bag) ? "NULL" : $dust_bag).",".
			(is_null($tank_size) ? "NULL" : $tank_size).",".
			(is_null($pressure_rating) ? "NULL" : $pressure_rating).",".
			(is_null($motor_rating) ? "NULL" : $motor_rating).",".
			(is_null($drum_size) ? "NULL" : $drum_size).",".
			(is_null($power_rating) ? "NULL" : $power_rating).")";
        $result = mysqli_query($con,$query);
		
        if($result){
            echo "<div class='form'>
<h3>Power Tool added successfully!</h3>
<br/></a></div>";

			// Add ACCESSORIES
			$accessory_description = NULL;
			if(isset($_REQUEST['accessory_description']) && !empty($_REQUEST['accessory_description'])){
				$accessory_description = stripslashes($_REQUEST['accessory_description']);
				$accessory_description = "'".mysqli_real_escape_string($con,$accessory_description)."'"; 
			}
			
			$accessory_quantity = NULL;
			if(isset($_REQUEST['accessory_quantity']) && !empty($_REQUEST['accessory_quantity'])){
				$accessory_quantity = stripslashes($_REQUEST['accessory_quantity']);
				$accessory_quantity = "'".mysqli_real_escape_string($con,$accessory_quantity)."'"; 
			}
			
			$battery_type = NULL;
			if(isset($_REQUEST['battery_type']) && !empty($_REQUEST['battery_type'])){
				$battery_type = stripslashes($_REQUEST['battery_type']);
				$battery_type = "'".mysqli_real_escape_string($con,$battery_type)."'"; 
			}
			
			$query = "INSERT into `powertoolaccessories` (toolID,description, quantity, battery_type) VALUES(".
			$toolIDInserted.",".
			(is_null($accessory_description) ? "NULL" : $accessory_description).",".
			(is_null($accessory_quantity) ? "NULL" : $accessory_quantity).",".
			(is_null($battery_type) ? "NULL" : $battery_type).")";
        
			$result = mysqli_query($con,$query);
			
			if($result){
				echo "<div class='form'>
				<h3>Power Tool Accessories added successfully!</h3>
				<br/>Click here to go back to <a href='menu.php'>menu</a></div>";
			}  else {
				echo "Add Power tool accessory failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
			}
			
			// End of power tool if block
			}  else {
				echo "Add Power tool failed for user! <br>Click here to go back to <a href='addTool.php'>Add Tool</a>";
			}
		}
} else {
?>
<div class="form">	
  <h2>Add Tool</h2>
    <form name="addtoolform" action="" method="get">
      <table>
        <tr>									<tr>
		  <td class="item_label">Type:</td>
		<td>
			<input type="radio" name="tool_type" value="handtool" checked onclick="Fillsub_type('handtool')">Hand Tool<br>
			<input type="radio" name="tool_type" value="gardentool" onclick="Fillsub_type('gardentool')">Garden Tool<br>
			<input type="radio" name="tool_type" value="laddertool" onclick="Fillsub_type('laddertool')">Ladder<br>
			<input type="radio" name="tool_type" value="powertool" onclick="Fillsub_type('powertool')">Power Tool<br>
			<br><br>
		</td>
		</tr>
		<tr>
			<td class="item_label">Sub-Type:</td>
			<td>
			<select name="sub_type" id="sub_type" data-type="handtool" onchange="Fillsub_option(this)">
				<option value="screwdriver" selected="selected">Screwdriver</option>
				<option value="socket">Socket</option>
				<option value="ratchet">Ratchet</option>
				<option value="wrench">Wrench</option>
				<option value="pliers">Pliers</option>
				<option value="gun">Gun</option>
				<option value="hammer">Hammer</option>
			</select>
		</td>
		</tr>
		<tr>
			<td class="item_label">Sub-Option:</td>
			<td><select name="sub_option" id="sub_option">
				<option value="phillips" selected="selected">phillips</option>
				<option value="hex">hex</option>
				<option value="torx">torx</option>
				<option value="slotted">slotted</option>
			</select>
		</td>
		</tr>
		<tr>		  
		  <td><p id="power_sourceDiv" style="display:none">
			  Power Source:
			<select name="power_source" id="power_source" onchange="BatteryTypeTrigger()">
				<option value="all" selected="selected">All</option>
				<option value="electic">Electric</option>
				<option value="cordless">Cordless</option>
				<option value="gas">Gas</option>
				<option value="manual">Manual</option>
			</select>
		</p>
		</td>
		</tr>
          
        <tr>
          <td class="item_label">Material</td>
          <td>
            <input type="text" name="material" />
            </td>
          </tr>			  

		  
        <tr>
          <td class="item_label">Purchase Price</td>
          <td>
            <input type="int" name="purchase_price" />										
            </td>
          </tr>
		  
        <tr>
          <td class="item_label">Manufacturer</td>
          <td>
            <input type="text" name="manufacturer" />	
            </td>
          </tr>
        
        <tr>
          <td class="item_label">Width</td>
		  <td>
           <input type="text" name="width" />	
            </td>
        </tr>
        
        <tr>
          <td class="item_label">Width Unit</td>
          <td>
            <select name="width_unit" id="width_unit">
              <option value="inches">inches</option>
              <option value="centimeters">centimeters</option>
              </select>
            </td>
          </tr>
        
        <tr>
          <td class="item_label">Length</td>
          <td>
            <input type="text" name="length" />	
            </td>
          </tr>
        
        <tr>
          <td class="item_label">Length Unit</td>
          <td>
            <select name="length_unit" id="length_unit">
              <option value="feet">feet</option>
              <option value="meters">meters</option>
              </select>
            </td>
          </tr>
        
        <tr>
          <td class="item_label">Weight(lbs)</td>
          <td>
            <input type="text" name="weight" />	
            </td>
          </tr>

		<!------------------ Hand tool ---------------->
		<tr class="handtool screwdriver socket ratchet wrench plier gyn hammer extra-attribs">
          <td class="item_label">HAND TOOLS ONLY:</td>
          <td></td>
          </tr>
		  
		<tr class="handtool screwdriver extra-attribs">
          <td class="item_label">Screw Size (in.)</td>
          <td>
            <input type="text" name="screw_size" />	
            </td>
          </tr>
		  
		<tr class="handtool socket ratchet wrench extra-attribs" style="display:none">
          <td class="item_label">Drive Size (in.)</td>
          <td>
            <input type="text" name="drive_size" />	
            </td>
          </tr>
        
		<tr class="handtool socket extra-attribs" style="display:none">
          <td class="item_label">Sae Size (in.)</td>
          <td>
            <input type="text" name="sae_size" />	
            </td>
          </tr>
		
		<tr class="handtool plier extra-attribs" style="display:none">
          <td class="item_label">Adjustable)</td>
          <td>
			<select name="adjustable" id="adjustable">
              <option value="true">true</option>
              <option value="false">false</option>
              </select>
            </td>
          </tr>
		
		<tr class="handtool gun extra-attribs" style="display:none">
          <td class="item_label">Gauge Rating (unit G)</td>
          <td>
            <input type="text" name="gauge_rating" />	
            </td>
          </tr>
		  
		<tr class="handtool gun extra-attribs" style="display:none">
          <td class="item_label">Capacity</td>
          <td>
            <input type="text" name="capacity" />	
            </td>
          </tr>
		
		<tr class="handtool hammer extra-attribs" style="display:none">
          <td class="item_label">Anti-vibration</td>
          <td>
			<select name="anti_vibration" id="anti_vibration">
              <option value="true">true</option>
              <option value="false">false</option>
              </select>
            </td>
          </tr>
		
		<!------------------ Garden tool ---------------->
		<tr class="gardentool pruner striking digger rakes wheelbarrows extra-attribs" style="display:none">
          <td class="item_label">GARDEN TOOLS ONLY:</td>
		  <td></td>
          </tr>
		  
		<tr class="gardentool pruner striking digger rakes wheelbarrows extra-attribs" style="display:none">
          <td class="item_label">Handle material</td>
          <td>
            <input type="text" name="handle_material" />	
            </td>
          </tr>
		  
		<tr class="gardentool pruner extra-attribs" style="display:none">
          <td class="item_label">Blade material</td>
          <td>
            <input type="text" name="blade_material" />	
            </td>
          </tr>
		
		<tr class="gardentool pruner extra-attribs" style="display:none">
          <td class="item_label">Blade length (in.)</td>
          <td>
            <input type="text" name="blade_length" />	
            </td>
          </tr>
		
		<tr class="gardentool striking extra-attribs" style="display:none">
          <td class="item_label">Head weight (lb.)</td>
          <td>
            <input type="text" name="head_weight" />	
            </td>
          </tr>
		
		<tr class="gardentool digging extra-attribs" style="display:none">
          <td class="item_label">Blade width (in.)</td>
          <td>
            <input type="text" name="blade_width" />	
            </td>
          </tr>
		
		<tr class="gardentool digging extra-attribs" style="display:none">
          <td class="item_label">Blade length (in.)</td>
          <td>
            <input type="text" name="blade_length" />	
            </td>
          </tr>
		  
		<tr class="gardentool rakes extra-attribs" style="display:none">
          <td class="item_label">Tine count</td>
          <td>
            <input type="text" name="tine_count" />	
            </td>
          </tr>
		
		<tr class="gardentool wheelbarrows extra-attribs" style="display:none">
          <td class="item_label">Bin material</td>
          <td>
            <input type="text" name="bin_material" />	
            </td>
          </tr>
		
		<tr class="gardentool wheelbarrows extra-attribs" style="display:none">
          <td class="item_label">Bin volume</td>
          <td>
            <input type="text" name="bin_volume" />	
            </td>
          </tr>
		
		<tr class="gardentool wheelbarrows extra-attribs" style="display:none">
          <td class="item_label">Wheel count (cu ft.)</td>
          <td>
            <input type="text" name="wheel_count" />	
            </td>
          </tr>
		
		<!------------------ Ladder tool ---------------->
		<tr class="laddertool straight step extra-attribs" style="display:none">
          <td class="item_label">LADDER TOOLS ONLY:</td>
		  <td></td>
          </tr>
		  
		<tr class="laddertool straight step extra-attribs" style="display:none">
          <td class="item_label">Step count</td>
          <td>
            <input type="text" name="step_count" />	
            </td>
          </tr>
		
		<tr class="laddertool straight step extra-attribs" style="display:none">
          <td class="item_label">Weight capacity (in lb.)</td>
          <td>
            <input type="text" name="weight_capacity" />	
            </td>
          </tr>
		  
		<tr class="laddertool straight extra-attribs" style="display:none">
          <td class="item_label">Rubber feet</td>
          <td>
            <select name="rubber_feet" id="rubber_feet">
              <option value="true">true</option>
              <option value="false">false</option>
              </select>
            </td>
          </tr>
		
		<tr class="laddertool step extra-attribs" style="display:none">
          <td class="item_label">Pail shelf</td>
          <td>
            <select name="pail_shelf" id="pail_shelf">
              <option value="true">true</option>
              <option value="false">false</option>
              </select>
            </td>
          </tr>
		
		<!------------------ Power tool ---------------->
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">POWER TOOLS ONLY:</td>
		  <td></td>
          </tr>
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">Volt Rating (V.)</td>
          <td>
            <input type="text" name="volt_rating" />
            </td>
          </tr>
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">Amp Rating (A.)</td>
          <td>
            <input type="text" name="amp_rating" />
            </td>
          </tr>
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">Min rpm rating (rpm)</td>
          <td>
            <input type="text" name="min_rpm_rating" />
            </td>
          </tr>
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">Max rpm rating (rpm)</td>
          <td>
            <input type="text" name="max_rpm_rating" />
            </td>
          </tr>
		
		<tr class="powertool drill extra-attribs" style="display:none">
          <td class="item_label">Adjustable clutch</td>
          <td>
            <select name="adjustable_clutch" id="adjustable_clutch">
              <option value="true">true</option>
              <option value="false">false</option>
              </select>
            </td>
          </tr>
		
		<tr class="powertool drill extra-attribs" style="display:none">
          <td class="item_label">Min torque rating (ft-lb.)</td>
          <td>
            <input type="text" name="min_torque_rating" />
            </td>
          </tr>
		  
		<tr class="powertool drill extra-attribs" style="display:none">
          <td class="item_label">Max torque rating (ft-lb.)</td>
          <td>
            <input type="text" name="max_torque_rating" />
            </td>
          </tr>  
		
		<tr class="powertool saw extra-attribs" style="display:none">
          <td class="item_label">Blade Size (in.)</td>
          <td>
            <input type="text" name="blade_size" />
            </td>
          </tr>  
		
		<tr class="powertool sander extra-attribs" style="display:none">
          <td class="item_label">Dust Bag</td>
          <td>
            <input type="text" name="dust_bag" />
            </td>
          </tr>  
		
		<tr class="powertool aircompressor extra-attribs" style="display:none">
          <td class="item_label">Tank Size (gal.)</td>
          <td>
            <input type="text" name="tank_size" />
            </td>
          </tr>  
		
		<tr class="powertool aircompressor extra-attribs" style="display:none">
          <td class="item_label">Pressure-Rating (psi.)</td>
          <td>
            <input type="text" name="pressure_rating" />
            </td>
          </tr> 
		
		<tr class="powertool mixer extra-attribs" style="display:none">
          <td class="item_label">Motor-Rating (HP.)</td>
          <td>
            <input type="text" name="motor_rating" />
            </td>
          </tr> 
		
		<tr class="powertool mixer extra-attribs" style="display:none">
          <td class="item_label">Drum-size (cu-ft.)</td>
          <td>
            <input type="text" name="drum_size" />
            </td>
          </tr> 
		
		<tr class="powertool generator extra-attribs" style="display:none">
          <td class="item_label">Power-Rating (Watt.)</td>
          <td>
            <input type="text" name="power_rating" />
            </td>
          </tr> 
		
		<tr class="powertool mixer extra-attribs" style="display:none">
          <td class="item_label">Motor-Rating (HP.)</td>
          <td>
            <input type="text" name="motor_rating" />
            </td>
          </tr> 
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">ACCESSORIES</td>
		  <td></td>
          </tr> 
		
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">accessory description</td>
          <td>
            <input type="text" name="accessory_description" />
            </td>
          </tr> 
		  
		<tr class="powertool drill saw sander  aircompressor mixer generator extra-attribs" style="display:none">
          <td class="item_label">accessory quantity</td>
          <td>
            <input type="text" name="accessory_quantity" />
            </td>
          </tr> 
		
		<tr class="powertool drill saw sander  aircompressor mixer generator cordless extra-attribs" style="display:none">
          <td class="item_label">battery type</td>
          <td>
            <input type="text" name="battery_type" />
            </td>
          </tr> 
		  
		  <td>
		  <input type="submit" name="confirm" value="Confirm" />			  
		  </td>
        </table>
      </form>
	<br/>Click here to go back to <a href='menu.php'>menu</a></div>
    </div>
	<?php } ?>
</body>
<script>
	function Fillsub_type(type) {
		var dropdown = document.getElementById("sub_type");
		dropdown.setAttribute("data-type", type);
		
		switch(type) {
			case "handtool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("Screwdriver","screwdriver");
				dropdown.options[dropdown.options.length] = new Option("Socket","socket");
				dropdown.options[dropdown.options.length] = new Option("Ratchet","ratchet");
				dropdown.options[dropdown.options.length] = new Option("Wrench","wrench");
				dropdown.options[dropdown.options.length] = new Option("Pliers","pliers");
				dropdown.options[dropdown.options.length] = new Option("Gun","gun");
				dropdown.options[dropdown.options.length] = new Option("Hammer","hammer");
				dropdown.onchange();
				// Disable powertool related UI elements
				var power_source = document.getElementById("power_sourceDiv");
				power_source.style = "display:none";
				break;
			case "gardentool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("Digger","digger");
				dropdown.options[dropdown.options.length] = new Option("Pruner","pruner");
				dropdown.options[dropdown.options.length] = new Option("Rakes","rakes");
				dropdown.options[dropdown.options.length] = new Option("Wheelbarrows","wheelbarrows");
				dropdown.options[dropdown.options.length] = new Option("Striking","striking");
				dropdown.onchange();
				// Disable powertool related UI elements
				var power_source = document.getElementById("power_sourceDiv");
				power_source.style = "display:none";
				break;
			case "laddertool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("Straight","straight");
				dropdown.options[dropdown.options.length] = new Option("Step","step");
				dropdown.onchange();
				// Disable powertool related UI elements
				var power_source = document.getElementById("power_sourceDiv");
				power_source.style = "display:none";
				break;
			case "powertool":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("Drill","drill");
				dropdown.options[dropdown.options.length] = new Option("Saw","saw");
				dropdown.options[dropdown.options.length] = new Option("Sander","sander");
				dropdown.options[dropdown.options.length] = new Option("Air-Compressor","aircompressor");
				dropdown.options[dropdown.options.length] = new Option("Mixer","mixer");
				dropdown.options[dropdown.options.length] = new Option("Generator","generator");
				dropdown.onchange();
				// Enable powertool related UI elements
				var power_source = document.getElementById("power_sourceDiv");
				power_source.style = "display:block";
				break;
		}
		
		BatteryTypeTrigger();
	}
	
	function BatteryTypeTrigger() {
		var classesToEnableDisable = document.getElementsByClassName("extra-attribs");
		var power_source = document.getElementById("power_source");
		var power_sourceDiv = document.getElementById("power_sourceDiv");
		
		var i;
		for (i = 0; i < classesToEnableDisable.length; i++) {
			var className = classesToEnableDisable[i].className;
				
			if(className && className.indexOf("cordless") > -1) {
				classesToEnableDisable[i].style = "display:none";
				if(power_source && power_source.options[power_source.selectedIndex].value == "cordless") {
					if(power_sourceDiv && power_sourceDiv.style && power_sourceDiv.style.display != "none") {
						classesToEnableDisable[i].style = "display:block";
					}
				}
			}
		}
	}
	
	function Fillsub_option(sub_type) {
		var sub_type_value = sub_type.value;
		var dropdown = document.getElementById("sub_option");
		
		// Start - Disable/Enable text boxes for type/subtype specific attributes
		var type = sub_type.getAttribute("data-type");
		if(type) {
			var classesToEnableDisable = document.getElementsByClassName("extra-attribs");
			var i;
			for (i = 0; i < classesToEnableDisable.length; i++) {
				var className = classesToEnableDisable[i].className;
				
				if(className && className.indexOf(type) > -1 && className.indexOf(sub_type_value) > -1) {
					classesToEnableDisable[i].style = "display:block";
				} else {
					classesToEnableDisable[i].style = "display:none";
				}
			}
			
		}
		
		// End - Disable/Enable text boxes for type/subtype specific attributes
		switch(sub_type_value) {
			case "screwdriver":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("phillips","phillips");
				dropdown.options[dropdown.options.length] = new Option("hex","hex");
				dropdown.options[dropdown.options.length] = new Option("torx","torx");
				dropdown.options[dropdown.options.length] = new Option("slotted","slotted");
				break;
				
			case "socket":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("deep","deep");
				dropdown.options[dropdown.options.length] = new Option("standard","standard");
				break;
				
			case "ratchet":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("adjustable","adjustable");
				dropdown.options[dropdown.options.length] = new Option("fixed","fixed");
				break;
				
			case "wrench":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("crescent","crescent");
				dropdown.options[dropdown.options.length] = new Option("torque","torque");
				dropdown.options[dropdown.options.length] = new Option("pipe","pipe");
				break;
				
			case "pliers":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("needlenose","needlenose");
				dropdown.options[dropdown.options.length] = new Option("cutting","cutting");
				dropdown.options[dropdown.options.length] = new Option("crimper","crimper");
				break;
				
			case "gun":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("nail","nail");
				dropdown.options[dropdown.options.length] = new Option("staple","staple");
				break;
				
			case "hammer":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("claw","claw");
				dropdown.options[dropdown.options.length] = new Option("sledge","sledge");
				dropdown.options[dropdown.options.length] = new Option("framing","framing");
				break;
				
			case "digger":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("pointed shovel","pointed shovel");
				dropdown.options[dropdown.options.length] = new Option("flat shovel","flat shovel");
				dropdown.options[dropdown.options.length] = new Option("scoop shovel","scoop shovel");
				dropdown.options[dropdown.options.length] = new Option("edgel","edgel");
				break;
				
			case "pruner":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("sheer","sheer");
				dropdown.options[dropdown.options.length] = new Option("loppers","loppers");
				dropdown.options[dropdown.options.length] = new Option("hedge","hedge");
				break;
				
			case "rakes":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("leaf","leaf");
				dropdown.options[dropdown.options.length] = new Option("landscaping","landscaping");
				dropdown.options[dropdown.options.length] = new Option("rock","rock");
				break;
				
			case "wheelbarrows":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("1-wheel","1-wheel");
				dropdown.options[dropdown.options.length] = new Option("2-wheel","2-wheel");
				break;
				
			case "striking":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("bar pry","bar pry");
				dropdown.options[dropdown.options.length] = new Option("rubber mallet","rubber mallet");
				dropdown.options[dropdown.options.length] = new Option("tamper","tamper");
				dropdown.options[dropdown.options.length] = new Option("pick axe","pick axe");
				dropdown.options[dropdown.options.length] = new Option("single bit axe","single bit axe");
				break;
				
			case "straight":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("rigid","rigid");
				dropdown.options[dropdown.options.length] = new Option("telescoping","telescoping");
				break;
				
			case "step":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("folding","folding");
				dropdown.options[dropdown.options.length] = new Option("multi-position","multi-position");
				break;
				
			case "drill":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("driver","driver");
				dropdown.options[dropdown.options.length] = new Option("hammer","hammer");
				break;
				
			case "saw":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("circular","circular");
				dropdown.options[dropdown.options.length] = new Option("reciprocating","reciprocating");
				dropdown.options[dropdown.options.length] = new Option("jig","jig");
				break;
				
			case "sander":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("finish","finish");
				dropdown.options[dropdown.options.length] = new Option("sheet","sheet");
				dropdown.options[dropdown.options.length] = new Option("belt","belt");
				dropdown.options[dropdown.options.length] = new Option("random orbital","random orbital");
				break;
				
			case "aircompressor":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("reciprocating","reciprocating");
				break;
				
			case "mixer":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("concrete","concrete");
				break;
				
			case "generator":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("electric","electric");
				break;
			
			case "default":
				dropdown.options.length = 0;
				dropdown.options[dropdown.options.length] = new Option("phillips","phillips");
				dropdown.options[dropdown.options.length] = new Option("hex","hex");
				dropdown.options[dropdown.options.length] = new Option("torx","torx");
				dropdown.options[dropdown.options.length] = new Option("slotted","slotted");
				break;
		}
		
		BatteryTypeTrigger();
	}
</script>
</html>
