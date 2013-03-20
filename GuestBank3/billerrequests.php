<?php
		include("class_lib.php");
  		session_start();
			/*add biller for customer*/
			if (isset($_POST['Submit'])) {
			
				$conn = oci_connect('guestbank', 'kayato1');
				if(isset($_POST['disapproved'])){
					foreach($_POST['disapproved'] as $disapprove){
						$token = strtok($disapprove, " ");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
						  $accountnum = $token;
						  $token = strtok(" ");
						  $billeraccountnum = $token;
						  $token = strtok(" ");
						  $refnum = $token;
						  //} 
						$aquery = 'UPDATE ADDBILLER_REQUEST SET appDisDate = SYSDATE, appDisFlag = 0 where refnum = :refnum and billeraccountnum = :billeraccountnum and accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $refnum);
						oci_bind_by_name($sid, ':billeraccountnum', $billeraccountnum);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_execute($sid);
					}
				}
				if(isset($_POST['approved'])){
					foreach($_POST['approved'] as $approve){
					
						$token = strtok($approve, " ");
						//while ($token != false)
						 // {
						  //echo "$token<br />";
						  $accountnum = $token;
						  $token = strtok(" ");
						  $billeraccountnum = $token;
						  $token = strtok(" ");
						  $refnum = $token;
						  $token = strtok(" ");
						  $billername = $token;
						/*$query =  'SELECT accountnum FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$accountnum = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
						*/
						/*$query =  'SELECT * FROM addbiller_request where refnum = :refnum';
						$compiled = oci_parse($conn, $query);
						oci_bind_by_name($compiled, ':refnum', $approve);
						oci_execute($compiled);
						$billername = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);*/
						/*
						foreach ($accountnum as $acctnum){
							foreach ($billername as $biller){
								
							}
						}*/
						
					/*while ($row = oci_fetch_array($compiled, OCI_BOTH)) {
							$biller=$row[2];
							$acctnum=$row[0];
						}
						
						$sql="select * from billerlist where billername ='".$biller."'";

						$stmt = oci_parse($conn, $sql);
						oci_execute($stmt, OCI_DEFAULT);

						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$billeraccountnum=$row[0];
						}*/

						$connMain = oci_connect("mainbank", "kayato1");
						
						$query =  'INSERT into current_biller values(:accountnum, :billeraccountnum, :billername, :refnum)';
						$compiled = oci_parse($connMain, $query);
						oci_bind_by_name($compiled, ':accountnum', $accountnum);
						oci_bind_by_name($compiled, ':billeraccountnum', $billeraccountnum);
						oci_bind_by_name($compiled, ':billername', $billername);
						oci_bind_by_name($compiled, ':refnum', $refnum);
						oci_execute($compiled);
						
								
						$aquery = 'UPDATE ADDBILLER_REQUEST SET appDisDate = SYSDATE, appDisFlag = 1 where refnum = :refnum and billeraccountnum = :billeraccountnum and accountnum = :accountnum';
						$sid = oci_parse($conn, $aquery);
						oci_bind_by_name($sid, ':refnum', $refnum);
						oci_bind_by_name($sid, ':accountnum', $accountnum);
						oci_bind_by_name($sid, ':billeraccountnum', $billeraccountnum);
						oci_execute($sid);
						
						
					}
				}//approve = 1
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
		<script src="scripts/javascript_validation.js" type="text/javascript"></script>
		
		
		<link type="text/css" href="stylesheets/jquery.jscrollpane.css" rel="stylesheet" media="all" />
		<link type="text/css" href="stylesheets/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
		<script type="text/javascript" src="scripts/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>
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
					<h1 id="functionality_header">Manage Biller Requests</h1>
					</div>
					
					<div id="content_wrapper">
						
			<form name = "confirmRequest_form" method ="post" action = "billerrequests.php" onSubmit="return checkManageBiller();">			
						<div class="scroll-pane-arrows2">
						
			<?php

				$conn = oci_connect("guestbank", "kayato1");
		
			
				$empid = $_SESSION['admin']->get_empid();

				$connMain = oci_connect("mainbank", "kayato1");
					$query = 'select * from admins where empid=:empid';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':empid', $empid);
					oci_execute($stid, OCI_DEFAULT);

					while ($row = oci_fetch_array($stid, OCI_BOTH)) {
						$branchcode = $row[4];	
					}

				$query = 'select * from addbiller_request where appDisFlag IS NULL and accountnum IN (SELECT accountnum
					from client where branchcode = :adminbranch)';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':adminbranch', $branchcode);
				oci_execute($stid);
				
				if($stid == NULL){
					echo "Execution Failed";
				}
				
				print '<table id="accountconnect_table">';
				print '<tr>';
				print'<th class="header_row">Account Number'; echo'</th>';
				print'<th class="header_row">Biller Account Number'; echo'</th>';
				print'<th class="header_row">Biller Name'; echo'</th>';
				print'<th class="header_row">Reference Number'; echo'</th>';
				print'<th class="header_row">Approve'; echo'</th>';
				print'<th class="header_row">Disapprove'; echo'</th>';
				echo '</tr>';
				$count = 0;		
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$accountnum=$row[0];
							$billeraccountnum=$row[1];
							$billername=$row[2];
							$ref=$row[3];

						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td class="ac_data">'.$row[0]; echo'</td>';
						print'<td class="ac_data">'.$row[1]; echo'</td>';
						print'<td class="ac_data">'.$row[2]; echo'</td>';
						print'<td class="ac_data">'.$row[3]; echo'</td>';
					//}
					print '<td class="ac_data">';
					print '<input type="checkbox" id="approve'.$count.'" name="approved[]" value="'.$row[0].' '.$row[1].' '.$row[3].' '.$row[2].'" onclick="if (this.checked) disapprove'.$count.'.disabled=true; else disapprove'.$count.'.disabled = false;"/>';
					echo '</td class="ac_data">';
					print '<td class="ac_data">';
					print '<input type="checkbox" id="disapprove'.$count.'" name="disapproved[]" value="'.$row[0].' '.$row[1].' '.$row[3].'"  onclick="if (this.checked) approve'.$count.'.disabled=true; else approve'.$count.'.disabled = false;" />';
					echo '</td class="ac_data">';
					echo '</tr>';
					$count++;
					
				}
				print '</table>';?>
			
			</div>
			<input type="submit" name="Submit" value="Submit" id="brsubmit"/>		

		</form>
					</div>
					
				</div>
			</div>
			</div>
				
		</div>
				
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
		<script type="text/javascript">
		
			$(function(){
						$('.scroll-pane2').jScrollPane();
						$('.scroll-pane-arrows2').jScrollPane(
						{
							showArrows: true,
						
						}
						);
						});
			
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "admin_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
		</script>
	</body>
</html>