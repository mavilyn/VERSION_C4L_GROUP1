<?php
		include("class_lib.php");
		session_start();
	
		if(isset($_POST['Submit'])){

			/************************************************************/
			//establish connections
				$connGuest = oci_connect("guestbank", "kayato1"); //cnnect with the guestbank
				$connMain = oci_connect("mainbank", "kayato1");
			/************************************************************/

			/************************************************************/
				$otheraccountnum = $_POST['accountconnected'];
				$clientaccount = $_SESSION['client']->get_accountnum();	 
				$amount = $_POST['amount'];
			/************************************************************/
				
			/***********************************************************************************/
			//get balance of the client
					$clientquery = 'SELECT * FROM account where accountnum = :clientaccount';
					$st = oci_parse($connMain, $clientquery);
					oci_bind_by_name($st, ':clientaccount', $clientaccount);
					oci_execute($st);
						
					while ($row = oci_fetch_array($st, OCI_BOTH)) {
						$clientbalance=$row[2];	//
					}	
			/***********************************************************************************/

				if($clientbalance < $_POST['amount']){
					echo "Account balance is not enough.";
				}
				
				else{
					/***********************************************************************************/
					//get branchcode of the account just to include in the record
						$query = 'select * from account where accountnum=:clientaccount';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':clientaccount', $clientaccount);
						oci_execute($stid, OCI_DEFAULT);

						while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							$branchcode = $row[5];	
						}
					/***********************************************************************************/	
						
					/***********************************************************************************/	
						//record transaction  for the client side
						$query = 'INSERT into trans values(trans__seq.nextval, :accountnum,:otheraccountnum,'."'credit'".','."'online'".','."'transfer_fund'".', SYSDATE, :transactioncost, :branchcode)';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':transactioncost', $amount);
						oci_bind_by_name($stid, ':branchcode', $branchcode);
						oci_execute($stid);	
					/***********************************************************************************/

					/***********************************************************************************/
						//update balance of the client
						$query = 'UPDATE account set balance = balance - :amount where accountnum = :accountnum';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':amount', $amount);
						oci_execute($stid);
					/***********************************************************************************/	
					
					/***********************************************************************************/	
						//transfer other account's
						//record transaction for the other account's side
						$query = 'INSERT into trans values(trans__seq.nextval, :otheraccountnum,:accountnum,'."'dedit'".','."'online'".','."'transfer_fund'".', SYSDATE, :transactioncost, :branchcode)';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':transactioncost', $amount);
						oci_bind_by_name($stid, ':branchcode', $branchcode);
						oci_execute($stid);	
					/***********************************************************************************/

					/***********************************************************************************/
					//update balance of other account
						$query = 'UPDATE account set balance = balance + :amount where accountnum = :otheraccountnum';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':amount', $amount);
						oci_execute($stid);
					/***********************************************************************************/
						echo "<script>alert('Fund has been successfully transferred.'); </script>";
					}

					oci_close($connGuest);
					oci_close($connMain);
		}
		
?>		
	
<html>
<head>
<title>Transfer Funds</title>
</head>
<script type="text/javascript" src="onlinebank.js"></script>
<body>
<?php if(isset($_SESSION['loginclient'])){
	echo "Welcome ".$_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname();
?>
		<form name = "transferFund_form" method ="post" action = "transferfund.php" onSubmit = "return checkTransfer();">
		<?php
				$conn = oci_connect("guestbank", "kayato1");
				$accountnum =  $_SESSION['client']->get_accountnum();
				$query = 'select * from accountconnected where accountnum = :accountnum';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':accountnum',$accountnum);
				oci_execute($stid, OCI_DEFAULT);
				
				print 'Account Connected: <select name="accountconnected">';
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							$otheraccountnum=$row[1];
							$preferred=$row[2];
							print'<option value="'.$otheraccountnum.'">'.$preferred." - ".$otheraccountnum; echo'</option>';
						}
				print '</select><br/>';
			oci_close($conn);
			?>
			
			Amount: <input type="text" name="amount" maxlength="10" value=""/>
			<input type="submit" name="Submit" value="Transfer Funds" />
				<?php
					$connMain = oci_connect("mainbank", "kayato1");
				
					$clientaccount = $_SESSION['client']->get_accountnum();
						$clientquery = 'SELECT * FROM account where accountnum =:clientaccount';
						$st = oci_parse($connMain, $clientquery);
						oci_bind_by_name($st, ':clientaccount', $clientaccount);
						oci_execute($st);
							
							while ($row = oci_fetch_array($st, OCI_BOTH)) {
								$clientbalance=$row[2];	//
							}	

						oci_close($connMain);

					echo "<b>BALANCE: Php".$clientbalance."</b>";
				?> 
			</form>
		<?php }else header('Location: login.php'); ?>
</body>

</html>