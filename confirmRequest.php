<?php
		include("class_lib.php");
  		session_start();
			/*add biller for customer*/
			if (isset($_POST['Submit'])) {
				
			
				$conn = oci_connect('guestbank', 'kayato1');
				if(isset($_POST['disapproved'])){
					foreach($_POST['disapproved'] as $disapprove){
						$token = strtok($disapprove, " ");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
						  $accountnum = $token;
						  $token = strtok(" ");
						  $billeraccountnum = $token;
						  $token = strtok(" ");
						  $refnum = $token;
						  //} 
						$aquery = 'UPDATE ADDBILLER_REQUEST SET appDisDate = SYSDATE, appDisFlag = 0 where refnum = :refnum and billeraccountnum = :billeraccountnum and accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $refnum);
						oci_bind_by_name($sid, ':billeraccountnum', $billeraccountnum);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_execute($sid);
					}
				}
				if(isset($_POST['approved'])){
					foreach($_POST['approved'] as $approve){
					
						$token = strtok($approve, " ");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
						  $accountnum = $token;
						  $token = strtok(" ");
						  $billeraccountnum = $token;
						  $token = strtok(" ");
						  $refnum = $token;
						  $token = strtok(" ");
						  $billername = $token;
						/*$query =  'SELECT accountnum FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$accountnum = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						*/
						/*$query =  'SELECT * FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$billername = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);*/
						/*
						foreach ($accountnum as $acctnum){
							foreach ($billername as $biller){
								
							}
						}*/
						
					/*while ($row = oci_fetch_array($compiled, OCI_BOTH)) {
							$biller=$row[2];
							$acctnum=$row[0];
						}
						
						$sql="select * from billerlist where billername ='".$biller."'";

						$stmt = oci_parse($conn, $sql);
						oci_execute($stmt, OCI_DEFAULT);

						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$billeraccountnum=$row[0];
						}*/

						$connMain = oci_connect("mainbank", "kayato1");
						
						$query =  'INSERT into current_biller values(:accountnum, :billeraccountnum, :billername, :refnum)';
						$compiled = oci_parse($connMain, $query);
						oci_bind_by_name($compiled, ':accountnum', $accountnum);
						oci_bind_by_name($compiled, ':billeraccountnum', $billeraccountnum);
						oci_bind_by_name($compiled, ':billername', $billername);
						oci_bind_by_name($compiled, ':refnum', $refnum);
						oci_execute($compiled);
						
								
						$aquery = 'UPDATE ADDBILLER_REQUEST SET appDisDate = SYSDATE, appDisFlag = 1 where refnum = :refnum and billeraccountnum = :billeraccountnum and accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $refnum);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':billeraccountnum', $billeraccountnum);
						oci_execute($sid);
						
						
					}
				}//approve = 1
			}				
?>
<html>
	<head>
		<title>	Confirm Request </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginadmin'])){
		echo "Welcome ".$_SESSION['admin']->get_username();
	?>
		<form name = "confirmRequest_form" method ="post" action = "confirmRequest.php">			
			<?php

				$conn = oci_connect("guestbank", "kayato1");
		
			
				$empid = $_SESSION['admin']->get_empid();

				$connMain = oci_connect("mainbank", "kayato1");
					$query = 'select * from admins where empid=:empid';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':empid', $empid);
					oci_execute($stid, OCI_DEFAULT);

					while ($row = oci_fetch_array($stid, OCI_BOTH)) {
						$branchcode = $row[4];	
					}




				$query = 'select * from addbiller_request where appDisFlag IS NULL and accountnum IN (SELECT accountnum
					from client where branchcode = :adminbranch)';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':adminbranch', $branchcode);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print '<table border="1">';
				print '<tr>';
				print'<th>Account Number'; echo'</th>';
				print'<th>Biller Account Number'; echo'</th>';
				print'<th>Biller Name'; echo'</th>';
				print'<th>Reference Number'; echo'</th>';
				print'<th>Approve'; echo'</th>';
				print'<th>Disapprove'; echo'</th>';
				echo '</tr>';
						$count = 0;
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$accountnum=$row[0];
							$billeraccountnum=$row[1];
							$billername=$row[2];
							$ref=$row[3];

						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td>'.$row[0]; echo'</td>';
						print'<td>'.$row[1]; echo'</td>';
						print'<td>'.$row[2]; echo'</td>';
						print'<td>'.$row[3]; echo'</td>';
					//}
					print '<td>';
					print '<input type="checkbox" id="approve'.$count.'" name="approved[]" value="'.$row[0].' '.$row[1].' '.$row[3].' '.$row[2].'" onclick =  "return disableDisapprove();"/>';
					echo '</td>';
					print '<td>';
					print '<input type="checkbox" id="disapprove'.$count.'" name="disapproved[]" value="'.$row[0].' '.$row[1].' '.$row[3].'" onclick =  "return  disableApprove();"/>';
					echo '</td>';
					echo '</tr>';
					$count++;
				}
				print '</table>';?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php } else header('Location: login.php');?>
	</body>
</html>
