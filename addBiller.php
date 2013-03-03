<?php
  		if (isset($_POST['Submit'])) {
				if(empty($_POST['refnum'])){
				//	echo "Please fill in Reference Number";
				}
				else{
					$conn = oci_connect('guestbank', 'kayato1');			
					$query =  'SELECT accountnum FROM billerlist where billername = :billername';
					$compiled = oci_parse($conn, $query);
					oci_bind_by_name($compiled, ':billername', $_POST['billerlist']);
					oci_execute($compiled);
					$result = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						foreach ($result as $num){
							$query =  'INSERT into addbiller_request values(:accountnum, :billername, :refnum)';
							$compiled = oci_parse($conn, $query);
							oci_bind_by_name($compiled, ':accountnum', $num);
							oci_bind_by_name($compiled, ':billername', $_POST['billerlist']);
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
		<form name = "addBiller_form" method ="post" action = "addBiller.php" onsubmit="return checkAddBiller();">
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
	</body>
</html>
