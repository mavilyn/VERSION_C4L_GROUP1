<?php
	include("class_lib.php");
	session_start();

	$successChange = false;
	$failureChange = false;
	$accountnum = "";
	$op = "";
	if (!(isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '')) {
		header('Location: destroy.php');
		}
		
	/*if(isset($_POST['Submit'])){
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
	}*/
	if (isset($_POST['Submit'])) {
				/************************************************/
				//estbalish connections
				$connGuest = oci_connect("guestbank", "kayato1");
				//$connMain = oci_connect("mainbank", "kayato1");
				/***********************************************/

				$empid = $_SESSION['admin']->get_empid();
				$accountnum =$_POST['accountnum'];
				$op = $_POST['operation'];
				if($_POST['operation']=="activate")
					$activate = 1;
				else
					$activate = 0;

					$query = 'select * from admins where empid=:empid';
					$stid = oci_parse($connGuest, $query);

					oci_bind_by_name($stid, ':empid', $empid);
					oci_execute($stid, OCI_DEFAULT);

					while ($row = oci_fetch_array($stid, OCI_BOTH)) {
						$branchcode = $row[4];	
					}

				$query = 'select * FROM client where accountnum = :accountnum and accountnum IN (SELECT accountnum
					from client where branchcode = :adminbranch)';
				$stid = oci_parse($connGuest, $query);
				oci_bind_by_name($stid, ':adminbranch', $branchcode);
				oci_bind_by_name($stid, ':accountnum', $accountnum);
				oci_execute($stid, OCI_DEFAULT);
				$count = 0;
					while ($row = oci_fetch_array($stid, OCI_BOTH)) {

						if(isset($row[16])){
									$parsed = oci_parse($connGuest, 'UPDATE client set activation = :activate where
										accountnum=:accountnum ');
										oci_bind_by_name($parsed, ':accountnum', $accountnum);
										oci_bind_by_name($parsed, ':activate', $activate);
									oci_execute($parsed);
									$successChange = true;
									$count++;
						}
							
					}

					if($count==0){
						$failureChange = true;
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
		<script src="scripts/javascript_validation.js" type="text/javascript"></script>

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
					<h1 id="functionality_header2">Activate/Deactivate Accounts</h1>
					</div>
					
					<div id="content_wrapper">
						<?php 
							if($successChange== true){
								echo '<script type=text/javascript>alertify.success("Account with account number ' .$accountnum.' is '.$op.'d")</script>';
								$successChange = false;
							}
							if($failureChange == true){
								echo '<script>alertify.error("Account does not exits!");</script>';
								$failureChange = false;
							}
								
										
						?>
						<form name = "activation_form" method ="post" action = "aordaccounts.php" onSubmit="return checkActivate();">
							<p id="anum_p">
							<span id="accountnumErr" style="color:red;font-weight:bold;" class = "errSpan"></span><br />
							<label class="label_2" for="anum">Account number:</label> 
							<input type = "text" name="accountnum" maxlength="10" id="accountnum"/>	
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