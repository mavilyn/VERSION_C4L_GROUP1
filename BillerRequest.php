<?php
  		
			/*add biller for customer*/
			if (isset($_POST['Submit'])) {
				$conn = oci_connect('guestbank', 'kayato1');
				if(isset($_POST['disapproved'])){
					foreach($_POST['disapproved'] as $disapprove){
						$aquery = 'DELETE from ADDBILLER_REQUEST where refnum = :refnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $disapprove);
						oci_execute($sid);
					}
				}
				if(isset($_POST['approved'])){
					foreach($_POST['approved'] as $approve){
					
						$query =  'SELECT accountnum FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$accountnum = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						
						$query =  'SELECT billername FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$billername = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						
						foreach ($accountnum as $acctnum){
							foreach ($billername as $biller){
								
							}
						}
						
						$query =  'INSERT into biller values(:accountnum, :billername, :refnum)';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':accountnum', $acctnum);
						oci_bind_by_name($compiled, ':billername', $biller);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						
								
						$aquery = 'DELETE from ADDBILLER_REQUEST where refnum = :refnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $approve);
						oci_execute($sid);
						
						
					}
				}
			}				
?>
<html>
	<head>
		<title>	Confirm Request </title>
	</head>
	<body>
		<form name = "confirmRequest_form" method ="post" action = "confirmRequest.php">			
			<?php

				$conn = oci_connect("guestbank", "kayato1");
		
				$query = 'select * from addbiller_request';
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print '<table border="1">';
				print '<tr>';
				print'<th>Account Number'; echo'</th>';
				print'<th>Biller Name'; echo'</th>';
				print'<th>Reference Number'; echo'</th>';
				print'<th>Approve'; echo'</th>';
				print'<th>Disapprove'; echo'</th>';
				echo '</tr>';
				while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
					print '<tr>';
					foreach ($row as $item) {
						print'<td>'.$item; echo'</td>';
					}
					print '<td>';
					print '<input type="checkbox" name="approved[]" value="'.$item.'"/>';
					echo '</td>';
					print '<td>';
					print '<input type="checkbox" name="disapproved[]" value="'.$item.'"/>';
					echo '</td>';
					echo '</tr>';
				}
				print '</table>';?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
	</body>
</html>
