<?php
		include("class_lib.php");
		session_start();
	
		if(isset($_POST['Submit'])){
			$connGuest = oci_connect("guestbank", "kayato1"); //cnnect with the guestbank
			
				$sql="select * from accountconnected where preferredname = '".$_POST["accountconnected"]."'"; //get the row with the same preferred name
				$stmt = oci_parse($connGuest, $sql);
						oci_execute($stmt, OCI_DEFAULT);
						
						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$otheraccountnum=$row[1];	//
						}
				
				
					$connGuest = oci_connect("mainbank", "kayato1");
					$clientaccount = $_SESSION['client']->get_accountnum();
					$clientquery = 'SELECT * FROM account where accountnum = $clientaccount';
					$st = oci_parse($connGuest, $clientquery);
						oci_execute($st);
						
						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$clientbalance=$row[2];	//
						}
					
				
					$query =  'SELECT * FROM account where accountnum = $otheraccountnum';
					$compiled = oci_parse($connGuest, $query);
					oci_bind_by_name($compiled, ':accountnum', $otheraccountnum );
					oci_execute($compiled);
					$result = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
					foreach ($result as $num){
						if($clientbalance < $_POST['amount']){
							echo "Account balance not enough!";
						}
						else{
						$newclientbalance = $clientbalance - $_POST['amount'];
	
						$query = 'UPDATE account set balance = balance + $_POST["amount"] where accountnum = :otheraccountnum';
						$stid = oci_parse($connGuest, $query);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						$stid = oci_parse($connGuest, $query);
						$query = 'UPDATE account set balance = :newclientbalance where accountnum = :clientaccount';
						oci_bind_by_name($stid, ':newclientbalanace', $newclientbalance);
						oci_bind_by_name($stid, ':bal', $clientaccount);
						oci_execute($stid);
						oci_close($connGuest);
						
						
						}
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
				
				$query = 'select preferredname from accountconnected';
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
			if($stid == NULL){
				echo "Execution Failed";
			}
			print 'Account Connected: <select name="accountconnected">';
			while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
				foreach ($row as $item) {
					print'<option value="'.$item.'">'.$item; echo'</option>';
			}
			}		
			print '</select><br/>';
			oci_close($conn);
			?> 
			
			Amount: <input type="text" name="amount" maxlength="10"/>
			<input type="submit" name="Submit" value="Submit" />
			</form>
			<?php }else header('Location: login.php'); ?>
</body>

</html>