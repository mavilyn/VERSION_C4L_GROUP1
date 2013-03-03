<?php
	include("class_lib.php");
	session_start();
	if(isset($_POST['changepassword'])){
		header("Location: changePasswordAdmin.php");
	}

	if(isset($_POST['logoutclient'])){
		unset($_SESSION['loginadmin']);
		session_destroy();
		header("Location: login_admin.php");
		exit;
	}
?>

<html>
	<head>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
<?php if(isset($_SESSION['loginadmin'])){
echo "Welcome ".$_SESSION['admin']->get_username();?>
<form name= "taas" action="#" method = "POST" >
	<input type="submit" name="logoutclient" value="Log Out Admin" />
	<input type="submit" name="changepassword" value="Change Password" />
</form>
<?php }
else echo "You're not logged in." ?>

	</body>
</html>