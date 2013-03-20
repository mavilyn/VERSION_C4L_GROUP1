<?php
	include("class_lib.php");
	session_start();
	
	if (!(isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '')) {
		header('Location: destroy.php');
		}
		
	if(isset($_POST['Submit'])){
		if($_POST['operation']=="activate")
			$opeFlag = 1;
		else
			$opeFlag = 0;
			
		$accountnum = $_POST['accountnum'];
		$connGuest = oci_connect("guestbank", "kayato1");
	
		
		$stid = oci_parse($connGuest,
			'SELECT accountnum FROM client
			WHERE accountnum = '.$accountnum);
			//oci_bind_by_name($stid, ':accountnum', $_POST['accountnum']);
		oci_execute($stid);

		if($stid ==  NULL) {
		?>
			<script>
			alert("Account not found");
			</script>
		<?php
		}
		else{
			$stid3 = oci_parse($connGuest,
			'UPDATE client set activation = :opeFlag where accountnum = :accountnum'
			);
		
			oci_bind_by_name($stid3, ':accountnum', $_POST['accountnum']);
			oci_bind_by_name($stid3, ':opeFlag', $opeFlag);
			oci_execute($stid3);
			echo '<script type=text/javascript>alert("Account with account number ' .$accountnum.' is '.$_POST['operation'].'d")</script>';
		}
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
					<h1 id="functionality_header2">Activate/Deactivate Accounts</h1>
					</div>
					
					<div id="content_wrapper">
						<form name = "activation_form" method ="post" action = "aordaccounts.php" onSubmit="return checkActivation();">
							<p id="anum_p">
							<label class="label_2" for="anum">Account number:</label> 
							<input type = "text" name="accountnum" maxlength="10" id="accountnum"/>
							<span id="accountnumErr" style="color:red;font-weight:bold;"></span>	
							</p>
							<p id="aordbot_p">
							<select name="operation" id = "operation">
								<option value="activate"> Activate </option>
								<option value="deactivate"> Deactivate </option>
							</select>
							<input type="submit" name="Submit" value="Change account's activation" id="caa"/>	
							</p>
						</form>
					</div>
					
				</div>
			</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span id="baba_span">GuestBank</span></p>
					
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