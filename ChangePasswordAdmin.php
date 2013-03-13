<?php
			include("class_lib.php");
			session_start();
			if(isset($_POST['Submit'])){
				$username = $_SESSION['admin']->get_username();
				$newpassword = md5(md5($_POST['newpassword']));
				if($_SESSION['admin']->get_password() == md5(md5($_POST['currentpassword']))){
						$connGuest = oci_connect("guestbank", "kayato1");
						$stid3 = oci_parse($connGuest,
						'UPDATE admins set password = :password where username = :username'
						);
						oci_bind_by_name($stid3, ':username', $username);
						oci_bind_by_name($stid3, ':password', $newpassword);
						oci_execute($stid3);
						oci_close($connGuest);
						$_SESSION['admin']->set_password(md5(md5($_POST['newpassword'])));
						echo "You have successfully change your password.";
						//header("Location: admin_home.php");
				}
				else{
					echo "Please enter your correct password.";
				}
			}
			
			
	if(isset($_POST['cancel'])){
		header("Location: admin_home.php");
		exit;
	}
			
?>
<html>
	<head>
		<title>	Change Password </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<?php if(isset($_SESSION['loginadmin'])){
		echo "Welcome Employee ".$_SESSION['admin']->get_empid();?>
		<form name = "change_password_form" method ="post" action = "ChangePasswordAdmin.php" onsubmit="return checkChangePassword();">
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
		<?php } else{header("Location: login.php");}?>
	</body>
</html>
