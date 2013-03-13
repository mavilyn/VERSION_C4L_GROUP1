<?php
	include("class_lib.php");
	session_start();
	if(isset($_POST['changepassword'])){
		header("Location: changePasswordAdmin.php");
	}
	
	if(isset($_POST['confirmBiller'])){
		header("Location: confirmRequest.php");
	}

	if(isset($_POST['addAdmin'])){
		header("Location: add_admin.php");
	}
	
	if(isset($_POST['removeAdmin'])){
		header("Location: removeAdmin.php");
	}
	
	if(isset($_POST['confirmOther'])){
		header("Location: confirmOther.php");
	}
	
	if(isset($_POST['logoutadmin'])){
		unset($_SESSION['loginadmin']);
		session_destroy();
		header("Location: login.php");
		exit;
	}
	
	
?>

<html>
	<head>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
<?php if(isset($_SESSION['loginadmin'])){
echo "Welcome Employee ".$_SESSION['admin']->get_empid();?>
<form name= "taas" action="#" method = "POST" >
	<input type="submit" name="logoutadmin" value="Log Out Admin" />
	<input type="submit" name="changepassword" value="Change Password" />
	<input type="submit" name="confirmBiller" value="Confirm Biller Request" />
	<input type="submit" name="confirmOther" value="Confirm Account Connected Request" />
	<?php if($_SESSION['admin']->get_mgrflag()==1){
				echo '<input type = "submit" name="addAdmin" value="Add Administrator"/>';
				echo '<input type = "submit" name="removeAdmin" value="Remove Administrator"/>';
				echo '<input type = "submit" name="modifyBiller" value="Modify biller list"/>';
			}
	?>
</form>
<?php }else header('Location: login.php'); ?>

	</body>
</html>