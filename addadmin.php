<?php		
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

			if($num_rows > 0) {
				echo '<script type=text/javascript>alert("Employee already has online account!");</script>';
			}
			
			else{
				$connMain = oci_connect("mainbank", "kayato1");
			
				$stid2 = oci_parse($connMain,
					'SELECT COUNT(*) AS NUM_ROWS2 FROM employee
					WHERE empid = '.$empid
				);

				oci_define_by_name($stid2, 'NUM_ROWS2', $num_rows2);
				//oci_define_by_name($stid2, 'MGR_FLAG', $mgrflag);
				oci_execute($stid2);
				oci_fetch($stid2);
				
				if($num_rows2>0){
					//echo "ege";
					$valid = true;
					
					/*$stid3 = oci_parse($connMain,
						'SELECT manager AS mgr_flag FROM employee
						WHERE empid = '.$empid
					);
					oci_define_by_name($stid3, 'mgr_flag', $mgrflag);
					oci_execute($stid3);
					oci_fetch($stid3);*/
						$sql="select * from employee where empid =".$empid;

						$stmt = oci_parse($connMain, $sql);
						oci_execute($stmt, OCI_DEFAULT);

						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$mgrflag=$row[5];
						}
					
						$stid4 = oci_parse($connGuest,
						
						'INSERT INTO admins VALUES(:username, :password, :empid, :mgrflag)'
								);
						oci_bind_by_name($stid4, ':username', $_POST['username']);
						oci_bind_by_name($stid4, ':password', $_POST['password']);
						oci_bind_by_name($stid4, ':empid', $_POST['empid']);
						oci_bind_by_name($stid4, ':mgrflag', $mgrflag);
						oci_execute($stid4);
						echo '<script type=text/javascript>alert("Administrator has been successfully added! ");</script>';
				}
				
				else{
					echo '<script type=text/javascript>alert("Employee number does not exist!");</script>';
					$valid = false;
				}
				
				
				/*if($num_rows2>0){
					$valid = true;
						$stid3 = oci_parse($connGuest,
						'INSERT INTO admins VALUES(:username, :password, :empid, :mgrflag)'
								);
						oci_bind_by_name($stid3, ':username', $_POST['username']);
						oci_bind_by_name($stid3, ':password', $_POST['password']);
						oci_bind_by_name($stid3, ':empid', $_POST['empid']);
						oci_bind_by_name($stid3, ':mgrflag', $mgrflag);
						echo '<script type=text/javascript>alert("Administrator has been successfully added! ")';
				}
				else{
					echo '<script type=text/javascript>alert("Employee number does not exist!");</script>';
					//return false;
					$valid = false;
				}*/
			}
		}
			/*$submit = false;
			$valid = true;
			$checkid = true;
			$checkusername = true;
			$checkpass = true;
			$usertype = "emp";
				function IsFilled($fill){
							if(empty($fill)){
								return true;
							}
							return false;
				}
				if (isset($_POST['Submit'])) {
						$submit = true;
						if(IsFilled($_POST['empid'])){
								$checkid = false;
								$valid = false;
						}	
						if(IsFilled($_POST['username'])){
								$checkusername = false;
								$valid = false;
						}
						if(IsFilled($_POST['password'])){
								$checkpass = false;
								$valid = false;
						}
				}
			if($valid==true && $submit==true){	
				
				$connguest = oci_connect("guestbank", "kayato1");
				
				$addUsersSql = 'insert into users(username,password,usertype)'. 'values(:username, :password, :type)';
				$compiled = oci_parse($connguest, $addUsersSql);
				
				oci_bind_by_name($compiled, ':username', $_POST['username']);
				oci_bind_by_name($compiled, ':password', $_POST['password']);
				oci_bind_by_name($compiled, ':type', $usertype);
				//oci_bind_by_name($compiled, ':empid', $_POST['empid']);
				
				if(!(oci_execute($compiled))){
						//header("Location: insert_masseuse.php");
				}
				
				
				//$sql = 'insert into admins(username,password,empid,mgrflag)'. 'values(:username, :password, :empid, 1)';
				//$compiled = oci_parse($connguest, $sql);
				
				/*set manager flag here*/
		//		if(!(oci_execute($compiled))){
						//header("Location: insert_masseuse.php");
			//	}*/
			
			
?>
<html>
	<head>
		<title>	Add admin </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
		<form name = "addadmin_form" method ="post" action = "addadmin.php" onsubmit="return check_addAdmin();">
			Employee Number: <input type="text" maxlength="10" name="empid"/> 
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
			<input type="submit" name="Submit" value="Add admin" />	
		</form>
	</body>
</html>
