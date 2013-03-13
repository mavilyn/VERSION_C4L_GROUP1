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
	<?php if(isset($_SESSION['loginclient'])){
		echo "Welcome ".$_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname();

		$conn = oci_connect("guestbank", "kayato1");
		
				$query = 'select * from addaccntconnect_request where accountnum = '.$_SESSION['client']->get_accountnum();
				$stid = oci_parse($conn, $query);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print '<table border="1">';
				print '<tr>';
				print'<th>Other Account Number'; echo'</th>';
				print'<th>Preferred Name'; echo'</th>';
				print'<th>Request Date'; echo'</th>';
				print'<th>Status'; echo'</th>';
				echo '</tr>';
						
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$preferred=$row[2];
							$otheraccountnum=$row[1];
							$requestdate=$row[3];
							if(isset($row[5]))
								$st = $row[5];
							else $st = "";
							if($st!=""){
								if($st==1) $status = "Approved";
								else if($st==0) $status = "Dispproved";
							}else $status = "Pending";
						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td>'.$otheraccountnum; echo'</td>';
						print'<td>'.$preferred; echo'</td>';
						print'<td>'.$requestdate; echo'</td>';
						print'<td>'.$status; echo'</td>';
					//}
					echo '</tr>';
				}
				print '</table>';
		}else header('Location: login.php'); ?>
	</body>
</html>
