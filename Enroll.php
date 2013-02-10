<?php
  			$valid = true;
				$submit = false;
				$fname=$_POST['fname'];
				$mname=$_POST['mname'];
				$lname=$_POST['lname'];
				
				function IsFilled($fill){
					if(empty($fill)){
						return true;
					}
					return false;
				}
				if (isset($_POST['Submit'])) {
						$submit = true;
						
				}
						/*$fname=$_POST['fname'];
						$mname=$_POST['mname'];
						$lname=$_POST['lname'];
						$accountnum=$_POST['accountnum'];
						$gender=$_POST['gender'];
						$homeaddress=$_POST['homeaddress'];
						$civilstat=$_POST['civilstat'];
						//$gender=;
						//$civilstat
						if($valid == true){
							$conn = oci_connect("guestbank", "kayato1");
							$sql = 'insert into CLIENT(username,password,accountnum,fname,mname, lname, gender, homeaddress, civilstat, bday, email, contact, spouse, mother, secret, answer, activation)'. 'values(:usernameinsert into CLIENT(:username,:password,:accountnum,:fname,:mname, :lname, :gender, :homeaddress, :civilstat, :bday, :email, :contact, :spouse, :mother, :secret, :answer, 0)';
							$compiled = oci_parse($conn, $sql);
							oci_bind_by_name($compiled, ':username', username);
							oci_bind_by_name($compiled, ':password', $password);
							oci_bind_by_name($compiled, ':accountnum', $accountnum);
							oci_bind_by_name($compiled, ':fname', $fname);
							oci_bind_by_name($compiled, ':mname', $mname);
							oci_bind_by_name($compiled, ':lname', $lname);
							oci_bind_by_name($compiled, ':gender', $gender);
							oci_bind_by_name($compiled, ':homeaddress', $homeadress);
							oci_bind_by_name($compiled, ':civilstat', $civilstat);
							oci_bind_by_name($compiled, ':bday', $bday);
							oci_bind_by_name($compiled, ':email', $email);
							oci_bind_by_name($compiled, ':contact', $contact);
							oci_bind_by_name($compiled, ':spouse', $spouse);
							oci_bind_by_name($compiled, ':mother', $mother);
							oci_bind_by_name($compiled, ':secret', $secret);
							oci_bind_by_name($compiled, ':answer', $answer);
							if(!(oci_execute($compiled))){
								//header("Location: insert_masseuse.php");
							}
						//or die(header("Location: insert_masseuse.php"));
							//oci_close($conn);
						//}
						}
				}
				else{
					$submit = false;
				}*/
			
				
			?>
<html>
	<head>
		<title>	Enroll me! </title>
	</head>
	<body>
		<form name = "enroll_form" method ="post" action = "Enroll.php">
			***********************Please complete the fields below for enrolling your ATM in GuestBank's Online Solutions****************************************
			<br /> <br />
			<b><i>First Step --- Enter your ATM Information</i></b>
			<br /> <br />
			ATM Account Number: <input type = "number" name="accountnum" max="9999999999"/><br />
			ATM Pin: <input type = "password" name="atmpin" maxlength="4"><br />
			<br /> <br />
			<b><i>Second Step --- Enter your personal information</i></b>
			<br /> <br />
			First Name: <input type="text" name="fname" maxlength="50"/> *
						<?php if($submit == true && IsFilled($_POST['fname'])){
								echo "Complete this field.";
								$valid = false;
							  }
						?>
						<br /> 	
			Middle Name: <input type="text" name="mname" maxlength="50"/> *
						<?php if($submit == true && IsFilled($_POST['mname'])){
								echo "Complete this field.";
								$valid = false;
							   }
						?>
						<br />
			Last Name: <input type="text" name="lname" maxlength="50"/> *
						<?php if($submit == true && IsFilled($_POST['lname'])){
								echo "Complete this field.";
							    $valid = false;
							   }
						?>
						<br />
			Gender: <input type="radio" name="gender" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" value="female" /> 
					<label for="female">Female</label> *
						<?php if(empty($_POST['gender']) && $submit == true){
							echo "Indicate gender.";}
							$valid = false;
						?><br />
			Civil Status: 
					<select name="civilstat">
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select> *
						<?php if(empty($_POST['civilstat']) && $submit == true){
								echo "Complete this field.";
							    $valid = false;
							   }
						?>
						<br />
			Birthdate: 
					<input type="number" name="month" min="1" max="12" placeholder="MM"/> - 
					<input type="number" name="day" min="1" max="31" placeholder="DD"/> - 						
					<input type="number" name="year" min="1900" max="2100" placeholder="YYYY"/><br />
			Email Address: <input type="text" name="email" maxlength="50"/> <br />
			Contact Number: <input type="text" name="contact" maxlength="50"/> <br />
			Spouse's Name: <input type="text" name="spouse" maxlength="100"/> <br />
			Mother's Maiden Name: <input type="text" name="mother" maxlength="50"/> <br />
			<br /> <br />
			<b><i>Third Step --- Enter your online account's information</b></i>
			<br /> <br />
			Username: <input type="text" name="username" maxlength="50"/> <br />
			Password: <input type="password" name="password" maxlength="20"/> <br />
			Confirm Password: <input type="password" name="confirmpassword" maxlength="20"/> <br />
			Secret Question:
					<select name="secret">
						<option value="q1"> Why do birds suddenly appear? </option>
						<option value="q2"> How can I leave without you? </option>
						<option value="q3"> What makes you beautiful? </option>
					</select> <br />
			Answer: <input type="text" name="answer" maxlength="50"/> <br />
			Confirm Anwer: <input type="text" name="confirmanswer" maxlength="50"/> <br />
			<input type="submit" name="Submit" value="Submit" />	
		</form>
	</body>
</html>
