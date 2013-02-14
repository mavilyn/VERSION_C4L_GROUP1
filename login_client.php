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
			$query =  'SELECT * FROM client where username= :username and password= :password';
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
				$sql='select * from client where username = :username';
				$stmt = oci_parse($conn, $sql);
				oci_bind_by_name($stmt, ':username', $_POST['username']);
				oci_execute($stmt, OCI_DEFAULT);

				while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
					$fname=$row[3];
					$lname=$row[4];
					$accountnum=$row[2];
					$username=$row[0];
					$password=$row[1];
				}
				
				
				$_SESSION['loginclient']=1;
				$_SESSION['client'] = new Client($fname,$lname,$accountnum,$username,$password);
				
				header("Location: logout_client.php");
					
				exit;
			}
		}
	}
?>

<html>
	<head>
		<title>Log In Client</title>
	</head>
	
	<body>
		<form name="login_client" action="login_client.php" method="post">
		
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