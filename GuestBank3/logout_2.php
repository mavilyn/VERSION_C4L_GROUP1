
<?php
	include("class_lib.php");
	
	session_start();
	session_destroy();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Client Dashboard</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/admindashboard_style.css"/>  
		<link rel="stylesheet" href="stylesheets/slider_style.css"/>  
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		
	</head>

	<body>
		<div id="top">
			
			<div id="top_center">
				<div id="logo">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div_dark">
								ADMIN DASHBOARD
						</div>
						
						<div id="logout_link" class="toplink_div">
								LOGOUT
						</div>
						
						
				    </div>
					
					<div id="slogan_div">
						<img id="slogan" src="images/slogan.png" alt ="" />
					</div>
				</div>
				
			</div>
			
			<div id="top_upper">
				
			</div>
			
			<div id="top_lower">
			</div>
		
		</div>
		
		<div id="body">
			
			<div id="body_center">
			
				<div id="msg_area">
					<div id ="msg_black">
						
					</div>
					<h1 id="logout_msg">You have successfully logged out!</h1>
				</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
		<script type="text/javascript">
		
			function go_home(){
				window.location='index.php';	
			}
			
			window.setTimeout("go_home()",1500);
		</script>
	
	</body>
</html>
