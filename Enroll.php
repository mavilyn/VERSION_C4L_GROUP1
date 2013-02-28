<?php
	//chazel mae 
		$valid = false;
		if(isset($_POST['Submit'])){
				
			$accountnum = $_POST['accountnum'];
			$atmpin = $_POST['atmpin'];
			$fname = $_POST['fname'];
			$mname = $_POST['mname'];
			$lname = $_POST['lname'];
			$birthday = $_POST['month']."-".$_POST['day']."-".$_POST['year'];
			$homeaddress = $_POST['brgy']."".$_POST['city']."".$_POST['province'];
			$connGuest = oci_connect("guestbank", "kayato1");
			
				$stid = oci_parse($connGuest,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM client
					WHERE accountnum = '.$accountnum
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_execute($stid);
			oci_fetch($stid);

			if($num_rows > 0) {
				echo '<script type=text/javascript>alert("Account number is already registered in the Guestbank Online Solutions!");return false;</script>';
			}
			
			else{
				$connMain = oci_connect("mainbank", "kayato1");
			
				$stid2 = oci_parse($connMain,
					'SELECT COUNT(*) AS NUM_ROWS2 FROM atm
					WHERE accountnum = '.$accountnum.'and pin = '.$atmpin
				);

				oci_define_by_name($stid2, 'NUM_ROWS2', $num_rows2);
				oci_execute($stid2);
				oci_fetch($stid2);
				
				if($num_rows2>0){
					$valid = true;
						$inactive = 0;
						$stid3 = oci_parse($connGuest,
						'INSERT INTO client VALUES(:username, :password, :accountnum, :fname, :mname, :lname, :gender, :homeaddress, :civilstat, :birthday, :email, :contact, :spouse, :mother, :secret, :answer, 0)'
								);
						oci_bind_by_name($stid3, ':username', $_POST['username']);
						oci_bind_by_name($stid3, ':password', $_POST['password']);
						oci_bind_by_name($stid3, ':accountnum', $_POST['accountnum']);
						oci_bind_by_name($stid3, ':fname', $_POST['fname']);
						oci_bind_by_name($stid3, ':mname', $_POST['mname']);
						oci_bind_by_name($stid3, ':lname', $_POST['lname']);
						oci_bind_by_name($stid3, ':gender', $_POST['gender']);
						oci_bind_by_name($stid3, ':homeaddress', $homeaddress);
						oci_bind_by_name($stid3, ':gender', $_POST['gender']);
						oci_bind_by_name($stid3, ':civilstat', $_POST['civilstat']);
						oci_bind_by_name($stid3, ':birthday', $birthday);
						oci_bind_by_name($stid3, ':email', $_POST['email']);
						oci_bind_by_name($stid3, ':contact', $_POST['contact']);
						oci_bind_by_name($stid3, ':spouse', $_POST['spouse']);
						oci_bind_by_name($stid3, ':mother', $_POST['mother']);
						oci_bind_by_name($stid3, ':secret', $_POST['secret']);
						oci_bind_by_name($stid3, ':answer', $_POST['answer']);
						oci_execute($stid3);
						echo "Account successfully added! ";
				}
				else{
					echo '<script type=text/javascript>alert("Account number does not exist!");return false;</script>';
					return false;
					$valid = false;
				}
			}
			
		}
			
				
?>
<html>
	<head>
		<title>	Enroll me! </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		
		<?php 
		if($valid == false){
			?>
			<form name = "enroll_form" method ="post" action = "Enroll.php" onSubmit="return checkEnroll();">
			  <!----> 
			<b>Please complete the fields below for enrolling your ATM in GuestBank's Online Solutions</b>
				Fields with *** are required.
			<br /> <br />
			<b><i>First Step --- Enter your ATM Information</i></b>
			<br /> <br />
			*** ATM Account Number: <input type = "text" name="accountnum" maxlength="10"/>
			<span id="accountnumErr" style="color:red;font-weight:bold;"></span>						
			<br />
			*** ATM Pin: <input type = "password" name="atmpin" maxlength="4">
			<span id="atmpinErr" style="color:red;font-weight:bold;"></span>		
			<br />
			<br /> <br />
			<b><i>Second Step --- Enter your personal information</i></b>
			<br /> <br />
			*** First Name: <input type="text" name="fname" maxlength="30"/>
			<span id="fnameErr" style="color:red;font-weight:bold;"></span>	 
						<br /> 	
			*** Middle Name: <input type="text" name="mname" maxlength="30"/> 
			<span id="mnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			*** Last Name: <input type="text" name="lname" maxlength="30"/> 
			<span id="lnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			*** Gender: <input type="radio" name="gender" id="male" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" id="female"value="female" /> 
					<label for="female">Female</label> 
					<span id="genderErr" style="color:red;font-weight:bold;"></span>	
						<br />
			*** Home Address: <span id="homeaddressErr" style="color:red;font-weight:bold;"></span>	
						<br />
						House Number, Brgy./Subdivision: <input type="text" name="brgy" maxlength="30"/><br />
						City/Town: <input type="text" name="city" maxlength="30"/><br />
						Province, Zip code: <input type="text" name="province" maxlength="20"/><br />
			*** Civil Status: 
					<select name="civilstat">
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select> 
						<br />
			*** Birthdate: 
					<input type="number" name="month" min="1" max="12" placeholder="MM"/> - 
					<input type="number" name="day" min="1" max="31" placeholder="DD"/> - 						
					<input type="number" name="year" min="1900" max="2100" placeholder="YYYY"/>
					<span id="birthErr" style="color:red;font-weight:bold;"></span>	
					<br />
			*** Email Address: <input type="text" name="email" maxlength="50"/> 
					<span id="emailErr" style="color:red;font-weight:bold;"></span>	
					<br />
			*** Contact Number: <input type="text" name="contact" maxlength="50"/> 
					<span id="contactErr" style="color:red;font-weight:bold;"></span>
					<br />
			Spouse's Name: <input type="text" name="spouse" maxlength="100"/> 
							<span id="spouseErr" style="color:red;font-weight:bold;"></span>
							<br />
			*** Mother's Maiden Name: <input type="text" name="mother" maxlength="100"/> 
							<span id="motherErr" style="color:red;font-weight:bold;"></span>
							<br />
			<br /> <br />
			<b><i>Third Step --- Enter your online account's information</b></i>
			<br /> <br />
			*** Username: <input type="text" name="username" maxlength="20"/> 
					<span id="usernameErr" style="color:red;font-weight:bold;"></span>
					<br />
			*** Password: <input type="password" name="password" maxlength="20"/> 
					<span id="passwordErr" style="color:red;font-weight:bold;"></span>
					<br />
			*** Confirm Password: <input type="password" name="confirmpassword" maxlength="20"/> 
					<span id="confirmpasswordErr" style="color:red;font-weight:bold;"></span>
					<br />
			*** Secret Question:
					<select name="secret">
						<option value="Why do birds suddenly appear?"> Why do birds suddenly appear? </option>
						<option value="How can I leave without you?"> How can I leave without you? </option>
						<option value="What makes you beautiful?"> What makes you beautiful? </option>
					</select> 
					<span id="secretErr" style="color:red;font-weight:bold;"></span>
					<br />
			*** Answer: <input type="text" name="answer" maxlength="50"/> 
					<span id="answerErr" style="color:red;font-weight:bold;"></span>
					<br />
			*** Confirm Answer: <input type="text" name="confirmanswer" maxlength="50"/> 
					<span id="confirmanswerErr" style="color:red;font-weight:bold;"></span>
					<br />
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php 
		}
		?>
	</body>
</html>
