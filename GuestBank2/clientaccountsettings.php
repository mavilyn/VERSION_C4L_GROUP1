<?php
	include("class_lib.php");
	
	session_start();
	
	if(isset($_POST['Submit'])){
				$username = $_SESSION['client']->get_username();
				$newpassword = md5(md5($_POST['newpassword']));
				
				
				if($_SESSION['client']->get_password() == md5(md5($_POST['currentpassword']))){
						$connGuest = oci_connect("guestbank", "kayato1");
						$stid3 = oci_parse($connGuest,
						'UPDATE client set password = :password where username = :username'
						);
						oci_bind_by_name($stid3, ':username', $username);
						oci_bind_by_name($stid3, ':password', $newpassword);
						oci_execute($stid3);
						
						$stid4 = oci_parse($connGuest,
						'UPDATE users set password = :password where username = :username'
						);
						oci_bind_by_name($stid4, ':username', $username);
						oci_bind_by_name($stid4, ':password', $newpassword);
						oci_execute($stid4);
						
						oci_close($connGuest);
						$_SESSION['client']->set_password(md5(md5($_POST['newpassword'])));
						echo "You have successfully change your password.";
						header("Location: client_home.php");
				}
				else{
					echo "Please enter your correct password.";
				}
			}
			
			
				if(isset($_POST['cancel'])){
					header("Location: client_home.php");
					exit;
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
		
		<script type="text/javascript" src="scripts/onlinebank.js"></script>
	</head>

	<body>
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
						<div id="home_link" class="toplink_div"  style="cursor: pointer;">
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
				<div id="content_outerbox">
					<div id="content_header">
					
					<div id="back_button">
						
						<img src="images/back_2.png" id="back_button_img" />
					</div>
					<h1 id="functionality_header">Account Settings</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "var_tabs">
							
							<div id = "epi_tab_light" class="tab_dark">
								<p class="tab_text">Edit Personal Information</p>
							</div>
							
							<div id = "cp_tab_light" class="tab" >
								<p class="tab_text">Change Password</p>
							</div>
							
							<div id = "da_tab_light" class="tab">
								<p class="tab_text">Deactivate Account</p>
							</div>
						</div>
						<div class="rule"></div>		
							<div id="epi_maincontent">
							</div>
							
							<div id="cp_maincontent">
							
							<form name = "change_password_form" method ="post" action = "clientaccountsettings.php" onsubmit="return checkChangePassword();">
								
								<p id="cpass_p">
								<label class="label_2" for ="cpass">Current Password: </label>
								<input type = "password" name="currentpassword" maxlength="20" id="cpass"/> 
								<span id="currentpasswordErr" class="err_span"></span>	
								</p>
								<p id="npass_p">
								<label class="label_2" for="npass">New Password:</label>
								<input type = "password" name="newpassword" maxlength="20" id="npass"/> 
								<span id="newpasswordErr" class="err_span"></span>	
								</p>
								<p id="cnpass_p">
								<label class="label_2" for="cnpass">Confirm New Password:</label>
								<input type = "password" name="newpasswordconfirm" maxlength="20" id="cnpass"/> 
								<span id="confirmpasswordErr" class="span_err"></span>	
								</p>
								
								<input type="submit" name="Submit" value="Change my password" id="chp"/>	
								</form>
							</div>
							
							<div id="da_maincontent">
							</div>
						<div class="rule"></div>
					</div>
					
				</div>
			</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
		<script type="text/javascript">
			
			$('#epi_maincontent').show();
			$('#cp_maincontent').hide();
			$('#da_maincontent').hide();
			
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
				
			$('#epi_tab_light').click(function(){
				$('#epi_tab_light').addClass('tab_dark');
				$('#epi_tab_light').removeClass('tab_light');
				$('#da_tab_dark').attr('id', 'da_tab_light');
				$('#cp_tab_dark').attr('id', 'cp_tab_light');
			
				$('#epi_maincontent').slideDown();
				$('#da_maincontent').hide();
				$('#cp_maincontent').hide();
				
				});
			
			
			$('#cp_tab_light').click(function(){
				$('#epi_tab_light').removeClass('tab_dark');
				$('#epi_tab_light').addClass('tab_light');
				$('#epi_tab_dark').attr('id', 'epi_tab_light');
				$('#da_tab_dark').attr('id', 'da_tab_light');
				$('#cp_tab_light').attr('id', 'cp_tab_dark');
				
				$('#epi_maincontent').hide();
				$('#da_maincontent').hide();
				$('#cp_maincontent').slideDown();
				
				});
			
			$('#da_tab_light').click(function(){
				$('#epi_tab_light').removeClass('tab_dark');
				$('#epi_tab_light').addClass('tab_light');
				$('#epi_tab_dark').attr('id', 'epi_tab_light');
				$('#da_tab_light').attr('id', 'da_tab_dark');
				$('#cp_tab_dark').attr('id', 'cp_tab_light');
				
				$('#epi_maincontent').hide();
				$('#da_maincontent').slideDown();
				$('#cp_maincontent').hide();
				
				});
		</script>
	</body>
</html>