<?php
	include("class_lib.php");
	session_start();
		$change = false;	
		$invalid = false;
		if (!(isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '')) {
		header('Location: destroy.php');
		}
			if(isset($_POST['Submit'])){
				$username = $_SESSION['admin']->get_username();
				$newpassword = md5(md5($_POST['newpassword']));
				if($_SESSION['admin']->get_password() == md5(md5($_POST['currentpassword']))){
						$connGuest = oci_connect("guestbank", "kayato1");
						$stid3 = oci_parse($connGuest,
						'UPDATE admins set password = :password where username = :username'
						);
						oci_bind_by_name($stid3, ':username', $username);
						oci_bind_by_name($stid3, ':password', $newpassword);
						oci_execute($stid3);
						oci_close($connGuest);
						
						$connGuest2 = oci_connect("guestbank", "kayato1");
						$stid4 = oci_parse($connGuest2,
						'UPDATE users set password = :password where username = :username'
						);
						oci_bind_by_name($stid4, ':username', $username);
						oci_bind_by_name($stid4, ':password', $newpassword);
						oci_execute($stid4);
						oci_close($connGuest2);
						$change = true;
						$_SESSION['admin']->set_password(md5(md5($_POST['newpassword'])));
						//echo "You have successfully changed your password.";
						//header("Location: admin_home.php");
				}
				else{
				//	echo "<script type='text/javascript'>alert('Please enter your correct password.');</script>";
						$invalid = true;
				}
			}
			
			
	if(isset($_POST['cancel'])){
		header("Location: admin_home.php");
		exit;
	}
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
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		<script src="scripts/onlinebank.js" type="text/javascript"></script>

		<link rel="stylesheet" href="stylesheets/alertify.core.css" />
		<link rel="stylesheet" href="stylesheets/alertify.default.css" />
		<script src="scripts/alertify.min.js"></script>
		
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
				<div id="content_outerbox">
					<div id="content_header">
					
					<div id="back_button">
						
						<img src="images/back_2.png" id="back_button_img" />
					</div>
					<h1 id="functionality_header">Account Settings</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "billerlist_tabs">
							
							<div id = "cp_tab_dark">
								<p class="tab_text">Change Password</p>
							</div>
							
						</div>
						<div class="rule2"></div>
						<div id="billers_maincontent">
							<?php 
								if($change == true){
									echo "<script type='text/javascript'>alertify.success('You have successfully changed your password.');</script>";
									$change = false;

								}

								if($invalid == true){
									echo "<script type='text/javascript'>alertify.error('Please enter your correct password.');</script>";
									$invalid = false;		
								}

							?>
							<div id="cp_form_area">
							<form name = "change_password_form" method ="post" action = "accountsettings.php" onsubmit="return checkChangePassword();">
								
								<p id="currentpw_p">
									<label for="currentpassword" >Current Password:</label>
									<input type = "password" id="currentpassword" name="currentpassword" maxlength="20"/> 
									<span id="currentpasswordErr" class="err_span"></span>	
								</p>
								<p id="newpw_p">
									<label for="newpassword">New Password: </label>
									<input type = "password" id="newpassword" name="newpassword" maxlength="20"/> 
									<span id="newpasswordErr" class="err_span"></span>	
								</p>
								
								<label for="newpasswordconfirm">Confirm New Password: </label>
								<input type = "password" id="newpasswordconfirm" name="newpasswordconfirm" maxlength="20"/> 
								<span id="confirmpasswordErr" class="err_span"></span>	
								<br />
								<input type="submit" name="Submit" value="Save" id="changepw"/>	
								
							</form>
						
							</div>
							
						</div>
						<div class="rule2"></div>
					</div>
					</div>
					
				</div>
			</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
		<script type="text/javascript">
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "admin_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
		</script>
	</body>
</html>