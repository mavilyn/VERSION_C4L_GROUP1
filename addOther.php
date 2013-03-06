<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
			if($_SESSION['client']->get_accountnum() == $_POST['otheraccountnum']){
					echo "<script>alert('Cannot add your own account!');</script>";
			}
			else{
				$connMain = oci_connect('mainbank','kayato1');
				$parsed = oci_parse($connMain, 'SELECT COUNT(*) AS NUM_ROWS
						FROM account
						WHERE accountnum = '.$_POST['otheraccountnum'].'and compName IS NULL');  //check if compname is null
				oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
				oci_execute($parsed);
				oci_fetch($parsed);
				if($num_rows == 0 ){
					echo "<script>alert('Account does not exist!');</script>";
				}
				else{
					$conn = oci_connect('guestbank', 'kayato1');
					$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
						FROM addaccntconnect_request
						WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and otheraccountnum  = '.$_POST['otheraccountnum']);
					oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
					oci_execute($parsed);
					oci_fetch($parsed);
					if($num_rows > 0){
						echo "<script>alert('You have already requested this!');</script>";
					}
					else{
						$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
							FROM accountconnected
							WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and otheraccountnum  = '.$_POST['otheraccountnum']);
						oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
						oci_execute($parsed);
						oci_fetch($parsed);
						if($num_rows > 0){
							echo "<script>alert('You are already connected to this account');</script>";
						}
						else{
							$query = oci_parse($conn, 'INSERT INTO addaccntconnect_request VALUES(:accountnum, :otheraccountnum, :preferredname)');
							oci_bind_by_name($query, ':accountnum', $_SESSION['client']->get_accountnum());
							oci_bind_by_name($query, ':otheraccountnum', $_POST['otheraccountnum']);
							oci_bind_by_name($query, ':preferredname', $_POST['preferredname']);
							oci_execute($query);
							oci_close($conn);
							echo "<script>alert('Request to add account successfully made!');</script>";
						}
					}
				}
			}
		}
			
				
?>
<html>
	<head>
		<title>	Add Account to Transfer Funds </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "addOther_form" method ="post" action = "#">
			Account Number: <input type = "text" name="otheraccountnum" maxlength="10"/><span id="otheraccntNumErr" style="color: red"> </span><br/>
			Preferred Name: <input type = "text" name="preferredname" maxlength="10"/><span id="preferredNumErr" style="color: red"> </span><br/>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
