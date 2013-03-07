<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
			$preferredname = $_POST['preferredname'];
			if($_SESSION['client']->get_accountnum() == $_POST['otheraccountnum']){		//check if own account
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
				if($num_rows == 0 ){											//if account is company or not exist
					echo "<script>alert('Account does not exist!');</script>";
				}
				else{
					$conn = oci_connect('guestbank', 'kayato1');
					$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
						FROM addaccntconnect_request
						WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and otheraccountnum  = '.$_POST['otheraccountnum'].'and appDisflag is NULL');
					oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
					oci_execute($parsed);
					oci_fetch($parsed);
					if($num_rows > 0){
						echo "<script>alert('Request to get connected to this account is pending.');</script>";
					}
					else{
						$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
							FROM accountconnected
							WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and otheraccountnum  = '.$_POST['otheraccountnum']);
						oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
						oci_execute($parsed);
						oci_fetch($parsed);
						if($num_rows > 0){
							echo "<script>alert('You are already connected to this account.');</script>";
						}
						else{
							//checking if dissapprove and ask user if he/she wants to request again
							/*$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
								FROM addaccntconnect_request
								WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and otheraccountnum  = '.$_POST['otheraccountnum'].'and appDisflag = 0');
							oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);		
							oci_execute($parsed);
							oci_fetch($parsed);
							if($num_rows > 0){/*
								print "<script>var confirmation = confirm('Request has been disapprove. Want to request again?');
								if(confirmation == true){".$parsed = oci_parse($conn, 'update addaccntconnect_request set appdisdate = null, appdisflag=null');
								oci_execute($parsed);print"} </script>";
								*/
								/*
								**************check if
								
							}
							else{*/
								$query = oci_parse($conn, 'INSERT INTO addaccntconnect_request(accountnum, otheraccountnum,preferredname,requestdate) VALUES(:accountnum, :otheraccountnum, :preferredname, SYSDATE)');
								oci_bind_by_name($query, ':accountnum', $_SESSION['client']->get_accountnum());
								oci_bind_by_name($query, ':otheraccountnum', $_POST['otheraccountnum']);
								if($preferredname != ""){
									oci_bind_by_name($query, ':preferredname', $preferredname);
								}
								else{
									$unnamed = "<unnamed>";
									echo $unnamed;
									oci_bind_by_name($query, ':preferredname', $unnamed);
								}
								oci_execute($query);
								oci_close($conn);
								echo "<script>alert('Request to add account successfully made!');</script>";
							//}
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
		<form name = "addOther_form" method ="post" action = "addOther.php" onSubmit="return checkAddAccount();">
			Account Number: <input type = "text" name="otheraccountnum" maxlength="10"/><span id="otheraccountnumErr" style="color: red"> </span><br/>
			Preferred Name: <input type = "text" name="preferredname" maxlength="10"/><span id="preferredNumErr" style="color: red"> </span><br/>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
