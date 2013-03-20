<?php
	include("class_lib.php");
	
	session_start();
	
	if (!(isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '')) {
		header('Location: destroy.php');
		}
	
	
	function insert_biller($accountnum, $billername){
		$conn = oci_connect('mainbank', 'kayato1');
		$query='insert into billerlist values(:accountnum, :billername)';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':accountnum', $accountnum);
		oci_bind_by_name($parsedQuery, ':billername', $billername);
		$good=@oci_execute($parsedQuery);
		
		if (!$good) {
			$e = oci_error($parsedQuery);  // For oci_parse errors pass the connection handle
			
			echo "<script type='text/javascript'>alert('Biller already exists.');</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('Biller successfully added.');</script>";
		}
		oci_commit($conn);
		oci_free_statement($parsedQuery);
		oci_close($conn);
		
	}
	
	function display_billerlist(){
		$conn = oci_connect('mainbank', 'kayato1');
		$query='select * from billerlist';
		$parsedQuery = oci_parse($conn, $query);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			echo "<tr><td class='billername'>".$row[1]."</td>";
			//echo "<a href='removefrombillerlist.php?accountnum=".$row[0]."'>Remove</a><br />";
			echo "<td><input type='button' value='Remove' onclick= 'removebiller(".$row[0].",".'"'.$row[1].'"'.");' /></td>";
			
			echo "</tr>";
		}
		
		oci_free_statement($parsedQuery);
		oci_close($conn);
		
	}
	
	if(isset($_POST['addbiller'])){
		$conn = oci_connect('mainbank', 'kayato1');
		$query='select * from account where accountype=\'compsavings\'';
		$parsedQuery = oci_parse($conn, $query);
		oci_execute($parsedQuery);
		$exists = false;
		while ($row = oci_fetch_array ($parsedQuery, OCI_BOTH)) {
			if($row[0] == $_POST['accountnum']){
				
				$exists = true;
				insert_biller($_POST['accountnum'], $row[4]);
				
			}
		}
		if(!$exists)
			echo "<script type='text/javascript'>alert('Account does not exist.');</script>";
	
		oci_free_statement($parsedQuery);
		oci_close($conn);
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
		
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		
		
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
					<h1 id="functionality_header">Modify Biller List</h1>
					</div>
					
					<div id="bills_wrapper">
						<div id = "billerlist_tabs">
							
							<div id = "billers_tab_dark">
								<p class="tab_text">Billers</p>
							</div>
							
						</div>
						<div class="rule"></div>
						<div id="billers_maincontent">
							<form name="modify_biller_list" action=# method="POST">
								<div class="scroll-pane-arrows">
								
								<table>
								<?php display_billerlist()?>
								
								</table>
								
								</div>
								<div class="rule"></div>
								
								<div id="addform">
									<label for="accountnum">Account Number: </label>
									<input type="text" id="accountnum" name="accountnum" required/>
									<input type="submit" name="addbiller" value="Add"/>
								</p>
							</form>	
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
		
					
					$(function(){
						$('.scroll-pane').jScrollPane();
						$('.scroll-pane-arrows').jScrollPane(
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
				
		function removebiller(accountnum,accountname){
			var yes= confirm("Are you sure you want to remove "+accountname+"?");
			if (yes){
				window.location = "removefrombillerlist.php?accountnum="+accountnum;

			}
		}
		
		
		</script>
	</body>
</html>