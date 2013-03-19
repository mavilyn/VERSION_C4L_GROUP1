<?php
	include("class_lib.php");
	
	session_start();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Client Dashboard</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/clientdashboard_style.css"/>  
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
				<div id="logo" onclick="location.href='client_home.php';" style="cursor: pointer;">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div"  onclick="location.href='client_home.php';" style="cursor: pointer;">
								CLIENT DASHBOARD
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
					<h1 id="name">Welcome, <?=$_SESSION['client']->fname?>!</h1>
				</div>
				
				<div id="panel_area_1">
				
					<div class="longpanel" id="var">
						<img alt="" src="images/var.png" />
					</div>
					<div class="longpanel" id="transfer">
						<img id="enge" alt=""src="images/enge.png" />
						<img id="yoko" alt=""src="images/yoko.png" />
						<img alt=""src="images/transfermoney.png" />
					</div>
					<div class="shortpanel" id="time">
				
					</div>
				</div>	
				<div id="panel_area_2">	
					<div class="shortpanel" id="email">
						<img alt="" src="images/email.png">
					</div>
					<div class="longpanel" id="paybills">
						<div class="slides">
							<img src='images/globe.png' />
							<img src='images/meralco.png' />
							<img src='images/pldtmydsl.png' />
						</div>
				
					</div>
					<div class="longpanel" id="clientaccountsettings">
						<img alt="" src="images/clientaccountsettings.png">
					</div>
				</div>
				
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
				<script type="text/javascript">
				
				$(function(){
					$('.slides img:gt(0)').hide();		//courtesy of: http://snook.ca/archives/javascript/simplest-jquery-slideshow
					setInterval(function(){
					$('.slides :first-child').fadeOut()
					.next('img').fadeIn()
					.end().appendTo('.slides');}, 
					4000);
					});
				
				$('#var').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "viewaccountrecord_2.php";});
				});
				
				$('#email').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "email_2.php";});
				});
				
				$('#transfer').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "transfermoney.php";});
				});
			
				$('#transfer').mouseenter(function() {
	
					$('#enge').removeClass("enge_anim_out");
					$('#enge').addClass("enge_anim");
					$('#yoko').removeClass("yoko_anim_out");
					$('#yoko').addClass("yoko_anim");
				});
				
				$('#transfer').mouseleave(function() {
	
					$('#enge').addClass("enge_anim_out");
					$('#enge').removeClass("enge_anim");
					
					$('#yoko').addClass("yoko_anim_out");
					$('#yoko').removeClass("yoko_anim");
				});
				

				$('#clientaccountsettings').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "clientaccountsettings.php";});
				});
				
				$('#paybills').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "paybills.php";});
				});
				
				$('#logout_link').click(function() {
				
					$('#name,#var, #transfer, #time, #email,#paybills,#clientaccountsettings').fadeOut('slow', function() {window.location = "logout.php";});
				});
				
				$(document).ready(function(){  $("#time").clock();});
				</script>
	
	</body>
</html>