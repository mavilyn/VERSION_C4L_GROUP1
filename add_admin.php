<?php
		include("class_lib.php");
		session_start();
		$empid = "";
		if(isset($_POST['Submit'])){
			$empid = $_POST['empid'];
			$connGuest = oci_connect("guestbank", "kayato1");
			
				$stid = oci_parse($connGuest,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM admins
					WHERE empid = '.$empid
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_execute($stid);
			oci_fetch($stid);
			oci_close($connGuest);
			if($num_rows > 0) {
				echo '<script type=text/javascript>alert("Employee already has an online account!");</script>';
			}
			
			else{
				
				$connMain = oci_connect("mainbank", "kayato1");
				$adminEmpid = $_SESSION['admin']->get_empid();

				$query = 'select * from employee where empid=:empid';
									$stid = oci_parse($connMain, $query);
									oci_bind_by_name($stid, ':empid', $adminEmpid);
									oci_execute($stid, OCI_DEFAULT);

									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[4];	
									}

				$stid2 = oci_parse($connMain,
					'SELECT COUNT(*) AS NUM_ROWS2 FROM employee
					WHERE empid = :empid and branchcode = :branchcode'
				);

				oci_define_by_name($stid2, 'NUM_ROWS2', $num_rows2);
				oci_bind_by_name($stid, ':empid', $empid);
				oci_execute($stid2);
				oci_fetch($stid2);
				
				oci_close($connMain);
				
				if($num_rows2>0){
						$connGuest = oci_connect("guestbank", "kayato1");
						
						$stid = oci_parse($connGuest,
								"SELECT COUNT(*) AS NUM_ROWS3
								FROM users
								WHERE username = '".$_POST['username']."'"
							);
							

							oci_define_by_name($stid, 'NUM_ROWS3', $num_rows3);
							oci_execute($stid);
							oci_fetch($stid);
							$connMain = oci_connect("mainbank", "kayato1");
								if($num_rows3>0){
									echo "Username already exists.";	
								}
								
								else{
									
									$sql="select * from employee where empid =".$empid;

									$stmt = oci_parse($connMain, $sql);
									oci_execute($stmt, OCI_DEFAULT);

									while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
										$mgrflag=$row[6];
									}
									
									$password = md5(md5($_POST['password']));
	
									$stid5 = oci_parse($connGuest,
									'INSERT INTO users VALUES(:username, :password,'."'admin'".')'
											);
									oci_bind_by_name($stid5, ':username', $_POST['username']);
									oci_bind_by_name($stid5, ':password', $password);
									oci_execute($stid5);
									oci_close($connGuest);
									
									
									$empid = $_POST['empid'];

									$connMain = oci_connect("mainbank", "kayato1");
									$query = 'select * from employee where empid=:empid';
									$stid = oci_parse($connMain, $query);
									oci_bind_by_name($stid, ':empid', $empid);
									oci_execute($stid, OCI_DEFAULT);
			
									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[5];	
									}	

									$connGuest = oci_connect("guestbank", "kayato1");

									$stid4 = oci_parse($connGuest,'INSERT INTO admins VALUES(:username, :password, :empid, :mgrflag, :branchcode)'
											);
									oci_bind_by_name($stid4, ':username', $_POST['username']);
									oci_bind_by_name($stid4, ':password',$password);
									oci_bind_by_name($stid4, ':empid', $empid);
									oci_bind_by_name($stid4, ':mgrflag', $mgrflag);
									oci_bind_by_name($stid4, ':branchcode', $branchcode);
									oci_execute($stid4);
									
									echo '<script type=text/javascript>alert("Administrator has been successfully added! ");</script>';
									
								}
						oci_close($connGuest);
						oci_close($connMain);
					
			}
				
				else{
					echo '<script type=text/javascript>alert("Employee number does not exist!");</script>';
					$valid = false;
				}
			}
			
			unset($_POST['Submit']);
		}		
?>
<html>
	<head>
		<title>	Add admin </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<?php if(isset($_SESSION['loginadmin'])){
echo "Welcome ".$_SESSION['admin']->get_username();?>
		<form name = "addadmin_form" method ="post" action = "add_admin.php" onsubmit="return check_addAdmin();">
			Employee Number: <input type="text" maxlength="10" name="empid" value="<?php echo $empid;?>"/> 
					<span id="empidErr" style="color:red;font-weight:bold;"></span>
			<br />
			Username: <input type="text" maxlength="20" name="username"/>
					<span id="usernameErr" style="color:red;font-weight:bold;"></span>
			<br />
			Password: <input type="password" name="password" maxlength="20"/> 
					<span id="passwordErr" style="color:red;font-weight:bold;"></span>
					<br />
			Confirm Password: <input type="password" name="confirmpassword" maxlength="20"/> 
					<span id="confirmpasswordErr" style="color:red;font-weight:bold;"></span>
					<br />
			<input type="submit" name="Submit" value="Add Admin" />	
		</form>
<?php }else header('Location: login.php'); ?>
	</body>
</html>