<?php
	include("class_lib.php");
	session_start();
	
	if(isset($_POST['login'])){
		
		if($_POST['username']==NULL || $_POST['password'] == NULL){
		?>
			<script>
				alert("Do not leave the username or password null.");
			</script>
		<?php
		}
		else{
			$conn = oci_connect('guestbank', 'kayato1');			
			$query =  'SELECT * FROM admins where username= :username and password= :password';
			$compiled = oci_parse($conn, $query);
			oci_bind_by_name($compiled, ':username', $_POST['username']);
			oci_bind_by_name($compiled, ':password', $_POST['password']);
			oci_execute($compiled);
			
			if($compiled == NULL){
				?>
				<script>
					alert("Username and password did not match");
				</script>
				<?php
			}				
			else{
				//$username = $_POST['username'];
				$sql='select * from admins where username = :username';
				$stmt = oci_parse($conn, $sql);
				oci_bind_by_name($stmt, ':username', $_POST['username']);
				oci_execute($stmt, OCI_DEFAULT);

				while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
					$mgrflag=$row[3];
					$empid=$row[2];
					$username=$row[0];
					$password=$row[1];
				}
				
				
				$_SESSION['loginadmin']=1;
				$_SESSION['admin'] = new Admin($username,$password,$empid,$mgrflag);
				
				header("Location: logout_admin.php");
					
				exit;
			}
		}
	}
?>

<html>
	<head>
		<title>Log In Admin</title>
	</head>
	
	<body>
		<form name="login_dmin" action="login_admin.php" method="post">
		
			<label for="username">Username</label>
			<input type="text" name="username" />
			<br/>

			<label for="password">Password</label>
			<input type="password" name="password" />
			<br/>

			<input type="submit" name="login" value="Log In" />
			<br/>		
		</form>
	</body>
</html>