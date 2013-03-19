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
			$stid = oci_parse($conn,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM users
					WHERE  username= :username and password= :password'
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_bind_by_name($stid, ':username', $_POST['username']);
			$encrypt = md5(md5($_POST['password']));
			echo $encrypt;
			oci_bind_by_name($stid, ':password', $encrypt);
			oci_execute($stid);
			oci_fetch($stid);

			
			if($num_rows == 0){
				?>
				<script>
					alert("Username and password did not match");
				</script>
				<?php
			}							
			else{
				$query='select * from users where username = :username';
				$parsedQuery = oci_parse($conn, $query);
				oci_bind_by_name($parsedQuery, ':username', $_POST['username']);
				oci_execute($parsedQuery);
				/*$parsedQuery=($conn, 'select * from users where username = :username');
				oci_bind_by_name($parsedQuery, ':username', $_POST['username']);
				oci_execute($parsedQuery);
				*/

				while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
						$type=$row[2];
				}
				
				if($type == 'admin'){
					$sql='select * from admins where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $_POST['username']);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$username=$row[0];
						$password=$row[1];
						$empid=$row[2];
						$mgrflag=$row[3];
					}
					
					
					$_SESSION['loginadmin']=1;
					$_SESSION['admin'] = new Admin($username,$password,$empid,$mgrflag);
					
					header("Location: admin_home.php");
						
					exit;
				}
				else{
					$sql='select * from client where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $_POST['username']);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$fname=$row[3];
						$lname=$row[5];
						$accountnum=$row[2];
						$username=$row[0];
						$password=$row[1];
					}
					
					oci_close($conn);
					
					$_SESSION['loginclient']=1;
					$_SESSION['client'] = new Client($fname,$lname,$accountnum,$username,$password);
					
					header("Location: client_home.php");
					
					exit;
				}
				//$username = $_POST['username'];
				
			}
		}
	}
?>

<html>
	<head>
		<title>Login</title>
	</head>
	
	<body>
	<?php if(!isset($_SESSION['loginadmin']) && !isset($_SESSION['loginclient'])){?>
		<form name="login" action="#" method="post">
		
			<label for="username">Username</label>
			<input type="text" name="username" />
			<br/>

			<label for="password">Password</label>
			<input type="password" name="password" />
			<br/>

			<input type="submit" name="login" value="Log In" />
			<br/>		
		</form>
		<?php }else{	if(isset($_SESSION['loginadmin'])){header("Location: admin_home.php");} else{header("Location: client_home.php");}}?>
	</body>
</html>