<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
require('db.php');
// If form submitted, insert values into the database.
if (isset($_REQUEST['username'])){
        // removes backslashes
	$username = stripslashes($_REQUEST['username']);
        //escapes special characters in a string
	$username = mysqli_real_escape_string($con,$username); 
	
	$first_name = stripslashes($_REQUEST['first_name']);
	$first_name = mysqli_real_escape_string($con,$first_name); 
	
	$middle_name = stripslashes($_REQUEST['middle_name']);
	$middle_name = mysqli_real_escape_string($con,$middle_name); 
	
	$last_name = stripslashes($_REQUEST['last_name']);
	$last_name = mysqli_real_escape_string($con,$last_name); 
	
	$type = "customer";//stripslashes($_REQUEST['type']);
	
	$email = stripslashes($_REQUEST['email']);
	$email = mysqli_real_escape_string($con,$email);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string($con,$password);
	
	$city = stripslashes($_REQUEST['city']);
	$city = mysqli_real_escape_string($con,$city);

	$street = stripslashes($_REQUEST['street']);
	$street = mysqli_real_escape_string($con,$street); 	
	
	$zipcode = stripslashes($_REQUEST['zipcode']);
	$zipcode = mysqli_real_escape_string($con,$zipcode);

	$state = stripslashes($_REQUEST['state']);
	$state = mysqli_real_escape_string($con,$state); 	
	
	$primaryphone = stripslashes($_REQUEST['primaryphone']);
	$primaryphone = mysqli_real_escape_string($con,$primaryphone);

	$homephone = stripslashes($_REQUEST['homephone']);
	$homephone = mysqli_real_escape_string($con,$homephone); 

	$workphone = stripslashes($_REQUEST['workphone']);
	$workphone = mysqli_real_escape_string($con,$workphone); 

	$cellphone = stripslashes($_REQUEST['cellphone']);
	$cellphone = mysqli_real_escape_string($con,$cellphone); 	
	
	$cc_name = stripslashes($_REQUEST['cc_name']);
	$cc_name = mysqli_real_escape_string($con,$cc_name);

	$cc_no = stripslashes($_REQUEST['cc_no']);
	$cc_no = mysqli_real_escape_string($con,$cc_no);
	
	$cc_cvc = stripslashes($_REQUEST['cc_cvc']);
	$cc_cvc = mysqli_real_escape_string($con,$cc_cvc);
	
	$cc_month = stripslashes($_REQUEST['cc_month']);
	$cc_month = mysqli_real_escape_string($con,$cc_month);
	
	$cc_year = stripslashes($_REQUEST['cc_year']);
	$cc_year = mysqli_real_escape_string($con,$cc_year);
	
        $query = "INSERT into `User` (username, password, email, first_name, middle_name, last_name, type)
VALUES ('$username', '".md5($password)."', '$email', '$first_name', '$middle_name', '$last_name', '$type')";
        $result = mysqli_query($con,$query);
		
		$query2 = "INSERT into `RentalCustomer` (username, street, city, state, zip)
VALUES ('$username', '$street', '$city', '$state', '$zipcode')";
		$result2 = mysqli_query($con,$query2);

		$query3 = "INSERT into `CustomerPhoneNumber` (username, primary_phone, home_phone_number, work_phone_number, cell_phone_number)
VALUES ('$username', '$primaryphone', '$homephone', '$workphone', '$cellphone')";
		$result3 = mysqli_query($con,$query3);
		
		$query4 = "INSERT into `creditcard` (username, name, cvc, expiration_month, expiration_year, cc_number)
VALUES ('$username', '$cc_name', '$cc_cvc', '$cc_month', '$cc_year', '$cc_no')";
		$result4 = mysqli_query($con,$query4);
		
        if($result AND $result2 AND $result3 AND $result4){
            echo "<div class='form'>
<h3>You are registered successfully.</h3>
<br/>Click here to <a href='login.php'>Login</a></div>";
        }  else {
			echo "Table insert failed for user!";
		}
    }else{
?>
<div class="form">
<h1>Registration</h1>
<form name="registration" action="" method="post">
<p>
	<input type="text" name="first_name" placeholder="First Name" required />
	<input type="text" name="middle_name" placeholder="Middle Name" required />
	<input type="text" name="last_name" placeholder="Last Name" required />
</p>
<!-- <p>
	<input type="radio" name="type" value="customer" checked> Rental Customer<br>
	<input type="radio" name="type" value="clerk"> Clerk<br>
</p> -->
<p>
	<input type="text" name="username" placeholder="Username" required />
	<input type="email" name="email" placeholder="Email" required />
</p>
<p>
	<input type="password" name="password" placeholder="Password" required />
	<input type="password" name="retype-password" placeholder="Re-type Password" required />
</p>
<p>
	<input type="text" name="homephone" placeholder="Home Phone" required />
	<input type="text" name="workphone" placeholder="Work Phone" required />
	<input type="text" name="cellphone" placeholder="Cell Phone" required />
</p>
<p>
	<label for="phonetype">Primary Phone:</label>
	<input type="radio" name="primaryphone" value="home"> Home Phone<br>
	<input type="radio" name="primaryphone" value="work"> Work Phone<br>
	<input type="radio" name="primaryphone" value="cell"> Cell Phone<br>
</p>
<p><input type="text" name="street" placeholder="Street Address" required /></p>
<p>
	<input type="text" name="city" placeholder="City" required />
	<select name="state" id="state">
		<option value="" selected="selected">Select a State</option>
		<option value="AL">Alabama</option>
		<option value="AK">Alaska</option>
		<option value="AZ">Arizona</option>
		<option value="AR">Arkansas</option>
		<option value="CA">California</option>
		<option value="CO">Colorado</option>
		<option value="CT">Connecticut</option>
		<option value="DE">Delaware</option>
		<option value="DC">District Of Columbia</option>
		<option value="FL">Florida</option>
		<option value="GA">Georgia</option>
		<option value="HI">Hawaii</option>
		<option value="ID">Idaho</option>
		<option value="IL">Illinois</option>
		<option value="IN">Indiana</option>
		<option value="IA">Iowa</option>
		<option value="KS">Kansas</option>
		<option value="KY">Kentucky</option>
		<option value="LA">Louisiana</option>
		<option value="ME">Maine</option>
		<option value="MD">Maryland</option>
		<option value="MA">Massachusetts</option>
		<option value="MI">Michigan</option>
		<option value="MN">Minnesota</option>
		<option value="MS">Mississippi</option>
		<option value="MO">Missouri</option>
		<option value="MT">Montana</option>
		<option value="NE">Nebraska</option>
		<option value="NV">Nevada</option>
		<option value="NH">New Hampshire</option>
		<option value="NJ">New Jersey</option>
		<option value="NM">New Mexico</option>
		<option value="NY">New York</option>
		<option value="NC">North Carolina</option>
		<option value="ND">North Dakota</option>
		<option value="OH">Ohio</option>
		<option value="OK">Oklahoma</option>
		<option value="OR">Oregon</option>
		<option value="PA">Pennsylvania</option>
		<option value="RI">Rhode Island</option>
		<option value="SC">South Carolina</option>
		<option value="SD">South Dakota</option>
		<option value="TN">Tennessee</option>
		<option value="TX">Texas</option>
		<option value="UT">Utah</option>
		<option value="VT">Vermont</option>
		<option value="VA">Virginia</option>
		<option value="WA">Washington</option>
		<option value="WV">West Virginia</option>
		<option value="WI">Wisconsin</option>
		<option value="WY">Wyoming</option>
	</select>
	<input type="text" name="zipcode" placeholder="Zip Code" required />
</p>

<label for="creditcard">Credit Card:</label>
<p>
	<input type="text" name="cc_name" placeholder="Name on Credit Card" required />
	<input type="text" name="cc_no" placeholder="Credit Card #" required />
	<select name="cc_month">
	<option value="0">January</option>
	<option value="1">February</option>
	<option value="2">March</option>
	<option value="3">April</option>
	<option value="4">May</option>
	<option value="5">June</option>
	<option value="6">July</option>
	<option value="7">August</option>
	<option value="8">September</option>
	<option value="9">October</option>
	<option value="10">November</option>
	<option value="11">December</option>
	</select>
	
	<select name="cc_year">
	<option value="17">2017</option>
	<option value="18">2018</option>
	<option value="19">2019</option>
	<option value="20">2020</option>
	<option value="21">2021</option>
	<option value="22">2022</option>
	<option value="23">2023</option>
	<option value="24">2024</option>
	<option value="25">2025</option>
	<option value="26">2026</option>
	</select>
	<input type="text" name="cc_cvc" placeholder="CVC" required />
</p>

<input type="submit" name="submit" value="Register" />
</form>
</div>
<?php } ?>
</body>
</html>