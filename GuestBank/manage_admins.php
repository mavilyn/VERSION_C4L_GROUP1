<?php
	include("class_lib.php");
	session_start();
	
		$branchcode = $_SESSION['admin']->get_branchcode();
		echo $branchcode;
		$empid = "";
		
		if(isset($_POST['Submit'])){
			
			$empid = $_POST['empid'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$confirmpassword = $_POST['confirmpassword'];

			/*****************************************************/
			//check whether administrator is on the 
				$connGuest = oci_connect("guestbank", "kayato1");
					$stid = oci_parse($connGuest,
						'SELECT COUNT(*) AS NUM_ROWS
						FROM admins
						WHERE empid = '.$empid
					);
				oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
				oci_execute($stid);
				oci_fetch($stid);
				oci_close($connGuest);
			/******************************************************/
			if($num_rows > 0) {
				echo '<script type=text/javascript>alert("Employee already has an online account!");</script>';
			}
			
			else{
				//$num_rows2 =0;
				$connMain = oci_connect("mainbank", "kayato1");
				$adminEmpid = $_SESSION['admin']->get_empid();
				$empid = $_POST['empid'];
				$branchcode = $_SESSION['admin']->get_branchcode();
				
				$query2 = 'SELECT COUNT(*) AS NUM_ROWS2 FROM employee
					WHERE empid = :empid and branchcode = :branchcode';		
				$stid2 = oci_parse($connMain, $query2);

				oci_define_by_name($stid2, 'NUM_ROWS2', $num_rows2);
				oci_bind_by_name($stid2, ':empid', $empid);
				oci_bind_by_name($stid2, ':branchcode', $branchcode);
				oci_execute($stid2);
				oci_fetch($stid2);
				
				oci_close($connMain);
				
				if($num_rows2>0){
						$connGuest = oci_connect("guestbank", "kayato1");
						
						$stid = oci_parse($connGuest,
								"SELECT COUNT(*) AS NUM_ROWS3
								FROM users
								WHERE username = '".$_POST['username']."'"
							);
							

							oci_define_by_name($stid, 'NUM_ROWS3', $num_rows3);
							oci_execute($stid);
							oci_fetch($stid);
							$connMain = oci_connect("mainbank", "kayato1");
								if($num_rows3>0){
									echo "Username already exists.";	
								}
								
								else{
									
									$sql="select * from employee where empid =".$empid;

									$stmt = oci_parse($connMain, $sql);
									oci_execute($stmt, OCI_DEFAULT);

									while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
										$mgrflag=$row[6];
									}
									
									$password = md5(md5($_POST['password']));
	
									$stid5 = oci_parse($connGuest,
									'INSERT INTO users VALUES(:username, :password,'."'admin'".')'
											);
									oci_bind_by_name($stid5, ':username', $_POST['username']);
									oci_bind_by_name($stid5, ':password', $password);
									oci_execute($stid5);
									oci_close($connGuest);
									
									
									$empid = $_POST['empid'];

									$connMain = oci_connect("mainbank", "kayato1");
									$query = 'select * from employee where empid=:empid';
									$stid = oci_parse($connMain, $query);
									oci_bind_by_name($stid, ':empid', $empid);
									oci_execute($stid, OCI_DEFAULT);
			
									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[5];	
									}	

									$connGuest = oci_connect("guestbank", "kayato1");

									$stid4 = oci_parse($connGuest,'INSERT INTO admins VALUES(:username, :password, :empid, :mgrflag, :branchcode)'
											);
									oci_bind_by_name($stid4, ':username', $_POST['username']);
									oci_bind_by_name($stid4, ':password',$password);
									oci_bind_by_name($stid4, ':empid', $empid);
									oci_bind_by_name($stid4, ':mgrflag', $mgrflag);
									oci_bind_by_name($stid4, ':branchcode', $branchcode);
									oci_execute($stid4);
									
									echo '<script type=text/javascript>alert("Administrator has been successfully added! ");</script>';
									
								}
						oci_close($connGuest);
						oci_close($connMain);
					
			}
				
				else{
					echo '<script type=text/javascript>alert("Employee number does not exist!");</script>';
					$valid = false;
				}
			}
			
			unset($_POST['Submit']);
		}
		
		else if (isset($_POST['Submit_2'])) {
				$empid = $_POST['empid']; 
				$adminEmpid = $_SESSION['admin']->get_empid();
				/*
				$conn = oci_connect("guestbank", "kayato1");
						
									$empid = $_SESSION['admin']->get_empid();
								
								//	$connMain = oci_connect("mainbank", "kayato1");
									$query = 'select * from admins where empid=:empid';
									$stid = oci_parse($conn, $query);
									oci_bind_by_name($stid, ':empid', $empid);
									oci_execute($stid, OCI_DEFAULT);

									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[4];	
									}
				*/
				$connGuest = oci_connect("guestbank", "kayato1");

									$query = 'select * from admins where empid=:empid';
									$stid = oci_parse($connGuest, $query);
									oci_bind_by_name($stid, ':empid', $adminEmpid);
									oci_execute($stid, OCI_DEFAULT);

									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
										$branchcode = $row[4];	
									}

						$stid = oci_parse($connGuest,
								"SELECT COUNT(*) AS NUM_ROWS
								FROM admins
								WHERE empid = :empid and branchcode = :branchcode"
							);
							
							oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
							oci_bind_by_name($stid, ':branchcode', $branchcode);
							oci_bind_by_name($stid, ':empid', $empid);
							oci_execute($stid);
							oci_fetch($stid);
							//$connMain = oci_connect("mainbank", "kayato1");
								if($num_rows>0){	

									$sql="select * from admins where empid = :empid";

									$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt, ':empid', $empid);
									oci_execute($stmt, OCI_DEFAULT);

									while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
										$username=$row[0];
									}

									$stmt2 = oci_parse($connGuest,
										'DELETE FROM admins WHERE empid = :removeAdmin');
									//$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt2, ':removeAdmin', $_POST['empid']);
									oci_execute($stmt2);
									
									$stmt3 = oci_parse($connGuest,
										'DELETE FROM users WHERE username = :username');
									//$stmt = oci_parse($connGuest, $sql);
									oci_bind_by_name($stmt3, ':username', $username);
									oci_execute($stmt3);
									
									oci_close($connGuest);
									
									echo"<script>alert('Administrator account has been successfully removed.');return false;</script>";
								}
								else{
									echo "<script>alert('Employee number not found.');</script>";
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
		<script src="scripts/onlinebank.js" type="text/javascript"></script>
		
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
					<h1 id="functionality_header">Manage Admins</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "tm_tabs">
							
							<div id = "add_tab_light" class="tab_dark">
								<p class="tab_text">Add an Administrator</p>
							</div>
							
							<div id = "remove_tab_light" class="tab_light" >
								<p class="tab_text">Remove an Administrator</p>
							</div>
							
						
							
						</div>
						<div class="rule2"></div>
						<div id="add_maincontent">
							<form name = "addadmin_form" method ="post" action = "manage_admins.php" onsubmit="return check_addAdmin();">
								
								<label for="empid">Employee Number: </label>
								<input type="text" maxlength="10" id="empid"  name="empid" value="<?php //echo $empid;?>"/> 
								<span id="empidErr" class="err_span"></span>
								<br />
								
								<p id="uname_p">
								<label for="username">Username:</label>
								<input type="text" maxlength="20" id="username" name="username"/>
								<span id="usernameErr" class="err_span"></span>
								</p>
								
								<p id="pw_p">
								<label for="password">Password:</label>
								<input type="password" name="password" maxlength="20" id="password"/> 
								<span id="passwordErr" class="err_span"></span>
								</p>
								
								<p id="cpw_p">
								<label for="confirmpassword">Confirm Password: </label>
								<input type="password" name="confirmpassword" id="confirmpassword" maxlength="20"/> 
								<span id="confirmpasswordErr" class="err_span"></span>
								</p>
								<input type="submit" name="Submit" value="Add Admin" id="addadmin"/>	
							</form>
						
						</div>
						
						<div id="remove_maincontent">
						
						<form name = "removeAdmin_form" method ="post" action = "manage_admins.php" onsubmit="return checkRemove();">
							
							<label for="remove_empid">Enter Employee ID: <input type="text" name="empid" id="remove_empid"/> <br />
							<input type="submit" name="Submit_2" value="Remove" id="remove_admin"/>
						</form>	
						</div>
						
						<div class="rule2"></div>
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
			
			$('#add_maincontent').show();
			$('#remove_maincontent').hide();

			
			$('#add_tab_light').click(function(){
				$('#add_tab_light').addClass('tab_dark');
				$('#add_tab_light').removeClass('tab_light');
				$('#remove_tab_dark').attr('id', 'remove_tab_light');
	
			
				$('#add_maincontent').slideDown();
				$('#remove_maincontent').hide();
				
				});
			
			
			$('#remove_tab_light').click(function(){
				$('#add_tab_light').removeClass('tab_dark');
				$('#add_tab_light').addClass('tab_light');
				$('#add_tab_dark').attr('id', 'add_tab_light');
				$('#remove_tab_light').attr('id', 'remove_tab_dark');
				
				$('#add_maincontent').hide();
				$('#remove_maincontent').slideDown();
				
				});
			
		</script>
	</body>
</html>