<?php
	include("class_lib.php");
	
	session_start();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Admin Dashboard</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/admindashboard_style.css"/>  
		<link rel="stylesheet" href="stylesheets/slider_style.css"/>  
		<link rel="stylesheet" href="stylesheets/jqClock.css" />
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		<script src="scripts/jqClock.js" type="text/javascript"></script>
	</head>

	<body>
		<div id="top">
			<div id="top_upper">
			</div>
			<div id="top_lower">
			</div>
			
			<div id="top_center">
				<div id="logo" onclick="location.href='admin_home.php';" style="cursor: pointer;">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div"  onclick="location.href='admin_home.php';" style="cursor: pointer;">
								ADMIN DASHBOARD
						</div>
						
						<div id="logout_link" class="toplink_div_dark" style="cursor: pointer;">
								LOGOUT
						</div>
						
						
				    </div>
					
					<div id="slogan_div">
						<img id="slogan" src="images/slogan.png" alt ="" />
					</div>
				</div>
				<div id="top_upper_inside">
				</div>
				<div id="top_lower_inside">
				</div>
			</div>
			
		
		</div>
		
		<div id="body">
			
			<div id="body_center">
				<div id="name_area">
					<h1 id="name">Welcome, <?=$_SESSION['admin']->username?>!</h1>
				</div>
				
				<div id="panel_area_1">
				
					<div class="longpanel" id="billerrequests">
						<img alt="" src="images/billerrequests.png" />
					</div>
					<div class="longpanel" id="accountconnections">
						<img alt=""src="images/accountconnections.png" />
					</div>
					<div class="shortpanel" id="time">
						
					</div>
				</div>	
				<div id="panel_area_2">	
					<div class="shortpanel" id="accountsettings">
						<img alt="" src="images/accountsettings.png">
					</div>
					<?php
					
					if($_SESSION['admin']->get_mgrflag()){
					?>
					<div class="longpanel" id="modifybillerlist">
						<img alt="" src="images/modifybillerlist.png">
					</div>
					<div class="longpanel" id="manageadmins">
						<img alt="" src="images/manageadmins.png">
					</div>
					<?php } ?>
				</div>
				
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
				<script type="text/javascript">
				
				$('#billerrequests').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "billerrequests.php";});
				});
				
				$('#accountconnections').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "account_connections.php";});
				});
				
				$('#accountsettings').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "accountsettings.php";});
				});
				
				$('#modifybillerlist').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "modify_billerlist.php";});
				});
				
				$('#manageadmins').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "manage_admins.php";});
				});
				
				$('#logout_link').click(function() {
				
					$('#name,#billerrequests,#accountconnections,#time,#accountsettings,#modifybillerlist,#manageadmins').fadeOut('slow', function() {window.location = "logout_2.php";});
				});
				
				$(document).ready(function(){  $("#time").clock();});
				
				</script>
	
	</body>
</html>