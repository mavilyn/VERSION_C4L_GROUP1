<?php
			include("class_lib.php");
			session_start();
			if(isset($_POST['Submit'])){
				if($_SESSION['client']->get_password() == md5(md5($_POST['currentpassword']))){
						$connGuest = oci_connect("guestbank", "kayato1");
						$stid3 = oci_parse($connGuest,
						'UPDATE client set password = :password where username = :username'
						);
						oci_bind_by_name($stid3, ':username', $_SESSION['client']->get_username());
						oci_bind_by_name($stid3, ':password', md5(md5($_POST['newpassword'])));
						oci_execute($stid3);
						echo '<script type=text/javascript>alert("Password changed.")</script>';
						oci_close($connGuest);
						$_SESSION['client']->set_password(md5(md5($_POST['newpassword'])));
						header("Location: client_home.php");
				}
				else{
					echo "Please enter your correct password.";
				}
			}
			
			
	if(isset($_POST['cancel'])){
		//unset($_SESSION['loginclient']);
		//session_destroy();
		header("Location: client_home.php");
		exit;
	}
			
?>
<html>
	<head>
		<title>	Change Password </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<?php if(isset($_SESSION['loginclient'])){?>
		<form name = "change_password_form" method ="post" action = "ChangePassword.php" onsubmit="return checkChangePassword();">
			Current Password: <input type = "password" name="currentpassword" maxlength="20"/> 
			<span id="currentpasswordErr" style="color:red;font-weight:bold;"></span>	
			<br />
			New Password: <input type = "password" name="newpassword" maxlength="20"/> 
			<span id="newpasswordErr" style="color:red;font-weight:bold;"></span>	
			<br />
			Confirm New Password: <input type = "password" name="newpasswordconfirm" maxlength="20"/> 
			<span id="confirmpasswordErr" style="color:red;font-weight:bold;"></span>	
			<br />
			<input type="submit" name="Submit" value="Change my password" />	
		</form>
		<form name="logout" action="#" method="POST">
			<input type="submit" name="cancel" value="Cancel" />
		</form>
		<?php } else {header("Location: login.php");}?>
	</body>
</html>
