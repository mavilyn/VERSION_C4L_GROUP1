<?php
	include("class_lib.php");
	session_start();
	if(isset($_SESSION['new'])){
		echo'<script>alert("Thank you! You are now registered in GuestBank Online Solutions.");</script>';
		unset($_SESSION['new']);
	}
	
	if(isset($_POST['changepassword'])){
		header("Location: changePassword.php");
	}
	
	if(isset($_POST['addbiller'])){
		header("Location: addbiller.php");
	}
	
	if(isset($_POST['paybill'])){
		header("Location: paybill.php");
	}
	
	if(isset($_POST['addother'])){
		header("Location: addOther.php");
	}
	
	if(isset($_POST['transferfund'])){
		header("Location: transferfund.php");
	}
	
	if(isset($_POST['viewbillerstat'])){
		header("Location: ViewBillerStat.php");
	}
	
	if(isset($_POST['viewaccountstat'])){
		header("Location: ViewAccountStat.php");
	}
	
	if(isset($_POST['deactivate'])){
		header("Location: deactivate.php");
	}

	if(isset($_POST['logoutclient'])){
		unset($_SESSION['loginclient']);
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
<?php if(isset($_SESSION['loginclient'])){
echo "Welcome ".$_SESSION['client']->get_username();?>
<form name= "taas" action="#" method = "POST" >
	<input type="submit" name="logoutclient" value="Log Out Client" />
	<input type="submit" name="changepassword" value="Change Password" />
	<input type="submit" name="addbiller" value="Add Biller" />
	<input type="submit" name="paybill" value="Pay Bills" />
	<input type="submit" name="addother" value="Add Account to Be Transacted With" />
	<input type="submit" name="transferfund" value="Transfer Funds" />
	<input type="submit" name="viewbillerstat" value="View Biller Request Status" />
	<input type="submit" name="viewaccountstat" value="View Account Connection Request Status" />
	<input type="submit" name="deactivation" value="Deactivate My Account" />
	
	
</form>
<?php }else header('Location: login.php'); ?>

	</body>
</html>