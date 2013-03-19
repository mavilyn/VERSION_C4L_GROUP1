<?php
	
	include("class_lib.php");
	include("email.php");
	
	session_start();
	
	
	if(isset($_POST['send'])){
		
		$username = $_SESSION['client']->get_username();
		$conn = oci_connect('guestbank', 'kayato1');
		$query='select email from client where username=:username';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':username', $username);
		
		oci_execute($parsedQuery);
		
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			$email = $row[0];
			
			}
		
		$name = $_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname()." - ".$email;
	
		$sent = sendEmail($name, $_POST['subject'], $_POST['body']);
		
		if($sent){
			echo "<script type='text/javascript'>"."alert('Your email was sent.');"."</script>";
		}
		else{
				echo "<script type='text/javascript'>"."alert('Your email was not sent.');"."</script>";
		
		}
		
	}
?>

<html>
	<head>
		<title> Guestbank OBS | Send Email</title>
		<script type="text/javascript">
			function validate_mail(){
				
				if((document.forms["mail_form"]["subject"].value=="") || (document.getElementById("field").value=="")){
					alert("Email not sent. Please fill-up both fields.");
					return false
					}
				else{
				
					return true;
				}
			}
		
		</script>
		
	</head>
	<body>
		<form name="mail_form" action="#" method="POST" onSubmit= "return validate_mail();">
		
		Subject:
		<br />
		
		<input type="text" name ="subject" id= "subject"/>
		<br />
		<br />
		Body :
		<br />
		
		<textarea name="body" id= "field"></textarea>
		
		<input type="submit" name="send" value="Send" />
		</form>
	
	</body>
		
</html>