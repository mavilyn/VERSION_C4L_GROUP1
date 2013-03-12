<?php
		include("class_lib.php");
		session_start();
		
						$accountnum = $_SESSION['client']->get_accountnum();
						$conn = oci_connect("guestbank", "kayato1");
						$query =  'SELECT * FROM client where accountnum = :accountnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':accountnum', $accountnum);
						oci_execute($compiled);
						while ($row = oci_fetch_array($compiled, OCI_BOTH)) {
							$fname = $row[3];
							$mname = $row[4];
							$lname = $row[5];
							$gender = $row[6];

							$homeaddress = $row[7];
							$civilstat = $row[8];
							$birthdate = $row[9];
							//$token = strtok($birthdate, "-");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
							  $month = substr($birthdate, 0, 2);
							  $day = substr($birthdate, 3, 2);
							  $year = substr($birthdate, 6, 4);
							  /*$token = strtok("-");
							  $day = $token;
							  $token = strtok("-");
							  $year = $token;
*/							if(isset($row[10])){
								$email = $row[10];
							}
							else{
								$email = "";
							}
							if(isset($row[11])){
								$contact = $row[11];	
							}
							else{
								$contact = "";
							}
							if(isset($row[12])){
								$spouse = $row[12];
							}
							else{
								$spouse = "";	
							}
							$mother = $row[13];
							
							//$fname = $row[3];
							//$ = $row[3];
						}
		if(isset($_POST['Submit'])){


			if($_POST['fname']!=""){
				$fname = $_POST['fname'];
			}
			if($_POST['mname']!=""){
				$mname = $_POST['mname'];
			}
			if($_POST['lname']!=""){
				$lname = $_POST['lname'];
			}
			if(isset($_POST['gender'])){
				$gender = $_POST['gender'];
			}
			if($_POST['province']!=""){
				$homeaddress = $_POST['brgy'].",".$_POST['city'].", ".$_POST['province'];
			}
			if($_POST['month']!=""){
				$month = $_POST['month'];
			}
			if($_POST['day']!=""){
				$day = $_POST['day'];
			}
			if($_POST['year']!=""){
				$year = $_POST['year'];
			}
			$birthdate = $month."-".$day."-".$year;
			if($_POST['civilstat']!=""){
				$civilstat = $_POST['civilstat'];
			}
			
			if($_POST['email']!=""){
				$email = $_POST['email'];
			}
			if($_POST['contact']!=""){
				$contact = $_POST['contact'];
			}
			if($_POST['spouse']!=""){
				$contact = $_POST['spouse'];
			}
			if($_POST['mother']!=""){
				$contact = $_POST['mother'];
			}
			
			$aquery = 'UPDATE CLIENT SET fname = :fname, mname = :mname, lname=:lname, gender=:gender, homeaddress=:homeaddress, civilstat=:civilstat,
			bday=:birthdate, email=:email, contact=:contact, spouse=:spouse, mother=:mother where accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':fname', $fname);
						oci_bind_by_name($sid, ':mname', $mname);
						oci_bind_by_name($sid, ':lname', $lname);
						oci_bind_by_name($sid, ':gender', $gender);
						oci_bind_by_name($sid, ':homeaddress', $homeaddress);
						oci_bind_by_name($sid, ':civilstat', $civilstat);
						oci_bind_by_name($sid, ':birthdate', $birthdate);
						oci_bind_by_name($sid, ':email', $email);
						oci_bind_by_name($sid, ':contact', $contact);
						oci_bind_by_name($sid, ':spouse', $spouse);
						oci_bind_by_name($sid, ':mother', $mother);
						oci_execute($sid);
						$_SESSION['client']->set_name($fname);
						$_SESSION['client']->set_lname($lname);
		}

		if(isset($_POST['Cancel'])){
			header("Location: client_home.php");
		}
?>

<html>
	<head>
		<title>	Update Personal Information </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<?php if(isset($_SESSION['loginclient'])){
			echo "Welcome ".$_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname();
						
						

			?>
			<form name = "update_personal" method ="post" action = "updateInfo.php" onSubmit="return checkUpdatePersonal();">
			  <!----> 
			<b>Update personal information. Please leave correct information blank.</b>
			<br /> <br />
			First Name: <input type="text" name="fname" maxlength="30" placeholder="<?php echo $fname;?>" value=""/>
			<span id="fnameErr" style="color:red;font-weight:bold;"></span>	 
						<br /> 	
			Middle Name: <input type="text" name="mname" maxlength="30" placeholder="<?php echo $mname;?>" value="" /> 
			<span id="mnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			Last Name: <input type="text" name="lname" maxlength="30" placeholder="<?php echo $lname;?>" value=""/> 
			<span id="lnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			Gender: <input type="radio" name="gender" id="male" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" id="female"value="female"/> 
					<label for="female">Female</label> 
					<span id="genderErr" style="color:red;font-weight:bold;"></span>	
						<br />
			Home Address: <?php echo "<i style='color:gray;'>".$homeaddress."</i>";?>
						<span id="homeaddressErr" style="color:red;font-weight:bold;"></span>	
						<br />
						&nbsp;&nbsp;&nbsp;&nbsp;House Number, Brgy./Subdivision: <input type="text" name="brgy" maxlength="30" value=""/><br />
						&nbsp;&nbsp;&nbsp;&nbsp;City/Town: <input type="text" name="city" maxlength="30" value=""/><br />
						&nbsp;&nbsp;&nbsp;&nbsp;Province, Zip code: <input type="text" name="province" maxlength="20" value=""/><br />
			Civil Status: 
					<select name="civilstat">
						<option value=""></option> 
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select> 
						<br />
			Birthdate: 
					<input type="text" name="month" min="1" max="12" placeholder="<?php echo $month;?>" value=""/> - 
					<input type="text" name="day" min="1" max="31" placeholder="<?php echo $day;?>" value=""/> - 						
					<input type="text" name="year" min="1900" max="2100" placeholder="<?php echo $year;?>" value=""/>
					<span id="birthErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Email Address: <input type="text" name="email" maxlength="50" placeholder="<?php echo $email;?>" value=""/> 
					<span id="emailErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Contact Number: <input type="text" name="contact" maxlength="50" placeholder="<?php echo $contact;?>" value=""/> 
					<span id="contactErr" style="color:red;font-weight:bold;"></span>
					<br />
			Spouse's Name: <input type="text" name="spouse" maxlength="100" placeholder="<?php echo $spouse;?>" value=""/> 
							<span id="spouseErr" style="color:red;font-weight:bold;"></span>
							<br />
			Mother's Maiden Name: <input type="text" name="mother" maxlength="100" placeholder="<?php echo $mother;?>" value=""/> 
							<span id="motherErr" style="color:red;font-weight:bold;"></span>
							<br />
					<input type="submit" name="Submit" value="Update Personal Information" />	
		</form>
		<form name="cancel_update_form" action="#" method="POST" onSubmit="return confirmCancel_update();">
					<input type="submit" name="Cancel" value="Cancel" />
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
