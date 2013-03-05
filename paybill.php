<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
			
				
				$conn = oci_connect("mainbank", "kayato1");
				
				$query = 'select balance from account where accountnum=:accountnumber';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':accountnumber', $_SESSION['client']->get_accountnum());
				oci_execute($stid);
				
				while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
					foreach($row as $temp){
					}
						$balance = $temp;
				}
				
				if($balance >= $_POST['Amount']){
					$query = 'INSERT into trans values(:accountnum, trans_seq.nextval,'."'online'".', SYSDATE, :transactioncost, '."'credit'".')';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnum', $_SESSION['client']->get_accountnum());
					oci_bind_by_name($stid, ':transactioncost', $_POST['Amount']);
					//oci_bind_by_name($stid, ':transtype', "online");
					//oci_bind_by_name($stid, ':transop', "credit");
					oci_execute($stid);
				}
				else{
					echo 'You dont have enough balance';
				}
				
				oci_close($conn);
				
				if($balance >= $_POST['Amount']){
					$conn = oci_connect("mainbank", "kayato1");
					$newbalance = $balance - $_POST['Amount'];
					$query = 'UPDATE account set balance = :bal where accountnum = :accountnumber';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnumber', $_SESSION['client']->get_accountnum());
					oci_bind_by_name($stid, ':bal', $newbalance);
					oci_execute($stid);
					oci_close($conn);
				}
				
				
				
				
			/*
				check if balance in account table (mainbank) is greater than or equal.
					if not, prompt message 
					else see comment below
			*/			
			/*
				insert to trans table (mainbank), insert to transact_transfer (guestbank) 
			*/
		}
			
				
?>
<html>
	<head>
		<title>	Pay Bills </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "paybill_form" method ="post" action = "#" >
			<?php
				/*hindi ko maisip itsura pero dropdown tapos amount ay textfield*/
				$tempaccountnum = $_SESSION['client']->get_accountnum();
				$conn = oci_connect("guestbank", "kayato1");
				
				$query = 'select billername from biller where accountnum=:accountnumber';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':accountnumber', $tempaccountnum);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print 'Billername: <select name="billers">';
				while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
					foreach ($row as $item) {
						print'<option value="'.$item.'">'.$item; echo'</option>';
					}
				}
				print '</select><br/>';
				
				oci_close($conn);
				
			?>
			Amount<input type="text" name="Amount"/><br/>
			<input type="submit" name="Submit" value="Pay Bills" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
