<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
				if(empty($_POST['refnum'])){
					//echo "Please fill in Reference Number";
				}
				else{
						$conn = oci_connect('guestbank', 'kayato1');
						//$connMain = oci_connect("mainbank","kayato1");
						$sql="select * from billerlist where billername ='".$_POST['billerlist']."'";

						$stmt = oci_parse($conn, $sql);
						oci_execute($stmt, OCI_DEFAULT);

						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$billeraccountnum=$row[0];
						}
						
					//$conn = oci_connect('guestbank', 'kayato1');			
					$query =  'SELECT accountnum FROM billerlist where billername = :billername';
					$compiled = oci_parse($conn, $query);
					oci_bind_by_name($compiled, ':billername', $_POST['billerlist']);
					oci_execute($compiled);
					$result = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						foreach ($result as $num){
							$query =  'INSERT into addbiller_request values(:accountnum, :billeraccountnum, :billername, :refnum)';
							$compiled = oci_parse($conn, $query);
							oci_bind_by_name($compiled, ':accountnum', $_SESSION['client']->get_accountnum());
							oci_bind_by_name($compiled, ':billername', $_POST['billerlist']);
							oci_bind_by_name($compiled, ':billeraccountnum', $billeraccountnum);
							oci_bind_by_name($compiled, ':refnum', $_POST['refnum']);
							oci_execute($compiled);
						}
				}
			
			}
			/*add biller for customer*/
				
?>
<html>
	<head>
		<title>	Add Biller </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "addBiller_form" method ="post" action = "#" onsubmit="return checkAddBiller();">
			Reference Number: <input type = "text" name="refnum" maxlength="10"/><span id="refNumErr" style="color: red"> </span><br/>
			<?php
				$conn = oci_connect("guestbank", "kayato1");
				
				$query = 'select billername from billerlist';
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
			if($stid == NULL){
				echo "Execution Failed";
			}
			print 'Billername: <select name="billerlist">';
			while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
				foreach ($row as $item) {
					print'<option value="'.$item.'">'.$item; echo'</option>';
			}
			}		
			print '</select><br/>';
			?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
