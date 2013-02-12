<?php
  	if(isset($_POST['Submit'])){
			$opeFlag = 0;			//set deactivate
			if($_POST['operation']=="activate"){
				$opeFlag = 1;
			}
			
			$accountnum = $_POST['accountnum'];
			
			$connGuest = oci_connect("guestbank", "kayato1");
			
				$stid = oci_parse($connGuest,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM client
					WHERE accountnum = '.$accountnum
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_execute($stid);
			oci_fetch($stid);

			if($num_rows > 0) {
			
					$stid3 = oci_parse($connGuest,
						'UPDATE client set activation = :opeFlag where accountnum = :accountnum'
						);
						oci_bind_by_name($stid3, ':accountnum', $_POST['accountnum']);
						oci_bind_by_name($stid3, ':opeFlag', $opeFlag);
						oci_execute($stid3);
						echo '<script type=text/javascript>alert("Account with account number ' .$accountnum.' is '.$_POST['operation'].'d")</script>';
			}
			else{
				echo "Cannot ".$_POST['operation'].". Account number does not exist!" ;
			}
		}
			
				
?>
<html>
	<head>
		<title>	Activate or Deactivate Account </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<form name = "activation_form" method ="post" action = "ActivateOrDeactivate.php" onSubmit="return checkActivation();">
			Account number: <input type = "text" name="accountnum" maxlength="10" />
			<span id="accountnumErr" style="color:red;font-weight:bold;"></span>	
			<br />
				<select name="operation">
					<option value="activate"> Activate </option>
					<option value="deactivate"> Deactivate </option>
				</select>
				<input type="submit" name="Submit" value="Change account's activation" />	
		</form>
	</body>
</html>
