<?php
	include("class_lib.php");
	
	session_start();
	
		if (!(isset($_SESSION['loginclient']) && $_SESSION['loginclient'] != '')) {
		header('Location: destroy.php');
		}
	
	/**********************codes for change password******************/
	
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
						echo "You have successfully changed your password.";
						header("Location: client_home.php");
				}
				else{
					echo "Please enter your correct password.";
				}
			}
	
	/*code for account deactivation */		
		if(isset($_POST['deactivate'])){
		
			$accountnum = $_SESSION['client']->accountnum;
			$connGuest = oci_connect("guestbank", "kayato1");
			
			$stid3 = oci_parse($connGuest,
			'UPDATE client set activation = 0 where accountnum = :accountnum');
		
			oci_bind_by_name($stid3, ':accountnum', $accountnum);
			//oci_bind_by_name($stid3, ':opeFlag', 0);
			oci_execute($stid3);

			echo '<script>alert("Account deactivated. You will be logout now.");</script>';
			
			
			unset($_SESSION['loginclient']);
			unset($_SESSION['client']);
			session_destroy();
			header("Location: index.php");
			exit;
		}	
	
		/****code for edit personal information*****/
		
						$accountnum = $_SESSION['client']->get_accountnum();
						$conn = oci_connect("guestbank", "kayato1");
						$query =  'SELECT * FROM client where accountnum = :accountnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':accountnum', $accountnum);
						oci_execute($compiled);
						while ($row = oci_fetch_array($compiled, OCI_BOTH)) {
							$fname = $row[3];
							$mname = $row[4];
							$lname = $row[5];
							$gender = $row[6];

							$homeaddress = $row[7];
							$civilstat = $row[8];
							$birthdate = date('d/m/y', strtotime($row[9]));
							//echo "<script type='text/javascript'>alert('".$birthdate."');</script>";
							//$token = strtok($birthdate, "-");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
							  $month = substr($birthdate, 0, 2);
							  $day = substr($birthdate, 3, 2);
							  $year = substr($birthdate, 6, 4);
							  /*$token = strtok("-");
							  $day = $token;
							  $token = strtok("-");
							  $year = $token;
*/							if(isset($row[10])){
								$email = $row[10];
							}
							else{
								$email = "";
							}
							if(isset($row[11])){
								$contact = $row[11];	
							}
							else{
								$contact = "";
							}
							if(isset($row[12])){
								$spouse = $row[12];
							}
							else{
								$spouse = "";	
							}
							$mother = $row[13];
							
							//$fname = $row[3];
							//$ = $row[3];
						}
		
		
		
		if(isset($_POST['update'])){


			if($_POST['fname']!=""){
				$fname = $_POST['fname'];
			}
			if($_POST['mname']!=""){
				$mname = $_POST['mname'];
			}
			if($_POST['lname']!=""){
				$lname = $_POST['lname'];
			}
			if(isset($_POST['gender'])){
				$gender = $_POST['gender'];
			}
			if($_POST['province']!=""){
				$homeaddress = $_POST['brgy'].",".$_POST['city'].", ".$_POST['province'];
			}
			if($_POST['month']!=""){
				$month = $_POST['month'];
			}
			if($_POST['day']!=""){
				$day = $_POST['day'];
			}
			if($_POST['year']!=""){
				$year = $_POST['year'];
			}
			$birthdate = $month."/".$day."/".$year;
			if($_POST['civilstat']!=""){
				$civilstat = $_POST['civilstat'];
			}
			
			if($_POST['email']!=""){
				$email = $_POST['email'];
			}
			if($_POST['contact']!=""){
				$contact = $_POST['contact'];
			}
			if($_POST['spouse']!=""){
				$contact = $_POST['spouse'];
			}
			if($_POST['mother']!=""){
				$contact = $_POST['mother'];
			}
			
			$aquery = 'UPDATE CLIENT SET fname = :fname, mname = :mname, lname=:lname, gender=:gender, homeaddress=:homeaddress, civilstat=:civilstat,
			bday=to_date(:birthdate,\'mm/dd/yy\'), email=:email, contact=:contact, spouse=:spouse, mother=:mother where accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':fname', $fname);
						oci_bind_by_name($sid, ':mname', $mname);
						oci_bind_by_name($sid, ':lname', $lname);
						oci_bind_by_name($sid, ':gender', $gender);
						oci_bind_by_name($sid, ':homeaddress', $homeaddress);
						oci_bind_by_name($sid, ':civilstat', $civilstat);
						oci_bind_by_name($sid, ':birthdate', $birthdate);
						oci_bind_by_name($sid, ':email', $email);
						oci_bind_by_name($sid, ':contact', $contact);
						oci_bind_by_name($sid, ':spouse', $spouse);
						oci_bind_by_name($sid, ':mother', $mother);
						oci_execute($sid);
						$_SESSION['client']->set_name($fname);
						$_SESSION['client']->set_lname($lname);
						$_SESSION['client']->set_mname($mname);
						
						echo "<script type='text/javascript'>alertify.success('Personal information edited.');</script>";
						oci_commit($sid);
						oci_close($sid);
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
	
		<link type="text/css" href="stylesheets/jquery.jscrollpane.css" rel="stylesheet" media="all" />
		<link type="text/css" href="stylesheets/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
		<script type="text/javascript" src="scripts/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>

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
							<div class="scroll-pane-arrows5">
							<form name = "update_personal" method ="post" action = "clientaccountsettings.php" onSubmit="return checkUpdatePersonal();">
			  <!----> 
			<b>Update personal information. Please leave correct information blank.</b>
			<br /> <br />
			First Name: <input type="text" id = "fname" name="fname" maxlength="30" placeholder="<?php echo $fname;?>" value=""/>
			<span id="fnameErr" style="color:red;font-weight:bold;"></span>	 
						<br /> 	
			Middle Name: <input type="text" id = "mname" name="mname" maxlength="30" placeholder="<?php echo $mname;?>" value="" /> 
			<span id="mnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			Last Name: <input type="text" id = "lname" name="lname" maxlength="30" placeholder="<?php echo $lname;?>" value=""/> 
			<span id="lnameErr" style="color:red;font-weight:bold;"></span>	 
						<br />
			Gender: <input type="radio" name="gender" id="male" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" id="female"value="female"/> 
					<label for="female">Female</label> 
					<span id="genderErr" style="color:red;font-weight:bold;"></span>	
						<br />
			Home Address: <?php echo "<i style='color:gray;'>".$homeaddress."</i>";?>
						<span id="homeaddressErr" style="color:red;font-weight:bold;"></span>	
						<br />
						&nbsp;&nbsp;&nbsp;&nbsp;House Number, Brgy./Subdivision: <input type="text" id = "brgy" name="brgy" maxlength="30" value=""/><br />
						&nbsp;&nbsp;&nbsp;&nbsp;City/Town: <input type="text" name="city" id = "city" maxlength="30" value=""/><br />
						&nbsp;&nbsp;&nbsp;&nbsp;Province, Zip code: <input type="text" name="province" id = "province" maxlength="20" value=""/><br />
			Civil Status: 
					<select name="civilstat" id="civilstat">
						<option value=""></option> 
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select> 
						<br />
			Birthdate: 
					<input type="text" id = "month" name="month" min="1" max="12" placeholder="<?php echo $month;?>" value=""/> - 
					<input type="text" id = "day" name="day" min="1" max="31" placeholder="<?php echo $day;?>" value=""/> - 						
					<input type="text" id = "year" name="year" min="1900" max="2100" placeholder="<?php echo $year;?>" value=""/>
					<span id="birthErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Email Address: <input type="text" id = "email_field" name="email" maxlength="50" placeholder="<?php echo $email;?>" value=""/> 
					<span id="emailErr" style="color:red;font-weight:bold;"></span>	
					<br />
			Contact Number: <input type="text" id = "contact" name="contact" maxlength="50" placeholder="<?php echo $contact;?>" value=""/> 
					<span id="contactErr" style="color:red;font-weight:bold;"></span>
					<br />
			Spouse's Name: <input type="text" id = "spouse"name="spouse" maxlength="100" placeholder="<?php echo $spouse;?>" value=""/> 
							<span id="spouseErr" style="color:red;font-weight:bold;"></span>
							<br />
			Mother's Maiden Name: <input type="text" id = "mother" name="mother" maxlength="100" placeholder="<?php echo $mother;?>" value=""/> 
							<span id="motherErr" style="color:red;font-weight:bold;"></span>
							<br />
					<input type="submit" name="update" value="Update Personal Information" id="upi"/>	
		</form>
							</div>
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
								<form name = "deactivate_form" method ="post" action = "clientaccountsettings.php" onsubmit=" return confirm('Are you sure you want to deactivate your account?');"> 
									<input type="submit" name="deactivate" value = "Deactivate My Account!" id="deactivate" />
								</form>
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
				
				$(function(){
						$('.scroll-pane5').jScrollPane();
						$('.scroll-pane-arrows5').jScrollPane(
						{
						
							showArrows: true,
						
						}
						);
						});	
		</script>
	</body>
</html>