<?php
	session_start();
	
	include("class_lib.php");
	include("email.php");
	
	
	echo $_SESSION['client']->get_username();
	
	
	if(isset($_POST['send'])){
		/*
		$conn = oci_connect('mainbank', 'kayato1');
		$query='select * from billerlist';
		$parsedQuery = oci_parse($conn, $query);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			echo $row[1]."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
			//echo "<a href='removefrombillerlist.php?accountnum=".$row[0]."'>Remove</a><br />";
			echo "<input type='button' value='Remove' onclick= 'removebiller(".$row[0].",".'"'.$row[1].'"'.");' />";
			
			echo "<br />";
		}
		*/
	
		//sendEmail($_SESSION['client']->username, $_POST['subject'], $_POST['body']);
	
	}
?>

<html>
	<head>
		<title> Guestbank OBS | Send Email</title>
	</head>
	<body>
		<form name="mail_form" action="mailform.php" method="POST">
		
		Subject:
		<br />
		
		<input type="text" name ="subject" />
		<br />
		<br />
		Body :
		<br />
		
		<textarea name="body"> </textarea>
		
		<input type="submit" name="send" value="Send" />
		</form>
	
	</body>
		
</html>