<?php
	include("class_lib.php");
	session_start();
	
	$tempusername = "";
	
	if(isset($_POST['login'])){
		$conn = oci_connect('guestbank', 'kayato1');

		/*********************************************/
		//variable declarations
			$username = md5(md5($_POST['username']));
			$tempusername = $_POST['username'];
			$encryptedPW = md5(md5($_POST['password']));
		/*********************************************/
		/***********************************************************/
		//check if correct login credentials by tracking through $num_rows	
			$stid = oci_parse($conn,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM users
					WHERE  username= :username and password= :password'
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_bind_by_name($stid, ':username', $username);
			oci_bind_by_name($stid, ':password', $encryptedPW);
			oci_execute($stid);
			oci_fetch($stid);
		/***********************************************************/
			
			if($num_rows == 0){
				?>
				<script>
					alert("Username and password did not match");
				</script>
				<?php
			}							
			else{
				/******************************************************/
				//distinguish type if administrator or client
				$query='select * from users where username = :username';
				$parsedQuery = oci_parse($conn, $query);
				oci_bind_by_name($parsedQuery, ':username', $username);
				oci_execute($parsedQuery);

				while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
						$type=$row[2];
				}
				/******************************************************/

				if($type == 'admin'){
					$sql='select * from admins where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $username);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$username=$row[0];
						$password=$row[1];
						$empid=$row[2];
						$mgrflag=$row[3];
						$branchcode=$rpw[4];
					}
					
					/*****************************************************************************/
						//initialize admin session, go to admin_home
						$_SESSION['loginadmin']=1;
						$_SESSION['admin'] = new Admin($username,$password,$empid,$mgrflag,$branchcode);
						
						header("Location: admin_home.php");
						exit;
					/*****************************************************************************/
				}
				else{

					$sql='select * from client where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $username);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$fname=$row[3];
						$lname=$row[5];
						$accountnum=$row[2];
						$username=$row[0];
						$password=$row[1];
					}
					
					oci_close($conn);		//close connection

					/*****************************************************************************/
					//initialize admin session, go to client_home
					$_SESSION['loginclient']=1;
					$_SESSION['client'] = new Client($fname,$lname,$accountnum,$username,$password);
					
					header("Location: client_home.php");
					/*****************************************************************************/
					exit;
				}
			}
	}
?>

<html>
	<head>
		<title>Login</title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	
	<body>
	<?php if(!isset($_SESSION['loginadmin']) && !isset($_SESSION['loginclient'])){?>
		<form name="login" action="#" method="post" onSubmit="return checkLogin();">
		
			<label for="username">Username</label>
			<input type="text" name="username" value="<?php echo $tempusername;?>"/>
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