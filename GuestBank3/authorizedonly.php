<?php
	session_start();
	if($_SESSION['login']!=1){
		header("Location: login.php");
		exit;
	}	
?>

<html>
<body>
Hello World!
<a href="logout.php">>> Log Out</a>


</body>


</html>
