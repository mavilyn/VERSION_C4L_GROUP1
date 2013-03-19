<?php
	include("class_lib.php");
	session_start();
	/* get account_balance */
	$balance = 0;

	$conn = oci_connect('mainbank', 'kayato1');
	$sql='select balance from account where accountnum = :accountnum';
	$stmt = oci_parse($conn, $sql);
	oci_bind_by_name($stmt, ':accountnum', $_SESSION['client']->accountnum);
	oci_execute($stmt, OCI_DEFAULT);
	while ($row = oci_fetch_array($stmt, OCI_BOTH)){
		$balance = $row[0];
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
					<h1 id="functionality_header">View Account Record</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "var_tabs">
							
							<div id = "ai_tab_light" class="tab_dark">
								<p class="tab_text">Account Information</p>
							</div>
							
							<div id = "notif_tab_light" class="tab" >
								<p class="tab_text">Notifications</p>
							</div>
							
							<div id = "trans_tab_light" class="tab">
								<p class="tab_text">Transaction History</p>
							</div>
							
						</div>
						<div class="rule"></div>
						<div id="ai_maincontent">
							<div id="ai_left">
							<h1 id="client_name">
								<?php 
								echo $_SESSION['client']->lname.", ".
								$_SESSION['client']->fname." ".
								$_SESSION['client']->mname;
								
								?>
							</h1>
							
							<h1 id="acc_num">Account Number: 
								<?= $_SESSION['client']->accountnum?>
							</h1>
							
							<h1 id="acc_bal">Account Balance: <span id="bal">P<?= $balance?></span>
							</h1>
							</div>
						<div id="ai_right">
							<img src="images/avatar.png" alt="" />
						</div>
						</div>
						
						<div id="notif_maincontent">
						</div>
						
						<div id="trans_maincontent">
						
							
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
			//$('#ai_tab_light').attr('id', 'ai_tab_dark');
			$('#ai_maincontent').show();
			$('#trans_maincontent').hide();
			$('#notif_maincontent').hide();
			
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {location.href = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {location.href = "logout.php";});
				});
			
			$('#ai_tab_light').click(function(){
				$('#ai_tab_light').addClass('tab_dark');
				$('#ai_tab_light').removeClass('tab_light');
				$('#trans_tab_dark').attr('id', 'trans_tab_light');
				$('#notif_tab_dark').attr('id', 'notif_tab_light');
			
				$('#ai_maincontent').slideDown();
				$('#trans_maincontent').hide();
				$('#notif_maincontent').hide();
				
				});
			
			
			$('#notif_tab_light').click(function(){
				$('#ai_tab_light').removeClass('tab_dark');
				$('#ai_tab_light').addClass('tab_light');
				$('#ai_tab_dark').attr('id', 'ai_tab_light');
				$('#trans_tab_dark').attr('id', 'trans_tab_light');
				$('#notif_tab_light').attr('id', 'notif_tab_dark');
				
				$('#ai_maincontent').hide();
				$('#trans_maincontent').hide();
				$('#notif_maincontent').slideDown();
				
				});
			
			$('#trans_tab_light').click(function(){
				$('#ai_tab_light').removeClass('tab_dark');
				$('#ai_tab_light').addClass('tab_light');
				$('#ai_tab_dark').attr('id', 'ai_tab_light');
				$('#trans_tab_light').attr('id', 'trans_tab_dark');
				$('#notif_tab_dark').attr('id', 'notif_tab_light');
				
				$('#ai_maincontent').hide();
				$('#trans_maincontent').slideDown();
				$('#notif_maincontent').hide();
				
				});
				
			
				
		</script>
	</body>
</html>