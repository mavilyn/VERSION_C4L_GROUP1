<?php
	include("class_lib.php");
	session_start();
	if(isset($_POST['changepassword'])){
		header("Location: changePassword.php");
	}

	if(isset($_POST['logoutclient'])){
		unset($_SESSION['loginclient']);
		session_destroy();
		header("Location: login_client.php");
		exit;
	}
?>

<html>
	<head>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
<?php if(isset($_SESSION['loginclient'])){
echo "Welcome ".$_SESSION['client']->get_username();?>
<form name= "taas" action="#" method = "POST" >
	<input type="submit" name="logoutclient" value="Log Out Client" />
	<input type="submit" name="changepassword" value="Change Password" />
</form>
<?php }
else echo "You're not logged in." ?>

	</body>
</html>