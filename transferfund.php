<?php
		include("class_lib.php");
		session_start();
	
		if(isset($_POST['Submit'])){
			
			
				/*$sql="select otheraccountnum from accountconnected where accountnum = '".$_POST["accountconnected"]."'"; //get the row with the same accountnum
				$stmt = oci_parse($connGuest, $sql);
						oci_execute($stmt);
						
						$row = oci_fetch_array($stmt, OCI_BOTH);
						$otheraccountnum=$row;	*/
						
				
				
					$conn = oci_connect("mainbank", "kayato1"); //connect with mainbank database
					$clientaccount = $_SESSION['client']->get_accountnum();
					$clientquery = 'SELECT * FROM account where accountnum = :accountnum';
					$st = oci_parse($conn, $clientquery);
					oci_bind_by_name($st, ':accountnum', $clientaccount);
					oci_execute($st, OCI_DEFAULT);
						
						while ($row = oci_fetch_array($st, OCI_BOTH)) {
							$clientbalance=$row[2];	//
						}
					
					
					$amount = $_POST["amount"];
					
						if($clientbalance < $amount){
							echo "You do not have enough balance in your account.";
						}
						else{
						
	
						$query = 'UPDATE account set balance = balance + :pera where accountnum = :accountnum';
						$setbal = oci_parse($conn, $query);
						oci_bind_by_name($setbal, ':pera', $amount);
						oci_bind_by_name($setbal, ':accountnum', $_POST["accountconnected"]);
						oci_execute($setbal);
						
						$query = 'UPDATE account set balance = balance - :pera where accountnum = :accountnum';
						$setbal = oci_parse($conn, $query);
						oci_bind_by_name($setbal, ':pera', $amount);
						oci_bind_by_name($setbal, ':accountnum', $clientaccount);
						oci_execute($setbal);
						
						
						echo "Successfully transferred P".$amount." to account number: " .$_POST["accountconnected"];
					
			
						
						$query = 'INSERT into trans values(:accountnum, trans_seq.nextval,'."'online'".', SYSDATE, :transactioncost, '."'credit'".')';
						$trans = oci_parse($conn, $query);
						oci_bind_by_name($trans, ':accountnum', $clientaccount);
						oci_bind_by_name($trans, ':transactioncost', $_POST['amount']);
						oci_execute($trans);
						oci_close($conn);
						
						$connGuest = oci_connect("guestbank", "kayato1");
						$query = 'INSERT into transact_transfer values(:accountnum,:otheracc,'."'online'".',SYSDATE, :transactioncost,'."'credit'".',trans_seq.nextval  )';
						$trans = oci_parse($connGuest, $query);
						oci_bind_by_name($trans, ':accountnum', $clientaccount);
						oci_bind_by_name($trans, ':otheracc', $_POST['accountconnected']);
						oci_bind_by_name($trans, ':transactioncost', $_POST['amount']);
						oci_execute($trans);
						oci_close($connGuest);
						
						}
						
		}
?>		
	
<html>
<head>
<title>Transfer Funds</title>
</head>
<script type="text/javascript" src="onlinebank.js"></script>
<body>
<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "transferFund_form" method ="post" action = "#">
		<?php
				$conn = oci_connect("guestbank", "kayato1");
				
				$query = 'select otheraccountnum from accountconnected';
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
				
				
			if($stid == NULL){
				
				echo "Execution Failed";
			}
			print 'Account Connected: <select name="accountconnected">';
			while ($row = oci_fetch_array($stid, OCI_BOTH)) {
				foreach ($row as $item) {
					print'<option value="'.$item.'">'.$item; echo'</option>';
			}
			}		
			print '</select><br/>';
			?> 
			
			Amount: <input type="text" name="amount"/>
			<input type="submit" name="Submit" value="Submit" />
			</form>
			<?php }else header('Location: login.php'); ?>
</body>

</html>