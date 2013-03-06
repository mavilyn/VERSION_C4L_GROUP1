<?php
		include("class_lib.php");
  		session_start();
			
				
?>
<html>
	<head>
		<title>	View Biller Status </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username();

		$conn = oci_connect("guestbank", "kayato1");
		
				$query = 'select * from addbiller_request where accountnum = '.$_SESSION['client']->get_accountnum();
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print '<table border="1">';
				print '<tr>';
				print'<th>Biller Name'; echo'</th>';
				print'<th>Reference Number'; echo'</th>';
				print'<th>Request Date'; echo'</th>';
				print'<th>Status'; echo'</th>';
				echo '</tr>';
						
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$billername=$row[2];
							$ref=$row[3];
							$requestdate=$row[4];
							if(isset($row[6]))
								$st = $row[6];
							else $st = "";
							if($st!=""){
								if($st==1) $status = "Approved";
								else if($st==0) $status = "Dispproved";
							}else $status = "Pending";
						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td>'.$billername; echo'</td>';
						print'<td>'.$ref; echo'</td>';
						print'<td>'.$requestdate; echo'</td>';
						print'<td>'.$status; echo'</td>';
					//}
					echo '</tr>';
				}
				print '</table>';
		}else header('Location: login.php'); ?>
	</body>
</html>
