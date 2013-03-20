<?php
	include("class_lib.php");
	include("email.php");
	
	session_start();
	
	if (!(isset($_SESSION['loginclient']) && $_SESSION['loginclient'] != '')) {
	header('Location: destroy.php');
	}
	
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
			$sent = true;
			//echo "<script type='text/javascript'>"."alert('Your email was sent.');"."</script>";
		}
		else{
			$sent = false;
			//echo "<script type='text/javascript'>"."alert('Your email was not sent.');"."</script>";
		
		}
		
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Client Dashboard</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/clientdashboard_style.css"/>  
		<link rel="stylesheet" href="stylesheets/slider_style.css"/>  
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		
		<link rel="stylesheet" href="stylesheets/alertify.core.css" />
		<link rel="stylesheet" href="stylesheets/alertify.default.css" />
		<script src="scripts/alertify.min.js"></script>
		
		
	</head>
	
	<body class="preload">
		
		<?php 
				if(isset($sent) && $sent){
					echo "<script type='text/javascript'>alertify.success('Your mail was sent!');</script>";
					unset($sent);		
					}
				if(isset($sent) && !$sent){
					echo "<script type='text/javascript'>alertify.error('Your mail was not sent.');</script>";
					unset($sent);		
					}
		?>
	<div id="top">
			<div id="top_upper">
			</div>
			<div id="top_lower">
			</div>
			<div id="top_center">
				<div id="logo" style="cursor: pointer;">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div"   style="cursor: pointer;">
								CLIENT DASHBOARD
						</div>
						
						<div id="logout_link" class="toplink_div_dark" style="cursor: pointer;">
								LOGOUT
						</div>
						
						
				    </div>
					
					<div id="slogan_div">
						<img id="slogan" src="images/slogan.png" alt ="" />
					</div>
					
					<div id="top_upper_inside">
					</div>
					<div id="top_lower_inside">
					</div>
				</div>
				
			</div>
			
		
		</div>
		
		<div id="body">
			
			<div id="body_center">
				
				<div id="content_outerbox">
					<div id="content_header">
					
					<div id="back_button">
						
						<img src="images/back_2.png" id="back_button_img" />
					</div>
					<h1 id="functionality_header">Email GuestBank</h1>
					</div>
					
					<div id="content_wrapper">
						<form name="mail_form" action="#" method="POST" onSubmit= "return validate_mail();">
							
							<div id="label_area">
							<br />
							<br />
							<label for="subject" id="subj_label" >Subject:</label>
							<br />
							<br />
							<label for="field" id="field_label">Body:</label>
							</div>
							
							<div id="form_area">
								<input type="text" name ="subject" id= "subject"/>
								<textarea name="body" id= "field"></textarea>
							
								<input type="submit" name="send" id="send_mail" value="Send" onClick="alertify.log('Sending Email...');"/>
							
							</div>
							
						</form>
						
						<!--<img id="mail_bg" src="images/mail_bg.png" alt="" />-->
					</div>
					
				</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>

		<script type="text/javascript">
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
				
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
		</script>
	</body>
</html>