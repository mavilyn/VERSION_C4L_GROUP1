<?php
  		if (isset($_POST['Submit'])) {
				//$removeAdmin = $_POST['admin']; 
					$connGuest = oci_connect("guestbank", "kayato1");
					$stmt = oci_parse($connGuest,
						'DELETE FROM admins WHERE empid = :removeAdmin');
					//$stmt = oci_parse($connGuest, $sql);
					oci_bind_by_name($stmt, ':removeAdmin', $_POST['admin']);
					oci_execute($stmt);
		}
					
					/*while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						 $empname = $row[1]." ".$row[2]." ".$row[3];
					}
					$msg = "fef";
				echo '<script> var confirmRemove = confirm("Are you sure you want to remove '.$empname.' with employee number ' .$removeAdmin.' as administrator?");
						
						</script>';*/
						//echo $msg;
			
			/*add biller for customer*/

?>
<html>
	<head>
		<title>	Remove Admin </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<form name = "removeAdmin_form" method ="post" action = "removeAdmin.php" onsubmit="return checkRemove();">
			<?php
				$conn = oci_connect("guestbank", "kayato1");

				$query = 'select empid from admins';
				
				$stid = oci_parse($conn, $query);
				oci_execute($stid);

				
			if($stid == NULL){
				echo "Execution Failed";
			}
			print 'Employee ID: <select name="admin">';
			while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
				foreach ($row as $item) {
					$connMain = oci_connect("mainbank", "kayato1");
					$sql = ('select * from employee where empid = ' .$item);
					$stmt = oci_parse($connMain, $sql);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						 $empname = $row[1]." ".$row[2]." ".$row[3];
					}
					$empname = $empname. " (ID: " .$item." )";
					print'<option value="'.$item.'">'.$empname; echo'</option>';
				}
			}		
			print '</select><br/>';
			?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
	</body>
</html

