<?php
		include 'class_lib.php';
		session_start();

  		if (isset($_POST['Submit'])) {
				$empid = $_POST['empid']; 
				$adminEmpid = $_SESSION['admin']->get_empid();
				/*
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
				*/
				$connGuest = oci_connect("guestbank", "kayato1");

									$query = 'select * from admins where empid=:empid';
									$stid = oci_parse($connGuest, $query);
									oci_bind_by_name($stid, ':empid', $adminEmpid);
									oci_execute($stid, OCI_DEFAULT);

									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[4];	
									}

						$stid = oci_parse($connGuest,
								"SELECT COUNT(*) AS NUM_ROWS
								FROM admins
								WHERE empid = :empid and branchcode = :branchcode"
							);
							
							oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
							oci_bind_by_name($stid, ':branchcode', $branchcode);
							oci_bind_by_name($stid, ':empid', $empid);
							oci_execute($stid);
							oci_fetch($stid);
							//$connMain = oci_connect("mainbank", "kayato1");
								if($num_rows>0){	

									$sql="select * from admins where empid = :empid";

									$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt, ':empid', $empid);
									oci_execute($stmt, OCI_DEFAULT);

									while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
										$username=$row[0];
									}

									$stmt2 = oci_parse($connGuest,
										'DELETE FROM admins WHERE empid = :removeAdmin');
									//$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt2, ':removeAdmin', $_POST['empid']);
									oci_execute($stmt2);
									
									$stmt3 = oci_parse($connGuest,
										'DELETE FROM users WHERE username = :username');
									//$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt3, ':username', $username);
									oci_execute($stmt3);
									
									oci_close($connGuest);
									
									echo"<script>alert('Administrator account has been successfully removed.');return false;</script>";
								}
								else{
									echo "<script>alert('Employee number not found.');</script>";
								}
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
	<?php if(isset($_SESSION['loginadmin'])){
		echo "Welcome Employee ".$_SESSION['admin']->get_empid();
	?>
		<form name = "removeAdmin_form" method ="post" action = "removeAdmin.php" onsubmit="return checkRemove();">
			<?php
				/*$conn = oci_connect("guestbank", "kayato1");

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
			print '</select><br/>';*/
			?>
			Enter Employee ID <input type="text" name="empid"/> <br />
			<input type="submit" name="Submit" value="Remove Administrator" />	
		</form>
		<?php } else header('Location: login.php');?>
	</body>
</html


