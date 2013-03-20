<?php
		include("class_lib.php");
		$valid = false;
		$enroll = false;
		session_start();
		if(isset($_POST['VerifyDB'])){
			$accountnum = $_POST['accountnum'];
			$atmpin = $_POST['atmpin'];
			$connGuest = oci_connect("guestbank", "kayato1");
			
				$stid = oci_parse($connGuest,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM client
					WHERE accountnum = '.$accountnum
				);
				

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_execute($stid);
			oci_fetch($stid);
			
			oci_close($connGuest);

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
				
				oci_close($connMain);
				
				if($num_rows2>0){
						$_SESSION['accountnum']=$_POST['accountnum'];
						$valid = true;
				}
				else{
					echo '<script type=text/javascript>alert("Account number does not exist!");return false;</script>';
				}
			}
			
		}
		if(isset($_POST['checkUser'])){
				$username = $_POST['username'];
				$connGuest = oci_connect("guestbank", "kayato1");
			
				$stid = oci_parse($connGuest,
					"SELECT COUNT(*) AS NUM_ROWS3
					FROM users
					WHERE username = '".$username."'"
				);
				

			oci_define_by_name($stid, 'NUM_ROWS3', $num_rows3);
			oci_execute($stid);
			oci_fetch($stid);
				if($num_rows3>0){
					echo "Username already exist";
					$valid = true;
					//show_form();
				}
				else{
					$enroll = true;
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['password'] = $_POST['password'];
					$_SESSION['secret'] = $_POST['secret'];
					$_SESSION['answer'] = $_POST['answer'];
				}
				oci_close($connGuest);
		}
		if(isset($_POST['Submit'])){
			$connGuest = oci_connect("guestbank", "kayato1");
			$birthday = $_POST['month']."-".$_POST['day']."-".$_POST['year'];
			$homeaddress = $_POST['brgy']."".$_POST['city']."".$_POST['province'];
						$stid3 = oci_parse($connGuest,
						'INSERT INTO client VALUES(:username, :password, :accountnum, :fname, :mname, :lname, :gender, :homeaddress, :civilstat, :birthday, :email, :contact, :spouse, :mother, :secret, :answer, 0)'
								);
						oci_bind_by_name($stid3, ':username', $_SESSION['username']);
						oci_bind_by_name($stid3, ':password', md5(md5($_SESSION['password'])));
						oci_bind_by_name($stid3, ':accountnum', $_SESSION['accountnum']);
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
						oci_bind_by_name($stid3, ':secret', $_SESSION['secret']);
						oci_bind_by_name($stid3, ':answer', $_SESSION['answer']);
						oci_execute($stid3);
						
						$stid4 = oci_parse($connGuest,
						'INSERT INTO users VALUES(:username, :password,'."'client'".')'
								);
						oci_bind_by_name($stid4, ':username', $_SESSION['username']);
						oci_bind_by_name($stid4, ':password', md5(md5($_SESSION['password'])));
						oci_execute($stid4);
						
						oci_close($connGuest);
						echo'<script>alert("Thank you! You are now registered in GuestBank Online Solutions.");</script>';
						$_SESSION['loginclient']=1;
						$_SESSION['client'] = new Client($_POST['fname'],$_POST['lname'],$_SESSION['accountnum'],$_SESSION['username'],md5(md5($_SESSION['password'])));
						
						header("Location: logout_client.php");
						
						exit;
		}
		
		if(isset($_POST['Cancel'])){
			header("Location: login.php");
			session_destroy();
		}
			
				
?>
<html>
	<head>
		<title>	Enroll me! </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		
		<?php 
		if($valid == false && $enroll ==false){
		?>
			<form name = "enroll_form_first" method ="post" action = "#" onSubmit="return checkFirst();">
			  <!----> 
			<b><i>First Step --- Enter your ATM Information</i></b>
			<br /> <br />
			ATM Account Number: <input type = "text" name="accountnum" maxlength="10"/>
			<span id="accountnumErr" style="color:red;font-weight:bold;"></span>						
			<br />
			ATM Pin: <input type = "password" name="atmpin" maxlength="4">
			<span id="atmpinErr" style="color:red;font-weight:bold;"></span>		
			<br />
			<br /> <br />
			<input type="submit" name="VerifyDB" value="Next Step" />
			</form>
			<form name="cancel_form" action="#" method="POST">
				<input type="submit" name="Cancel" value="Cancel" />
			</form>
			<?php 
		}
			?>
		<?php if($valid == true){?>
			<form name = "enroll_form_second" method ="post" action = "#" onSubmit="return checkSecond();">
			<b><i>Second Step --- Enter your online account's information</b></i>
			<br /> <br />
			    Username: <input type="text" name="username" maxlength="20"/> 
					<span id="usernameErr" style="color:red;font-weight:bold;"></span>
					<br />
			    Password: <input type="password" name="password" maxlength="20"/> 
					<span id="passwordErr" style="color:red;font-weight:bold;"></span>
					<br />
			    Confirm Password: <input type="password" name="confirmpassword" maxlength="20"/> 
					<span id="confirmpasswordErr" style="color:red;font-weight:bold;"></span>
					<br />
			    Secret Question:
					<select name="secret">
						<option value="Why do birds suddenly appear?"> Why do birds suddenly appear? </option>
						<option value="How can I leave without you?"> How can I leave without you? </option>
						<option value="What makes you beautiful?"> What makes you beautiful? </option>
					</select> 
					<span id="secretErr" style="color:red;font-weight:bold;"></span>
					<br />
			    Answer: <input type="text" name="answer" maxlength="50"/> 
					<span id="answerErr" style="color:red;font-weight:bold;"></span>
					<br />
			    Confirm Answer: <input type="text" name="confirmanswer" maxlength="50"/> 
					<span id="confirmanswerErr" style="color:red;font-weight:bold;"></span>
					<br />
					<input type="submit" name="checkUser" value="Next Step" />
			</form>
			<form name="cancel_form" action="#" method="POST">
				<input type="submit" name="Cancel" value="Cancel" />
			</form>
		<?php } ?>
		<?php if($enroll == true){?>
			<form name = "enroll_form" method ="post" action = "#" onSubmit="return checkEnroll();">
			<b>Please complete the fields below for enrolling your ATM in GuestBank's Online Solutions</b>
				Fields with *** are required.
			<br /> <br />
			<b><i>Third Step --- Enter your personal information</i></b>
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
					<input type="text" name="month" min="1" max="12" placeholder="MM"/> - 
					<input type="text" name="day" min="1" max="31" placeholder="DD"/> - 						
					<input type="text" name="year" min="1900" max="2100" placeholder="YYYY"/>
					<span id="birthErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Email Address: <input type="text" name="email" maxlength="50"/> 
					<span id="emailErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Contact Number: <input type="text" name="contact" maxlength="50"/> 
					<span id="contactErr" style="color:red;font-weight:bold;"></span>
					<br />
			Spouse's Name: <input type="text" name="spouse" maxlength="100"/> 
							<span id="spouseErr" style="color:red;font-weight:bold;"></span>
							<br />
			*** Mother's Maiden Name: <input type="text" name="mother" maxlength="100"/> 
							<span id="motherErr" style="color:red;font-weight:bold;"></span>
							<br />
			<br /> <br />
			<input type="submit" name="Submit" value="Submit" />
			</form>
			<form name="cancel_form" action="#" method="POST">
				<input type="submit" name="Cancel" value="Cancel" />
			</form>
			<?php 
		}
			?>
	</body>
</html>
