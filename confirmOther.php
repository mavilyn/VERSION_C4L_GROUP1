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
						  $otheraccountnum = $token;
						  
						$aquery = 'UPDATE ADDACCNTCONNECT_REQUEST SET appDisDate = SYSDATE, appDisFlag = 0 where accountnum = :accountnum and otheraccountnum = :otheraccountnum and accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':otheraccountnum', $otheraccountnum);
						oci_execute($sid);
						  
						/*$aquery = 'DELETE from ADDACCNTCONNECT_REQUEST where otheraccountnum = :otheraccountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':otheraccountnum', $disapprove);
						oci_execute($sid);*/
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
						  $otheraccountnum = $token;
						  $token = strtok(" ");
						  $preferred = $token;
					
						$query =  'INSERT into accountconnected values(:accountnum, :otheraccountnum, :preferredname)';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':accountnum', $accountnum);
						oci_bind_by_name($compiled, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($compiled, ':preferredname', $preferred);
						oci_execute($compiled);
					
						$aquery = 'UPDATE ADDACCNTCONNECT_REQUEST SET appDisDate = SYSDATE, appDisFlag = 1 where accountnum = :accountnum and otheraccountnum = :otheraccountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':otheraccountnum', $otheraccountnum);
						oci_execute($sid);
					
						oci_close($conn);
					}
				}
			}				
?>
<html>
	<head>
		<title>	Confirm Request </title>
	</head>
	<body>
	<?php if(isset($_SESSION['loginadmin'])){
		echo "Welcome ".$_SESSION['admin']->get_username();
	?>
		<form name = "confirmOther_form" method ="post" action = "confirmOther.php">			
			<?php

				$conn = oci_connect("guestbank", "kayato1");
						
					$empid = $_SESSION['admin']->get_empid();
				
				//	$connMain = oci_connect("mainbank", "kayato1");
					$query = 'select * from admins where empid=:empid';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':empid', $empid);
					oci_execute($stid, OCI_DEFAULT);

					while ($row = oci_fetch_array($stid, OCI_BOTH)) {
						$branchcode = $row[4];	
					}	


				$query = 'select * from addaccntconnect_request where appDisFlag IS NULL and accountnum IN (SELECT 
					accountnum from client where branchcode = :adminbranch)';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':adminbranch', $branchcode);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				print '<table border="1">';
				print '<tr>';
				print'<th>Account Number'; echo'</th>';
				print'<th>Other Account Number'; echo'</th>';
				print'<th>Preferred Name'; echo'</th>';
				print'<th>Approve'; echo'</th>';
				print'<th>Disapprove'; echo'</th>';
				echo '</tr>';
						
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$accountnum=$row[0];
							$otheraccountnum=$row[1];
							$preferredname=$row[2];
						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td>'.$row[0]; echo'</td>';
						print'<td>'.$row[1]; echo'</td>';
						print'<td>'.$row[2]; echo'</td>';
					//}
					print '<td>';
					print '<input type="checkbox" name="approved[]" value="'.$row[0].' '.$row[1].' '.$row[2].'"/>';
					echo '</td>';
					print '<td>';
					print '<input type="checkbox" name="disapproved[]" value="'.$row[0].' '.$row[1].' '.$row[2].'"/>';
					echo '</td>';
					echo '</tr>';
				}
				print '</table>';?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php } else header('Location: login.php');?>
	</body>
</html>
